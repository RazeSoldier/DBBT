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

namespace DBBT\Storage;

use DBBT\Config;

class LocalStorage implements IStorage
{
    private $sourcePath;

    private $targetPath;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config, string $source, string $target)
    {
        $this->config = $config;
        $this->sourcePath = $source;
        $this->targetPath = $target;
    }

    public function save() : bool
    {
        return copy( $this->sourcePath, $this->targetPath );
    }
}