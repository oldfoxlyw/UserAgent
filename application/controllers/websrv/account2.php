<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account2 extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('web_account');
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
			$user = $this->web_account->validate($accountName, $accountPass, $server_id);
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

				$db = $this->web_account->db();
				$time = time();
				$sql = "update `web_account` set `account_lastlogin`={$user->account_currentlogin}, `account_currentlogin`={$time}, `account_activity`=`account_activity`+1 where `GUID`='{$user->GUID}'";
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
	
	public function register($format = 'json')
	{
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$accountEmail=	$this->input->get_post('account_email', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);
		$question	=	$this->input->get_post('account_question', TRUE);
		$answer		=	$this->input->get_post('account_answer', TRUE);
		
		$accountEmail = $accountEmail===FALSE ? '' : $accountEmail;
		$country = $country===FALSE ? '' : $country;
		$question = $question===FALSE ? '' : $question;
		$answer = $answer===FALSE ? '' : $answer;
		
		if(!empty($name) && !empty($pass) && !empty($server_id))
		{
			$forbiddenWords = $this->config->item('forbidden_words');
			if(in_array($name, $forbiddenWords))
			{
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_REGISTER_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_REGISTER_FAIL_FORBIDDEN',
					'account_guid'	=>	'',
					'account_name'	=>	$name,
					'server_id'		=>	$server_id
				);
				$this->logs->write($logParameter);
			}

			if($this->web_account->validate_duplicate($name, $pass, $server_id)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	$accountEmail,
					'question'	=>	$question,
					'answer'	=>	$answer,
					'server_id'	=>	$server_id
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					$user = $this->web_account->get($guid);
	            	unset($user->account_pass);
	            	unset($user->account_secret_key);
        			$user->guid_code = md5(sha1($user->GUID));
					$jsonData = Array(
						'success'	=>	true,
						'message'	=>	'ACCOUNT_REGISTER_SUCCESS',
						'user'		=>	$user
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'account_name'	=>	$user->account_name,
						'server_id'		=>	$server_id
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'success'	=>	false,
						'errors'	=>	'ACCOUNT_REGISTER_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_FAIL',
						'account_guid'	=>	'',
						'account_name'	=>	$name,
						'server_id'		=>	$server_id
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_REGISTER_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'account_name'	=>	$name,
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
				'log_action'	=>	'ACCOUNT_REGISTER_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function check_duplicated($format = 'json') {
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);

		if(!empty($name) && !empty($pass) && !empty($server_id)) {

			if($this->web_account->validate_duplicate($name, $pass, $server_id)) {
				$jsonData = Array(
					'success'	=>	true,
					'message'	=>	'ACCOUNT_CHECK_SUCCESS'
				);
				echo $this->return_format->format($jsonData, $format);
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'success'	=>	false,
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function change_password($format = 'json') {
		$accountName  = $this->input->get_post('account_name', TRUE);
		$originPassword	=	$this->input->get_post('origin_pass', TRUE);
		$newPassword	=	$this->input->get_post('new_pass', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);
		
		if(!empty($accountName) && !empty($originPassword) && !empty($newPassword) && !empty($server_id))
		{
			$userPass = $this->web_account->encrypt_pass($originPassword);
			$parameter = array(
				'account_name'		=>	$accountName,
				'account_pass'		=>	$userPass,
				'server_id'			=>	$server_id
			);
			$result = $this->web_account->getAllResult($parameter);
			if($result[0] != FALSE) {
				$guid = $result[0]->GUID;
				if($result[0]->account_pass == $userPass) {
					if($this->web_account->validate_duplicate($result[0]->account_name, $newPassword, $result[0]->server_id)) {
						$newPass = $this->web_account->encrypt_pass($newPassword);
						$parameter = array(
							'account_pass'	=>	$newPass
						);
						if($this->web_account->update($parameter, $guid)) {
							$jsonData = Array(
								'success'	=>	true,
								'message'	=>	'ACCOUNT_PASSWORD_CHANGE_SUCCESS'
							);
							echo $this->return_format->format($jsonData, $format);
			
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_PASSWORD_CHANGE_SUCCESS',
								'account_guid'	=>	$guid,
								'account_name'	=>	$result[0]->account_name,
								'server_id'		=>	$server_id
							);
							$this->logs->write($logParameter);
						} else {
							$jsonData = Array(
								'success'	=>	false,
								'errors'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL'
							);
							echo $this->return_format->format($jsonData, $format);
			
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL',
								'account_guid'	=>	$guid,
								'account_name'	=>	$result[0]->account_name,
								'server_id'		=>	$server_id
							);
							$this->logs->write($logParameter);
						}
					} else {
						$jsonData = Array(
							'success'	=>	false,
							'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
						);
						echo $this->return_format->format($jsonData, $format);
					}
				} else {
					$jsonData = Array(
						'success'	=>	false,
						'errors'	=>	'ACCOUNT_ERROR_PASSWORD'
					);
					echo $this->return_format->format($jsonData, $format);
				}
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_ERROR_NO_GUID'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'success'	=>	false,
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
			
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_PASSWORD_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function demo($format = 'json') {
		$partner = $this->input->get_post('partner', TRUE);

		if(empty($partner))
		{
			$partner = 'empty';
		}
			
		$this->load->library('guid');
		$this->load->helper('security');
		$guid = do_hash($this->guid->toString(), 'md5');
		$name = 'Guest' . $guid;
		$pass = do_hash($guid, 'md5');
		
		$this->load->model('websrv/server', 'server');
		$parameter = array(
			'partner'				=>	$partner
			'server_recommend'		=>	'1'
		);
		$result = $this->server->getAllResult($parameter);
		if($result!=FALSE) {
			$server_id = $result[0]->account_server_id;
		} else {
			$parameter = array(
				'partner'				=>	$partner
				'order_by'				=>	'server_sort'
			);
			$result = $this->server->getAllResult($parameter);
			$server_id = $result[0]->account_server_id;
		}
			
		if(!empty($name) && !empty($pass) && !empty($server_id))
		{
			if($this->web_account->validate_duplicate($name, $pass, $server_id)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	'',
					'server_id'	=>	$server_id
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					$user = $this->web_account->get($guid);
		            unset($user->account_secret_key);
	            	$user->account_pass = $pass;
            		$user->guid_code = md5(sha1($user->GUID));
	            	
					$jsonData = Array(
						'success'	=>	true,
						'message'	=>	'ACCOUNT_DEMO_SUCCESS',
						'user'		=>	$user,
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'account_name'	=>	$user->account_name,
						'server_id'		=>	$server_id
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'success'	=>	false,
						'errors'	=>	'ACCOUNT_DEMO_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_FAIL',
						'account_guid'	=>	'',
						'account_name'	=>	$name,
						'server_id'		=>	$server_id
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_DEMO_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'account_name'	=>	$name,
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
				'log_action'	=>	'ACCOUNT_DEMO_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function modify($format = 'json') {
		$accountId	=	$this->input->get_post('guid', TRUE);
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$nickName	=	$this->input->get_post('nick_name', TRUE);
		$accountEmail=	$this->input->get_post('account_email', TRUE);
		
		if(!empty($accountId))
		{
			//取得GUID
			$webAccount = $this->web_account->get($accountId);
			if($webAccount!=FALSE) {
				$guid = $webAccount->GUID;
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_ERROR_NOT_EXIST'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_MODIFY_ERROR_NOT_EXIST',
					'account_guid'	=>	$accountId,
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
				exit();
			}
			
			$row = array();
			if(!empty($name)) {
				$row['account_name'] = $name;
			}
			if(!empty($pass)) {
				$this->load->helper('security');
				$row['account_pass'] = $this->web_account->encrypt_pass($pass);
			}
			
			if(!empty($row)) {
				if(!empty($name) || !empty($pass)) {
					$name = empty($name) ? $webAccount->account_name : $name;
					$needEncrypt = false;
					if(empty($pass)) {
						$pass = $webAccount->account_pass;
					} else {
						$needEncrypt = true;
					}
					if(!$this->web_account->validate_duplicate($name, $pass, $needEncrypt)) {
						$jsonData = Array(
							'success'	=>	false,
							'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
						);
						echo $this->return_format->format($jsonData, $format);
						
						$logParameter = array(
							'log_action'	=>	'ACCOUNT_MODIFY_FAIL_DUPLICATE',
							'account_guid'	=>	$guid,
							'account_name'	=>	$name
						);
						$this->logs->write($logParameter);
						exit();
					}
				}
				if($this->web_account->update($row, $guid)) {
					$jsonData = Array(
						'success'	=>	true,
						'message'	=>	'ACCOUNT_MODIFY_SUCCESS'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_MODIFY_SUCCESS',
						'account_guid'	=>	$guid,
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'success'	=>	false,
						'errors'	=>	'ACCOUNT_MODIFY_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_MODIFY_FAIL',
						'account_guid'	=>	$guid,
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'success'	=>	false,
					'errors'	=>	'ACCOUNT_MODIFY_NOTHING'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_MODIFY_NOTHING',
					'account_guid'	=>	$guid,
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'success'	=>	false,
				'errors'	=>	'ACCOUNT_MODIFY_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_MODIFY_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function checkGuid($format = 'json')
	{
		$serverId		=	$this->input->get_post('server_id', TRUE);
		$guid 			=	$this->input->get_post('guid', TRUE);
	
		if(!empty($guid))
		{
				
			$result = $this->web_account->get($guid);
            $result->guid_code = md5(sha1($result->GUID));
			if(!empty($result))
			{
				$jsonData = Array(
						'success'	=>	true,
						'message'	=>	'ACCOUNT_GUID_SUCCESS',
						'user'		=>	$result
				);
				exit($this->return_format->format($jsonData, $format));
			}
			else
			{
				$jsonData = Array(
						'success'	=>	false,
						'message'	=>	'ACCOUNT_GUID_ERROR',
						'user'		=>	null
				);
				exit($this->return_format->format($jsonData, $format));
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
