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

namespace DBBT;

/**
 * Batch check the data as match expected
 * @package DBBT
 */
class Checker implements IChecker
{
    const ALLOW_RULES = [
        'required',
        'equals'
    ];

    /**
     * @var array Inspection conditions
     * - The item as a key, the check condition as the value
     * - Array = [
     * -    'config' => 'required',
     * -    'type' => [
     * -        0 => [
     * -            'cond' => 'equals',
     * -            'value' => 1
     * -        ]
     * -     ],
     * -     'basedir' => [
     * -         0 => 'required'.
     * -         1 => [
     * -            'cond' => 'equals',
     * -             'value' => 233,
     * -          ]
     * -     ]
     * - ]
     */
    private $rules = [];

    /**
     * @var array A index array
     * - The check name as a key
     * - Array = [
     * -    'config' => '/etc',
     * -    'type' => 1,
     * -    'basedir' => 233
     * - ]
     */
    private $checks = [];

    /**
     * @var bool
     */
    private $strongJudge;

    /**
     * Checker constructor.
     * @param array $rules Checks rules
     * @param array $needChecks
     * @param bool $strongJudge Whether or not to throw an exception if it does not meet expected
     */
    public function __construct(array $rules, array $needChecks, bool $strongJudge = false)
    {
        $this->rules = $rules;
        $this->checks = $needChecks;
        $this->strongJudge = $strongJudge;
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function check(string $name) : bool
    {
        if ( !isset( $this->rules[$name] ) ) {
            throw new \LogicException( "Undefined rule item: $name" );
        }
        $rules = $this->rules[$name];
        $actual = $this->checks[$name];
        // Do check @{
        if ( is_array( $rules ) ) {
            foreach ( $rules as $rule ) {
                if ( !is_array( $rule ) ) {
                    $result = $this->doCheck( $rule, $actual );
                    continue;
                }
                if ( !isset( $rule['cond'], $rule['value'] ) ) {
                    throw new \LogicException( "Missing some parameters" );
                }
                $result = $this->doCheck( $rule['cond'], $actual, $rule['value'] );
            }
        } else {
            $result = $this->doCheck( $rules, $actual );
        }
        // @}
        if ( !$result['ok'] && $this->strongJudge ) {
            throw new \Exception( "$name check failed: {$result['checker']} return FALSE", 10 );
        }
        return $result['ok'];
    }

    private function doCheck(string $checkType, $actual, ...$params) : array
    {
        switch ( $checkType ) {
            case 'required':
                $result['ok'] = $this->checkRequired( $actual );
                $result['checker'] = 'checkRequired()';
                break;
            case 'equals':
                $result['ok'] = $this->checkEquals( $actual, ...$params );
                $result['checker'] = 'checkEquals()';
                break;
            default :
                throw new \LogicException( "Unknown check type: $checkType" );
        }
        return $result;
    }

    public function checkAll() : bool
    {
        foreach ( array_keys( $this->rules ) as $checkName ) {
            try {
                if ( !$this->check( $checkName ) ) {
                    return false;
                }
            } catch ( \Exception $e ) {
                echo $e . "\n";
                if ( $e->getCode() !== 10 ) {
                    die( 1 );
                }
            }
        }
        return true;
    }

    public function checkRequired($value) : bool
    {
        return ( isset( $value ) ) ? true : false;
    }

    public function checkEquals($actual, $expected) : bool
    {
        if ( is_array( $expected ) ) {
            foreach ( $expected as $value ) {
                if ( $value === $actual ) {
                    return true;
                }
            }
            return false;
        } else {
            return ( $actual === $expected ) ? true : false;
        }
    }
}