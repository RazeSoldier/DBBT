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

abstract class AbstractFactory implements IFactory
{
    /**
     * @var array
     */
    protected static $classMap = [];

    /**
     * @var string
     */
    protected static $module;

    public static function make(string $name, ...$params)
    {
        if ( !self::hasClass( $name ) ) {
            throw new \LogicException( "Undefined class key: $name" );
        }
        $class = __NAMESPACE__ . '\\' . static::$module . '\\' . static::$classMap[$name];
        return new $class( Config::getInstance(), ...$params );
    }

    public static function makeAll() : array
    {
        foreach ( array_keys( static::$classMap ) as $class ) {
            $instances[$class] = self::make( $class );
        }
        if ( isset( $instances ) ) {
            throw new \LogicException( '$classMap is empty' );
        }
        return $instances;
    }

    public static function addClass(string $classKey, string $className) : bool
    {
        if ( static::hasClass( $classKey ) ) {
            throw new \LogicException( "$classKey class already added" );
        }
        static::$classMap[$classKey] = $className;
        return true;
    }

    public static function hasClass(string $name) : bool
    {
        return ( isset( static::$classMap[$name] ) ) ? true : false;
    }

    public static function getClassMap(): array
    {
        return static::$classMap;
    }
}