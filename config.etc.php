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

# Storage config
$gStorageType = ''; // Required, storage type (Allow value: 'local' or 'remote')
// If $gStorageType set to 'remote', please configure @{
$gRemoteType = ''; // Remote type (Allow value: 'qcloud')
# ... Other remote configuration
// @}

# Other config
$gLogFilePath = ''; // The path to the log file, if it set to NULL or an empty string, logging will disable