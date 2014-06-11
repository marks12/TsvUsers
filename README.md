## About
User management

## Install 

0. You must be sure that you have installed and included modules in your application.config.php befor TsvUsers:
 
	- DoctrineModule;
	- DoctrineORMModule;
	- ZfcBase;
	- ZfcUser;
	- BjyProfiler;
	- ZfcAdmin;
	- BjyAuthorize.

1. Install module via composer like this: $ composer require marks12/tsv-users:dev-master
2. Move and rename file ./vendor/marks12/tsv-users/config/tsvusers.local.php.dist to application autoload folder ./config/autoload
3. Configure your database connection (./config/autoload/local.php - recomended)
4. Insert into your database SQL dump from ./vendor/marks12/tsv-users/data/schema.sql

Tables of this module may differ from tables ZfcUser, therefore recommends store data from previously created tables module Zftsuser for later
 
## Enter
If you have base config for ZfcUser module, you can authorize in http://domain.nameuser/login
Then, if your user account have admin role, you can going to admin interface in ZfcAdmin module, for example http://domain.nameuser/admin and you will see 
new link in main menu like 'Пользователи'. Use this interfaces for user management.

