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

namespace DBBT\Compress;
use DBBT\Command;
use DBBT\Config;

/**
 * Used to compress files to tar.gz tarball
 * Because PHP is no tar.gz compressed extension, so we try to call the external program
 * @package DBBT\Compress
 */
class TarGzCompress implements ICompress
{
    /**
     * @var string Source files path
     */
    private $source;

    /**
     * @var string The path you want to save
     */
    private $target;

    private $config;

    /**
     * TarGzCompress constructor.
     * @param Config $config
     * @param string $source Source files path
     * @param string $target The path you want to save
     */
    public function __construct(Config $config, string $source, string $target)
    {
        // Checks if there is tar.gz compressor @{
        $shell = new Command( 'tar' );
        $shell->execute();
        $shell->stop();
        if ( $shell->getStatus() === 1 ) {
            throw new \RuntimeException( 'Without tar.gz compressor' );
        }
        // @}
        if ( !is_writable( $source ) ) {
            throw new \RuntimeException( "$source can't be readable" );
        }
        $this->source = $source;
        if ( !is_writable( $path = pathinfo( $target, PATHINFO_DIRNAME ) ) ) {
            throw new \RuntimeException( "$path can't be writable" );
        }
        $this->target = $target;
        $this->config = $config;
    }

    public function compress()
    {
        $shell = new Command( "tar czf $this->target $this->source/*" );
        $shell->execute();
        $shell->stop();
        if ( $shell->getStatus() !== 0 ) {
            throw new \RuntimeException( 'Failed to compress tar.gz' );
        }
        return true;
    }
}