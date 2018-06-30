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

namespace DBBT\Action;

use DBBT\{
    Logger,
    Storage\IStorage
};

final class StorageAction implements IAction
{
    /**
     * @var IStorage
     */
    private $storage;

    /**
     * @var Logger|null
     */
    private $logger;

    /**
     * StorageAction constructor.
     * @param IStorage $storage
     * @param Logger|null $logger
     */
    public function __construct(IStorage $storage, Logger $logger = null)
    {
        $this->storage = $storage;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function execute() : bool
    {
        $result = $this->storage->save();
        if ( $this->logger !== null ) {
            $this->logger->write( Logger::makeMessage( 'Succeed storage dump file', 'Notice' ) );
        }
        return $result;
    }
}