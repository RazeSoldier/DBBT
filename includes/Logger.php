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
 * Logger class
 * @package DBBT
 */
class Logger
{
    /**
     * @var string
     */
    private $logFilePath;

    /**
     * @var \SplFileObject
     */
    private $logFile;

    public function __construct(string $logFilePath)
    {
        // Checks $logFilePath can write @{
        if ( is_dir( $logFilePath ) ) {
            throw new \RuntimeException( '$gLogFilePath is a directory' );
        }
        if ( file_exists( $logFilePath ) ) {
            if ( !is_writable( $logFilePath ) ) {
                throw new \RuntimeException( "$logFilePath can't writable" );
            }
        } else {
            $dir = pathinfo( $logFilePath, PATHINFO_DIRNAME );
            if ( !is_writable( $dir ) ) {
                throw new \RuntimeException( "$dir can't writable" );
            }
        }
        // @}
        $this->logFilePath = $logFilePath;
        $this->logFile = new \SplFileObject( $this->logFilePath, 'ab' );
    }

    public function write(string $text) : bool
    {
        if ( $this->logFile->fwrite( $text ) === 0 ) {
            throw new \RuntimeException( 'Failed to write the log file' );
        }
        return true;
    }

    /**
     * Generate formatted messages
     * @param string $text The message text
     * @param string|null $level The level of the event
     * @return string The formatted message
     */
    public static function makeMessage(string $text, string $level = null) : string
    {
        $time = ( new \DateTime() )->format( 'Y-m-d H:i:s:u' );
        if ( $level !== null ) {
            $level = "($level)";
        }
        return "[$time]$level $text\n";
    }
}