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

use DBBT\Command;

class CommandTest extends TestCase
{
    /**
     * @var Command
     */
    private $testInstance;

    protected function setUp()
    {
        $this->testInstance = new Command( 'ls -a' );
        $this->testInstance->execute();
    }

    public function testOutput()
    {
        $arr = explode( "\n", $this->testInstance->output() );
        $actual = array_filter( $arr, function ($var) {
            return empty( $var ) ? false : true;
        } );
        $count = 0;
        if ( $dh = opendir( '.' ) ) {
            while ( ( $file = readdir( $dh ) ) !== false ) {
                if ( in_array( $file, $actual, true ) ) {
                    $count++;
                }
            }
            closedir( $dh );
        }
        $this->assertEquals( $count, count( $actual ) );
    }
}