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

use DBBT\Compress\TarGzCompress;
use DBBT\Config;
use PHPUnit\Framework\TestCase;

class TarGzCompressTest extends TestCase
{
    private $dir = 'testTar';

    private $tmp = 'test.tmp';

    protected function setUp()
    {
        if ( file_exists( $this->dir ) ) {
            if ( is_dir( $this->dir ) ) {
                $this->delTree( $this->dir );
            } else {
                unlink( $this->dir );
            }
            mkdir( $this->dir );
        } else {
            mkdir( $this->dir );
        }
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
        $this->delTree( $this->dir );
        if ( file_exists( $this->tmp ) ) {
            unlink( $this->tmp );
        }
    }

    private function delTree(string $dir)
    {
        $files = array_diff( scandir( $dir ), [ '.', '..' ] );
        foreach ( $files as $file ) {
            ( is_dir("$dir/$file") ) ? $this->delTree( "$dir/$file" ) : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
