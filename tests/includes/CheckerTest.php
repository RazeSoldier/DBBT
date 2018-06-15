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

use DBBT\Checker;
use PHPUnit\Framework\TestCase;

class CheckerTest extends TestCase
{
    /**
     * @var Checker
     */
    protected $testInstance;

    protected function setUp()
    {
        $rules = [
            'config' => 'required',
            'dir' => 'required',
            'dbType' => [['cond' => 'equals', 'value' => 'mysql']],
            'basedir' => [
                'required',
                [
                    'cond' => 'equals',
                    'value' => 233
                ]
            ]
        ];
        $check = [
            'config' => 1,
            'dir' => null,
            'dbType' => 'sql',
            'basedir' => 233
        ];
        $this->testInstance = new Checker( $rules, $check );
    }

    /**
     * @throws \Exception
     */
    public function testCheck()
    {
        $this->assertEquals( true, $this->testInstance->check( 'config' ) );
        $this->assertEquals( false, $this->testInstance->check( 'dir' ) );
        $this->assertEquals( false, $this->testInstance->check( 'dbType' ) );
        $this->assertEquals( true, $this->testInstance->check( 'basedir' ) );
    }

    /**
     * @depends testCheck
     */
    public function testCheckAll()
    {
        $this->assertEquals( false, $this->testInstance->checkAll() );
    }
}
