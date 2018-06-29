<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @copyright
 */

namespace DBBT\backup;

use DBBT\Action\CompressAction;
use DBBT\Config;
use DBBT\Invoker;
use DBBT\Logger;

class PhysicalBackup implements IBackup
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var string The path to you want to backup, can be a file or a directory
     */
    private $dbPath;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string The user home directory
     */
    private $homeDir;

    public function __construct(Config $config, Logger $logger = null)
    {
        $this->config = $config;
        $this->dbPath = $config->get( 'DBPath' );
        // Checks $gDBPath @{
        if ( !is_readable( $this->dbPath ) ) {
            throw new \LogicException( "{$this->dbPath} can't readable or does not exists" );
        }
        // @}
        $this->logger = $logger;
        // Set PhysicalBackup::$homeDir @{
        $homeDir = getenv( 'HOME' );
        if ( $homeDir === false ) {
            throw new \RuntimeException( "'HOME' environment variable does not exist" );
        }
        if ( !is_writable( $homeDir ) ) {
            throw new \RuntimeException( "$homeDir does not writable" );
        }
        $this->homeDir = $homeDir;
        // @}
    }

    public function dump() : string
    {
        // If $gDBPath is a directory, pack all files in this directory before moving
        if ( is_dir( $this->dbPath ) ) {
            $tmp = $this->dirHandler( $this->dbPath );
        } elseif ( is_file( $this->dbPath ) ) {
            $tmp = $this->homeDir . '/db.dump';
            if ( !copy( $this->dbPath, $tmp ) ) {
                throw new \RuntimeException( 'Failed to copy' );
            }
        } else {
            throw new \LogicException();
        }
        return $tmp;
    }

    private function dirHandler(string $dir) : string
    {
        if ( $this->config->has( 'CompressType' ) ) {
            if ( empty( $type = $this->config->get( 'CompressType' ) ) ) {
                $type = 'zip';
            }
        } else {
            // If $gCompressType undefined. use 'zip'
            $type = 'zip';
        }
        // Get $target @{
        switch ( $type ) {
            case 'tar.gz':
                $target = $this->homeDir . '/db.tar.gz';
                break;
            case 'zip':
                $target = $this->homeDir . 'db.zip';
                break;
            default :
                throw new \LogicException( "Undefined type: '$type'" );
        }
        // @}
        $compressor = \DBBT\Compress\Factory::make( $type, $dir, $target );
        $action = new CompressAction( $compressor, $this->logger );
        $invoker = new Invoker( $action );
        $invoker->doAction();
        return $target;
    }
}