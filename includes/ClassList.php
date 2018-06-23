<?php
/**
 * Class map
 *
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

return [
    'AbstractFactory' => 'includes/AbstractFactory.php',
    'Action\BackupAction' => 'includes/action/BackupAction.php',
    'Action\CompressAction' => 'includes/action/CompressAction.php',
    'Action\IAction' => 'includes/action/IAction.php',
    'Action\StorageAction' => 'includes/action/StorageAction.php',
    'Backup\Factory' => 'includes/backup/Factory.php',
    'Backup\IBackup' => 'includes/backup/IBackup.php',
    'Backup\LogicalBackup' => 'includes/backup/LogicalBackup.php',
    'Backup\MySQLBackup' => 'includes/backup/MySQLBackup.php',
    'Backup\PhysicalBackup' => 'includes/backup/PhysicalBackup.php',
    'Checker' => 'includes/Checker.php',
    'CLIOption' => 'includes/CLIOption.php',
    'Command' => 'includes/Command.php',
    'Compress\Factory' => 'includes/compress/Factory.php',
    'Compress\ICompress' => 'includes/compress/ICompress.php',
    'Compress\TarGzCompress' => 'includes/compress/TarGzCompress.php',
    'Config' => 'includes/Config.php',
    'Core' => 'includes/Core.php',
    'IAccessor' => 'includes/IAccessor.php',
    'IChecker' => 'includes/IChecker.php',
    'IFactory' => 'includes/IFactory.php',
    'Invoker' => 'includes/Invoker.php',
    'IRunnable' => 'includes/IRunnable.php',
    'ISingleton' => 'includes/ISingleton.php',
    'Logger' => 'includes/Logger.php',
    'Storage\Factory' => 'includes/storage/Factory.php',
    'Storage\IStorage' => 'includes/storage/IStorage.php',
    'Storage\LocalStorage' => 'includes/storage/LocalStorage.php',
    'Storage\QcloudCOSBackup' => 'includes/storage/QcloudCOSBackup.php',
];