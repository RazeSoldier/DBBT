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

use DBBT\{
    Action\BackupAction,
    Action\StorageAction,
    Backup\Factory as BackupFactory,
    Storage\Factory as StorageFactory
};

final class Core implements IRunnable
{
    /**
     * @var Config
     */
    private $config;

    public function __construct()
    {
        $this->config = Config::getInstance();
        register_shutdown_function( [ $this, '__destruct' ] );
        pcntl_signal( SIGTERM, [ $this, 'signalHandler' ] );
        pcntl_signal( SIGKILL, [ $this, 'signalHandler' ] );
    }

    public function run()
    {
        session_start();
        $tmpPath = $this->backup();
        $this->storage( $tmpPath );
    }

    /**
     * Do backup
     * @return string The tmp file path of the dump file
     */
    private function backup() : string
    {
        if ( $this->config->get( 'BackupType' ) === 'logical' ) {
            $dumper = BackupFactory::make( $this->config->get( 'DBType' ) );
        } elseif ( $this->config->get( 'BackupType' ) === 'physical' ) {
            $dumper = BackupFactory::make( $this->config->get( 'physical' ) );
        } else {
            throw new \LogicException();
        }
        $action = new BackupAction( $dumper );
        $invoker = new Invoker( $action );
        return $_SESSION['dbbt_tmp'][] = $invoker->doAction();
    }

    private function storage(string $tmpPath)
    {
        if ( $this->config->get( 'StorageType' ) === 'local' ) {
            $storage = StorageFactory::make( 'local', $tmpPath, $this->config->get( 'StoragePath' ) );
        } elseif ( $this->config->get( 'StorageType' ) === 'remote' ) {
            $storage = StorageFactory::make( $this->config->get( 'RemoteType' ) );
        } else {
            throw new \LogicException();
        }
        $action = new StorageAction( $storage );
        $invoker = new Invoker( $action );
        if ( !$invoker->doAction() ) {
            throw new \RuntimeException( 'Failed to storage' );
        }
    }

    /**
     * Call when the application shutdown
     */
    public function __destruct()
    {
        // Clean up temporary files
        if ( isset( $_SESSION['dbbt_tmp'] ) && is_array( $_SESSION['dbbt_tmp'] ) ) {
            foreach ( $_SESSION['dbbt_tmp'] as $tmpFile ) {
                unlink( $tmpFile );
            }
            unset( $_SESSION['dbbt_tmp'] );
        }
    }

    /**
     * Signal handling
     * @param int $signo The signal number
     */
    private function signalHandler(int $signo)
    {
        switch ( $signo ) {
            case SIGTERM:
                die ( 1 );
            case SIGKILL:
                die ( 1 );
        }
    }
}