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
 * This singleton class can access the cli long options that pass to DBBT
 * @package DBBT
 */
final class CLIOption implements ISingleton, IAccessor
{
    const LONG_OPTION_IDENTIFIER = '--';

    /**
     * @var CLIOption
     */
    private static $instance;

    private $options = [];

    private function __construct()
    {
        foreach ( $_SERVER['argv'] as $option ) {
            $pattern = '/^'. self::LONG_OPTION_IDENTIFIER . '(?<name>.*)=/';
            // Determines if $option is a long option
            if ( preg_match_all( $pattern, $option, $matches ) > 0 ) {
                $this->options[$matches['name'][0]] = str_replace( self::LONG_OPTION_IDENTIFIER
                    . "{$matches['name'][0]}=", null, $option );
            }
        }
    }

    /**0
     * @return CLIOption
     */
    public static function getInstance()
    {
        if ( self::$instance === null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function get(string $name)
    {
        if ( !$this->has( $name ) ) {
            throw new \LogicException( "$name option does not exist" );
        }
        return $this->options[$name];
    }

    public function has(string $name) : bool
    {
        return ( isset( $this->options[$name] ) ) ? true : false;
    }
}