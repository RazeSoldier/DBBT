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

interface IFactory
{
    /**
     * Create a new instance
     * @param string $name The class abbreviation
     * @return mixed
     */
    public static function make(string $name);

    /**
     * Create all new instance from the class map
     * @return array
     */
    public static function makeAll() : array;

    /**
     * Add a new class to the class map
     * @param string $classKey The class abbreviation
     * @param string $className The class name
     * @return bool Returns TRUE if success, FALSE otherwise
     */
    public static function addClass(string $classKey, string $className) : bool;

    /**
     * Checks if $name class already defined
     * @param string $name The class abbreviation
     * @return bool Returns TRUE if defined, FALSE otherwise
     */
    public static function hasClass(string $name) : bool;

    /**
     * Get a class map of a factory
     * @return array A class map
     */
    public static function getClassMap() : array;
}