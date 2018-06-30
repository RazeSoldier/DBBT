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

namespace DBBT\Test;

use PHPUnit\Framework\TestCase as phpunit;

/**
 * Extending PHPUnit\Framework\TestCase class to add commonly used methods
 * @package DBBT\Test
 */
abstract class TestCase extends phpunit
{
    /**
     * As of $tree, create a directory tree
     * @param array $tree Directory tree
     * @param string|null $parent The parent directory of $tree
     */
    protected function makeTree(array $tree, string $parent = null)
    {
        foreach ( $tree as $key => $file ) {
            if ( is_array( $file ) ) {
                if ( $parent === null ) {
                    $dir = $key;
                    mkdir( $dir );
                } else {
                    $dir = $parent . '/' . $key;
                    mkdir( $dir );
                }
                $this->makeTree( $file, $dir );
                return;
            }
            if ( is_string( $file ) ) {
                if ( $parent === null ) {
                    $path = $file;
                } else {
                    $path = $parent . '/' . $file;
                }
                file_put_contents( $path, '' );
            }
        }
    }

    /**
     * Traverse a directory
     * @param string $dir
     * @return array Directory structure
     */
    protected function resDir(string $dir) : array
    {
        $iterator = new \DirectoryIterator( $dir );
        foreach ( $iterator as $fileInfo ) {
            if ( !$fileInfo->isDot() ) {
                if ( $fileInfo->isDir() ) {
                    $dirStructure[$fileInfo->getFilename()] = $this->resDir( $fileInfo->getRealPath() );
                    continue;
                }
                $dirStructure[] = $fileInfo->getFilename();
            }
        }
        if ( !isset( $dirStructure ) ) {
            throw new \RuntimeException();
        }
        return $dirStructure;
    }

    /**
     * Delete a directory
     * @param string $dir
     * @return bool
     */
    protected function delTree(string $dir)
    {
        $files = array_diff( scandir( $dir ), [ '.', '..' ] );
        foreach ( $files as $file ) {
            ( is_dir( "$dir/$file" ) ) ? $this->delTree( "$dir/$file" ) : unlink( "$dir/$file" );
        }
        return rmdir( $dir );
    }

    /**
     * Batch checking whether the files in the array exists
     * @param array $arr Number array, includes path to check
     * @param callable|null $funName Called after checked a file
     * @return array
     */
    protected function batchCheckFileExists(array $arr, callable $funName = null) : array
    {
        foreach ( $arr as $dirPath ) {
            if ( file_exists( $dirPath ) ) {
                if ( is_dir( $dirPath ) ) {
                    $status = 'dir';
                } else {
                    $status = 'file';
                }
            } else {
                $status = false;
            }
            call_user_func( $funName, $dirPath, $status );
            $result[$dirPath] = $status;
        }
        return $result;
    }

    /**
     * Batch delete files
     * @param array $arr Number array, includes path to delete
     */
    protected function batchDelete(array $arr)
    {
        $this->batchCheckFileExists( $arr, function (string $dirPath, $status) {
            if ( $status === 'dir' ) {
                $this->delTree( $dirPath );
            }
            if ( $status === 'file' ) {
                unlink( $dirPath );
            }
        } );
    }
}