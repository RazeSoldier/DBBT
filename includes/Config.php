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
use DBBT\CLIOption;

/**
 * The facade of Config model
 * @package DBBT\Config
 */
final class Config implements ISingleton, IAccessor
{
    /**
     * @var Config
     */
    private static $instance;

    /**
     * @var array
     */
    private $configs = [];

    private function __construct()
    {
        # Load config file @{
        if ( CLIOption::getInstance()->has( 'config' ) ) {
            $configPath = CLIOption::getInstance()->get('config');
        } elseif ( defined( 'PHPUNIT_TEST' ) ) {
            $configPath = APP_PATH . '/tests/TestConfig.php';
        } else {
            $configPath = APP_PATH . '/config.php';
        }
        if ( is_readable( $configPath ) ) {
            require_once $configPath;
        } else {
            throw new \RuntimeException( "$configPath can't be readable" );
        }
        # @}
        // Get config options @{
        foreach ( get_defined_vars() as $varName => $varValue ) {
            if ( strpos( $varName, 'g' ) === 0 ) {
                $varName = substr( $varName, 1 );
                $configs[$varName] = $varValue;
            }
        }
        if ( !isset( $configs ) ) {
            throw new \LogicException( 'Without config variables' );
        }
        // @}
        $this->configs = $configs;
        $this->checkConfigs();
    }

    private function checkConfigs()
    {
        $rules = [
            'StorageType' => [
                'required',
                [
                    'cond' => 'equals',
                    'value' => [
                        'local',
                        'remote'
                    ]
                ]
            ],
            'BackupType' => [
                'required',
                [
                    'cond' => 'equals',
                    'value' => [
                        'logical',
                        'physical'
                    ]
                ]
            ]
        ];
        $checker = new Checker( $rules, $this->configs, true );
        $checker->checkAll();
    }

    /**
     * @return Config
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
        if ( $this->has( $name ) ) {
            return $this->configs[$name];
        }
        throw new \LogicException( "$name config option does not exist" );
    }

    public function has(string $name) : bool
    {
        return ( isset( $this->configs[$name] ) ) ? true : false;
    }
}