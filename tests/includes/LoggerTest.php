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

use DBBT\Logger;

class LoggerTest extends TestCase
{
    private $tmpPath;

    protected function setUp()
    {
        $this->tmpPath = __DIR__ . '/test.tmp';
    }

    public function testWrite()
    {
        $expected = 'This is a test.';
        $logger = new Logger( $this->tmpPath );
        $logger->write( 'This is a test.' );
        unset( $logger );

        $this->assertEquals( $expected, file_get_contents( $this->tmpPath ) );
    }

    protected function tearDown()
    {
        if ( file_exists( $this->tmpPath ) ) {
            unlink( $this->tmpPath );
        }
    }
}
