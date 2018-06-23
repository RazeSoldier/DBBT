# DBBT
Database Backup Tool

## Features
### Support database
* MySQL (and MariaDB)
### Support storage way
* [QCloud COS remote storage](https://intl.cloud.tencent.com/product/cos)

## Usage
**For user, it is more recommend to use the PHAR file**
### Source code usage way
1. Download the source code to your local.
2. `cd` to the source code directory.
3. `composer install --no-dev` (In this step, you must make sure you have [composer](https://getcomposer.org/)).
4. Copy `config.etc.php` to `config.php` and configure it. (NOTE: `config.etc.php` already includes configuration structure. You can configure your backup tool on this basis)
5. `php run.php`, does not display any messages if execution succeed.
### PHAR file usage way
... TO DO

## Support
If this tool on the way in the use of the problem or you have any ideas, please go to the [Github issues](https://github.com/RazeSoldier/DBBT/issues).
