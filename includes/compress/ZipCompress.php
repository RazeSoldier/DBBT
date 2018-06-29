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

use DBBT\Config;

class ZipCompress implements ICompress
{
    private $config;

    private $source;

    private $target;

    /**
     * @var \ZipArchive
     */
    private $zip;

    public function __construct(Config $config, string $source, string $target)
    {
        if ( !extension_loaded( 'zip' ) ) {
            throw new \RuntimeException( 'PHP ZIP extension unavailable' );
        }
        $this->config = $config;
        if ( !is_writable( $source ) ) {
            throw new \RuntimeException( "$source can't be readable" );
        }
        $this->source = realpath( $source );
        if ( !is_writable( $path = pathinfo( $target, PATHINFO_DIRNAME ) ) ) {
            throw new \RuntimeException( "$path can't be writable" );
        }
        $this->target = $target;
        $this->zip = new \ZipArchive();
        if ( $code = $this->zip->open( $this->target, \ZipArchive::CREATE ) !== true ) {
            throw new \RuntimeException( "Failed to create a new zip archive, error code: $code" );
        }
    }

    public function compress()
    {
        $this->resAddDir( $this->source );
        $this->zip->close();
        return true;
    }

    /**
     * Recursive to add all the files under $dir to the zip archive
     * @param string $dir
     */
    private function resAddDir(string $dir)
    {
        static $resCount = 0;
        $iterator = new \DirectoryIterator( $dir );
        if ( is_dir( $dir ) ) {
            $options['remove_path'] = $dir;
            if ( $resCount !== 0 ) {
                $options['add_path'] = str_replace( $this->source . DIRECTORY_SEPARATOR, null, $dir ) . '/';
            }
            $this->zip->addPattern( '/.*/', $dir, $options );
        } else {
            throw new \LogicException();
        }
        foreach ( $iterator as $fileInfo ) {
            if ( !$fileInfo->isDot() && $fileInfo->isDir() ) {
                $resCount++;
                $this->resAddDir( $fileInfo->getRealPath() );
            }
        }
    }
}