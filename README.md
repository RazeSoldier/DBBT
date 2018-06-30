# DBBT
Database Backup Tool

## What can this do?
This tool is written by PHP. If your website is also written by PHP, then using this tool does not require additional
environment requirements. (The version requirements are described in Compatibility section below)

This tool supports both physical and logical backups. Due physical backup just is file copy,
so the backup method supported all databases. However the logical backup only supports MySQL (and MariaDB),
in the future will support more databases.

This tool also supports both local and remote storage. The support storage way is detailed below.

## Compatibility
### PHP
DBBT requires PHP 7.0+.
### System
DBBT is well tested only on linux systems, other systems
(like: Windows) do not guarantee normal operation.

## Features
### Support database
* MySQL (and MariaDB)
* All database (in physics backup case)
### Support storage way
* Local file system storage
* [QCloud COS remote storage](https://intl.cloud.tencent.com/product/cos)
### Compress
* `tar.gz` (if you have `GNU tar` software)
* `zip` compress (if you have `Zip` PHP extension)

## Usage
**For user, it is more recommend to use the PHAR file**
### Source code usage way
1. Download the source code to your local.
2. `cd` to the source code directory.
3. `composer install --no-dev` (In this step, you must make sure you have [composer](https://getcomposer.org/)).
4. Copy `config.etc.php` to `config.php` and configure it. (NOTE: `config.etc.php` already includes configuration
structure. You can configure your backup tool on this basis)
5. `php run.php`, does not display any messages if execution succeed.
### PHAR file usage way
1. Download the PHAR release file from [the release center](https://github.com/RazeSoldier/DBBT/releases) to your local.
2. Create a config file based on
[the sample configuration file](https://github.com/RazeSoldier/DBBT/blob/master/config.etc.php).
3. `php dbbt.phar --config=<config file path>` where 'config file path' is the path of the config file you created.

## Support
If this tool on the way in the use of the problem or you have any ideas, please go to
the [Github issues](https://github.com/RazeSoldier/DBBT/issues).

## Contribute
The project welcomes anyone to contribute code. For more information, see `CONTRIBUTING.md` file.