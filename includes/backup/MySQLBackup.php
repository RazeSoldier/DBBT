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

namespace DBBT\Backup;

use DBBT\Command;

class MySQLBackup extends LogicalBackup
{
    protected $commandPrefix = 'mysqldump';

    private function getCommand() : string
    {
        $host = escapeshellarg( $this->config->get( 'DBHost' ) );
        $username = escapeshellarg( $this->config->get( 'DBUsername' ) );
        $password = escapeshellarg( $this->config->get( 'DBPassword' ) );
        $dbs = escapeshellarg( $this->config->get( 'DBWantDump' ) );
        if ( $dbs === null || $dbs === 'all' ) {
            $database =  '--all-databases';
        } elseif ( is_array( $dbs ) ) {
            $database = '--databases';
            foreach ( $dbs as $db ) {
                $database .= ' ' . $db;
            }
        } elseif ( is_string( $dbs ) ) {
            $database = $dbs;
        } else {
            throw new \LogicException( '$gDBWantDump value does not allow' );
        }
        $option = '--opt';
        $target = getenv( 'HOME' );
        if ( $target === false ) {
            throw new \RuntimeException( "'HOME' environment variable does not exist" );
        }
        if ( !is_writable( $target ) ) {
            throw new \RuntimeException( "$target does not writable" );
        }
        $this->tmpPath = $target . '/db.dump';
        return $this->commandPrefix . " -h{$host} -u{$username} -p$password {$database} $option > $this->tmpPath";
    }

    public function dump()
    {
        $shell = new Command( $this->getCommand() );
        $shell->execute();
        $shell->stop();
        if ( $shell->getStatus() !== 0 ) {
            echo "Error message:\n" . $shell->getErrorMsg() . "\n";
            throw new \RuntimeException( 'Failed to dump, exit code: ' . $shell->getStatus() );
        }
        return $this->tmpPath;
    }
}