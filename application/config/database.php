<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7.
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'accountdb';
$active_record = TRUE;

$db['accountdb']['hostname'] = 'localhost';
$db['accountdb']['username'] = 'useragent';
$db['accountdb']['password'] = 'carM2u6ZTJq5fJWM';
$db['accountdb']['database'] = 'agent_account_db';
$db['accountdb']['dbdriver'] = 'mysql';
$db['accountdb']['dbprefix'] = '';
$db['accountdb']['pconnect'] = FALSE;
$db['accountdb']['db_debug'] = TRUE;
$db['accountdb']['cache_on'] = FALSE;
$db['accountdb']['cachedir'] = '';
$db['accountdb']['char_set'] = 'utf8';
$db['accountdb']['dbcollat'] = 'utf8_general_ci';
$db['accountdb']['swap_pre'] = '';
$db['accountdb']['autoinit'] = TRUE;
$db['accountdb']['stricton'] = FALSE;

$db['productdb']['hostname'] = 'localhost';
$db['productdb']['username'] = 'useragent';
$db['productdb']['password'] = 'carM2u6ZTJq5fJWM';
$db['productdb']['database'] = 'agent_product_db';
$db['productdb']['dbdriver'] = 'mysql';
$db['productdb']['dbprefix'] = '';
$db['productdb']['pconnect'] = FALSE;
$db['productdb']['db_debug'] = TRUE;
$db['productdb']['cache_on'] = FALSE;
$db['productdb']['cachedir'] = '';
$db['productdb']['char_set'] = 'utf8';
$db['productdb']['dbcollat'] = 'utf8_general_ci';
$db['productdb']['swap_pre'] = '';
$db['productdb']['autoinit'] = TRUE;
$db['productdb']['stricton'] = FALSE;

$db['fundsdb']['hostname'] = 'localhost';
$db['fundsdb']['username'] = 'useragent';
$db['fundsdb']['password'] = 'carM2u6ZTJq5fJWM';
$db['fundsdb']['database'] = 'agent_funds_flow_db';
$db['fundsdb']['dbdriver'] = 'mysql';
$db['fundsdb']['dbprefix'] = '';
$db['fundsdb']['pconnect'] = FALSE;
$db['fundsdb']['db_debug'] = TRUE;
$db['fundsdb']['cache_on'] = FALSE;
$db['fundsdb']['cachedir'] = '';
$db['fundsdb']['char_set'] = 'utf8';
$db['fundsdb']['dbcollat'] = 'utf8_general_ci';
$db['fundsdb']['swap_pre'] = '';
$db['fundsdb']['autoinit'] = TRUE;
$db['fundsdb']['stricton'] = FALSE;

$db['logdb']['hostname'] = 'localhost';
$db['logdb']['username'] = 'useragent';
$db['logdb']['password'] = 'carM2u6ZTJq5fJWM';
$db['logdb']['database'] = 'agent_log_db_201203';
$db['logdb']['dbdriver'] = 'mysql';
$db['logdb']['dbprefix'] = '';
$db['logdb']['pconnect'] = FALSE;
$db['logdb']['db_debug'] = TRUE;
$db['logdb']['cache_on'] = FALSE;
$db['logdb']['cachedir'] = '';
$db['logdb']['char_set'] = 'utf8';
$db['logdb']['dbcollat'] = 'utf8_general_ci';
$db['logdb']['swap_pre'] = '';
$db['logdb']['autoinit'] = TRUE;
$db['logdb']['stricton'] = FALSE;

$db['log_cachedb']['hostname'] = 'localhost';
$db['log_cachedb']['username'] = 'useragent';
$db['log_cachedb']['password'] = 'carM2u6ZTJq5fJWM';
$db['log_cachedb']['database'] = 'agent_log_db';
$db['log_cachedb']['dbdriver'] = 'mysql';
$db['log_cachedb']['dbprefix'] = '';
$db['log_cachedb']['pconnect'] = FALSE;
$db['log_cachedb']['db_debug'] = TRUE;
$db['log_cachedb']['cache_on'] = FALSE;
$db['log_cachedb']['cachedir'] = '';
$db['log_cachedb']['char_set'] = 'utf8';
$db['log_cachedb']['dbcollat'] = 'utf8_general_ci';
$db['log_cachedb']['swap_pre'] = '';
$db['log_cachedb']['autoinit'] = TRUE;
$db['log_cachedb']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
