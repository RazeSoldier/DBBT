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
    Compress\TarGzCompress,
    Config
};

class TarGzCompressTest extends TestCase
{
    private $dir = 'testTar';

    private $tmp = 'test.tmp';

    private $tmpList;

    protected function setUp()
    {
        $this->tmpList = [ $this->dir, $this->tmp ];
        $this->batchDelete( $this->tmpList );
        mkdir( $this->dir );
        for ( $i = 0; $i < 10; $i++ ) {
            file_put_contents( $this->dir. "/$i", $i );
        }
    }

    public function testCompress()
    {
        $compressor = new TarGzCompress( Config::getInstance(), $this->dir, 'test.tmp' );
        $this->assertTrue( $compressor->compress() );
    }

    protected function tearDown()
    {
        $this->batchDelete( $this->tmpList );
    }
}
