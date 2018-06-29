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

use DBBT\{
    Config,
    Compress\ZipCompress
};
use PHPUnit\Framework\TestCase;

class ZipCompressTest extends TestCase
{
    private $dir = 'tesZip';

    private $tmp = 'test.zip';

    private $extractDir = 'extracted';

    /**
     * @var array Directory structure used to testing
     */
    private $fileMap = [
        0 => '1',
        '1' => 'test.html',
        'test' => [
            '1.txt',
            '2' => [
                '5',
                '6'
            ]
        ]
    ];

    protected function setUp()
    {
        // If the test directory already exists, delete it @{
        if ( file_exists( $this->dir ) ) {
            if ( is_dir( $this->dir ) ) {
                $this->delTree( $this->dir );
            } else {
                unlink( $this->dir );
            }
        }
        if ( file_exists( $this->tmp ) ) {
            if ( is_dir( $this->tmp ) ) {
                $this->delTree( $this->tmp );
            } else {
                unlink( $this->tmp );
            }
        }
        if ( file_exists( $this->extractDir ) ) {
            if ( is_dir( $this->extractDir ) ) {
                $this->delTree( $this->extractDir );
            } else {
                unlink( $this->extractDir );
            }
        }
        // @}
        // Make test case
        mkdir( $this->dir );
        $this->makeTree( $this->fileMap, $this->dir );
    }

    public function testCompress()
    {
        $compressor = new ZipCompress( Config::getInstance(), $this->dir, $this->tmp );
        $compressor->compress();
        $zip = new \ZipArchive();
        $zip->open( $this->tmp );
        $zip->extractTo( $this->extractDir );
        $this->assertEquals( $this->fileMap, $this->resDir( $this->extractDir ) );
    }

    protected function tearDown()
    {
        if ( is_dir( $this->dir ) ) {
            $this->delTree( $this->dir );
        }
        if ( is_dir( $this->extractDir ) ) {
            $this->delTree( $this->extractDir );
        }
        if ( file_exists( $this->tmp ) ) {
            unlink( $this->tmp );
        }
    }

    /**
     * As of $tree, create a directory tree
     * @param array $tree Directory tree
     * @param string|null $parent The parent directory of $tree
     */
    private function makeTree(array $tree, string $parent = null)
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
    private function resDir(string $dir) : array
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
    private function delTree(string $dir)
    {
        $files = array_diff( scandir( $dir ), [ '.', '..' ] );
        foreach ( $files as $file ) {
            ( is_dir( "$dir/$file" ) ) ? $this->delTree( "$dir/$file" ) : unlink( "$dir/$file" );
        }
        return rmdir( $dir );
    }
}
