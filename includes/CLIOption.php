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
    const SHORT_OPTION_IDENTIFIER = '-';
    const LONG_OPTION_IDENTIFIER = '--';

    /**
     * @var CLIOption
     */
    private static $instance;

    private $options = [];

    private $shortOptions = [];

    private function __construct()
    {
        foreach ( $_SERVER['argv'] as $option ) {
            $pattern = '/^'. self::LONG_OPTION_IDENTIFIER . '(?<name>.*)=/';
            // Determines if $option is a long option
            if ( preg_match_all( $pattern, $option, $matches ) > 0 ) {
                $this->options[$matches['name'][0]] = str_replace( self::LONG_OPTION_IDENTIFIER
                    . "{$matches['name'][0]}=", null, $option );
                continue;
            }
            $pattern = '/^'. self::SHORT_OPTION_IDENTIFIER . '(?<name>.*)/';
            // Determines if $option is a short option
            if ( preg_match_all( $pattern, $option, $matches ) > 0 ) {
                $this->shortOptions[] = $matches['name'][0];
            }
        }
        // Show the help message when command options includes -h
        if ( in_array( 'h', $this->shortOptions ) ) {
            $this->showHelpMessage();
            die( 0 );
        }
    }

    private function showHelpMessage()
    {
        echo <<<TEXT
Usage: php run.php
       php run.php --config=<config file path>
 --config Customize the configuration file path instead of the default config.php
TEXT;
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