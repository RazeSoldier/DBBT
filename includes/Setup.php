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

// Define the absolute path to the root directory of this project
define( 'APP_PATH', dirname(__DIR__) );

# For security, this script can only be run in cli mode
if ( PHP_SAPI !== 'cli' ) {
    echo "For security, this script can only be run in cli mode.\n";
    die( 1 );
}

cli_set_process_title( 'DBBT' );

# Verify the version for PHP, requires PHP version 7.0 or later
if ( version_compare( PHP_VERSION, '7.0.0', '<' ) ) {
    trigger_error( "DBBT requires PHP version 7.0 or later\n", E_USER_ERROR );
}

require_once APP_PATH . '/includes/AutoLoader.php';

require_once APP_PATH . '/vendor/autoload.php';