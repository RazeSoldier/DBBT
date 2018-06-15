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

namespace DBBT\Backup;

use DBBT\Config;

abstract class LogicalBackup implements IBackup
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Like: /usr/bin/mysqldump
     * @var string
     */
    protected $commandPrefix;

    /**
     * @var string Complete order
     */
    protected $command;

    /**
     * @var string
     */
    protected $tmpPath;

    public function __construct(Config $config)
    {
        $this->config = $config;
        if ( $this->config->has( 'DBCommandPrefix' ) ) {
            $this->commandPrefix = $this->config->get( 'DBCommandPrefix' );
        }
    }
}