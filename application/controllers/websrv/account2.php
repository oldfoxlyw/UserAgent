<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account2 extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('web_account2');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
		$this->authKey = $this->config->item('game_auth_key');
	}
	
	public function login($format = 'json') {
		$accountName	=	$this->input->get_post('account_name', TRUE);
		$accountPass	=	$this->input->get_post('account_pass', TRUE);
		$server_id		=	$this->input->get_post('server_id', TRUE);
		
		if(!empty($accountName) && !empty($accountPass) && !empty($server_id))
		{
			$user = $this->web_account2->validate($accountName, $accountPass, $server_id);
			if($user != FALSE) {
            	unset($user->account_pass);
            	unset($user->account_secret_key);
				if($user->account_status == '0') {
					$jsonData = Array(
						'success'	=>	false,
						'errors'	=>	'ACCOUNT_VALIDATE_FAIL_FREEZED',
						'user'		=>	$user
					);
					exit($this->return_format->format($jsonData, $format));
				} elseif ($user->account_status == '-1') {
					$jsonData = Array(
						'success'	=>	false,
						'errors'	=>	'ACCOUNT_VALIDATE_FAIL_BANNED',
						'user'		=>	$user
					);
					exit($this->return_format->format($jsonData, $format));
				}

				$db = $this->web_account2->db();
				$time = time();
				$sql = "update `web_account2` set `account_lastlogin`={$user->account_currentlogin}, `account_currentlogin`={$time}, `account_activity`=`account_activity`+1 where `GUID`='{$user->GUID}'";
				$db->query($sql);
				$jsonData = Array(
					'success'	=>	true,
					'message'	=>	'ACCOUNT_VALIDATE_SUCCESS',
					'user'		=>	$user
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_LOGIN_SUCCESS',
					'account_guid'	=>	$user->GUID,
					'account_name'	=>	$user->account_name,
					'server_id'		=>	$server_id
				);
				$this->logs->write($logParameter);
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_VALIDATE_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
					
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_LOGIN_FAIL',
					'account_guid'	=>	'',
					'account_name'	=>	$accountName,
					'server_id'		=>	$server_id
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'success'	=>	false,
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'errors'	=>	'ACCOUNT_LOGIN_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>