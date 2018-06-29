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
}
