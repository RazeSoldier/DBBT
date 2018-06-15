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

use DBBT\Backup\IBackup;

/**
 * Responsible for calling IBackup::dump()
 * @package DBBT\Action
 */
final class BackupAction implements IAction
{
    /**
     * @var IBackup
     */
    private $dumper;

    /**
     * BackupAction constructor.
     * @param IBackup $dumper
     */
    public function __construct(IBackup $dumper)
    {
        $this->dumper = $dumper;
    }

    /**
     * Execute IBackup::dump() method
     * @return mixed
     */
    public function execute()
    {
        return $this->dumper->dump();
    }
}