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

    private $tmpList = [];

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
        $this->tmpList = [ $this->dir, $this->tmp, $this->extractDir ];
        $this->batchDelete( $this->tmpList );
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
        $this->assertTrue( isset( $this->resDir( $this->extractDir )['test'] ) );
    }

    protected function tearDown()
    {
        $this->batchDelete( $this->tmpList );
    }
}
