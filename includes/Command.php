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

class Command
{
    const STDIN = 0;

    const STDOUT = 1;

    const STDERR = 2;

    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var string
     */
    private $env;

    /**
     * @var int|null
     */
    private $status;

    /**
     * @var resource
     */
    private $process;

    /**
     * @var array
     */
    private $pipes;

    private $errorMsg;

    /**
     * Command constructor.
     * @param string $command
     * @param string|null $cwd
     * @param string|null $env
     */
    public function __construct(string $command, string $cwd = null, string $env = null)
    {
        $this->command = escapeshellcmd( $command );
        $this->cwd = $cwd;
        $this->env = $env;
    }

    public function execute()
    {
        if ( $this->status !== null ) {
            throw new \LogicException( 'Can\'t call this method repeatedly in the same object' );
        }
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $this->process = proc_open( $this->command,$descriptorspec, $this->pipes, $this->cwd, $this->env );
        if ( !is_resource( $this->process ) ) {
            throw new \RuntimeException( 'proc_open() returns non-resource type' );
        }
    }

    public function input(string $in)
    {
        $int =  fwrite( $this->pipes[self::STDIN], $in );
        fclose( $this->pipes[self::STDIN] );
        return $int;
    }

    public function output()
    {
        return stream_get_contents( $this->pipes[self::STDOUT] );
    }

    public function getMeta() : array
    {
        return proc_get_status( $this->process );
    }

    public function getErrorMsg()
    {
        return stream_get_contents( $this->pipes[self::STDERR] );
    }

    public function stop()
    {
        $this->errorMsg = $this->getErrorMsg();
        $this->__destruct();
    }

    public function closePipe()
    {
        foreach ( $this->pipes as $pipe ) {
            if ( is_resource( $pipe ) ) {
                fclose( $pipe );
            }
        }
    }

    public function __destruct()
    {
        if ( is_resource( $this->process ) ) {
            $this->closePipe();
            $this->status = proc_close( $this->process );
            if ( $this->status === -1 ) {
                throw new \RuntimeException( 'Failed to close the process' );
            }
        }
    }

    /**
     * @return int|null
     */
    public function getStatus() : int
    {
        return $this->status;
    }
}