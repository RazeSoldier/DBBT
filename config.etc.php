<?php
/**
 * Sample configuration
 */

# Backup config
$gBackupType = ''; // Required, backup type (Allow value: 'logical' or 'physical')
// If $gBackupType set to 'logical', please configure @{
$gDBType = ''; // The database software type (Allow value: 'mysql')
$gDBHost = ''; // The database address
$gDBUsername = ''; // The database username that used to backup
$gDBPassword = ''; // The database password that used to backup
// The databases you want to back up
// Allow value: a single database name, an array that includes multiple database names or 'all' (backup all databases)
$gDBWantDump = '';
// @}
// If $gBackupType set to 'physical', please configure @{
$gDBPath = ''; // The path to you want to backup, can be a file or a directory
# If $gDBPath is a directory, please also configure $gCompressType
$gCompressType = ''; // The compress type (Allow value: 'tar.gz', 'zip', '' or null)
// @}

# Storage config
$gStorageType = ''; // Required, storage type (Allow value: 'local' or 'remote')
// If $gStorageType set to 'remote', please configure @{
$gRemoteType = ''; // Remote type (Allow value: 'qcloud')
# ... Other remote configuration
// @}
// If $gStorageType set to 'local', please configure @{
$gStoragePath = ''; // The path to you want to save
// @}

# Other config
$gLogFilePath = ''; // Optional, the path to the log file, if it set to NULL or an empty string, logging will disable
