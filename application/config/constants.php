<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('VERIFY_LOGIN_TOKEN_ERROR_NO_PARAM', -1);	//参数缺失
define('VERIFY_LOGIN_TOKEN_ERROR_NOT_EXIST', -2);	//token不存在
define('VERIFY_LOGIN_TOKEN_ERROR_EXPIRED', -3);		//token已过期
define('VERIFY-LOGIN_TOKEN_SUCCESS', 1);

define('ERROR_NO_PARAM', 99999);				//参数缺失
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_REGISTER_FAIL', 99997);			//注册失败
define('ERROR_LOGIN_FAIL', 99996);				//登录失败
define('ERROR_ACCOUNT_NOT_EXIST', 99995);		//帐号不存在
define('ERROR_ORDER_ALREADY_COMPLETED', 99994);	//订单已经完成了
define('ERROR_ACCOUNT_BANNED', 99993);			//帐号被封停
define('ERROR_ACCOUNT_DUPLICATED', 99992);		//帐号重复
define('ERROR_CHANGE_FAIL', 99991);				//修改密码失败
define('ERROR_PASSWORD_ERROR', 99990);			//密码错误
define('ERROR_DEMO_FAIL', 99989);				//试玩失败
define('ERROR_MODIFY_FAIL', 99988);				//修改帐号失败
define('ERROR_MODIFY_NOTHING', 99987);			//没有修改任何信息
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误
define('ERROR_CHECK_CODE', 99998);				//校验码错误


/* End of file constants.php */
/* Location: ./application/config/constants.php */