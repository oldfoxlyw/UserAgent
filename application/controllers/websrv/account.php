<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
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
		$requestData = json_decode(file_get_contents("php://input"));
		$accountName  = $requestData->account_name;
		$accountPass  = $requestData->account_pass;
		$redirect	= $this->input->get_post('redirect', TRUE);
		
		if(!empty($accountName) && !empty($accountPass)) {
			
			$user = $this->web_account->validate($accountName, $accountPass);
			if($user != FALSE) {
            	unset($user->account_pass);
            	unset($user->account_secret_key);
            	$user->guid_code = md5(sha1($user->GUID));
				if($user->account_status == '0') {
					$jsonData = Array(
						'errors'	=>	'ACCOUNT_VALIDATE_FAIL_FREEZED',
						'user'		=>	$user
					);
					exit($this->return_format->format($jsonData, $format));
				} elseif ($user->account_status == '-1') {
					$jsonData = Array(
						'errors'	=>	'ACCOUNT_VALIDATE_FAIL_BANNED',
						'user'		=>	$user
					);
					exit($this->return_format->format($jsonData, $format));
				}
	            
	            if(!empty($redirect)) {
	            	redirect($redirect);
	            } else {
	            	$db = $this->web_account->db();
	            	$time = time();
	            	$sql = "update `web_account` set `account_lastlogin`={$user->account_currentlogin}, `account_currentlogin`={$time}, `account_activity`=`account_activity`+1 where `GUID`='{$user->GUID}'";
	            	$db->query($sql);
					$jsonData = Array(
						'message'	=>	'ACCOUNT_VALIDATE_SUCCESS',
						'user'		=>	$user
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_LOGIN_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'account_name'	=>	$user->account_name
					);
					$this->logs->write($logParameter);
	            }
			} else {
				$jsonData = Array(
					'errors'	=>	'ACCOUNT_VALIDATE_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
					
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_LOGIN_FAIL',
					'account_guid'	=>	'',
					'account_name'	=>	$accountName
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
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
	
	public function register($format = 'json') {
		$requestData = json_decode(file_get_contents("php://input"));
		$name  = $requestData->account_name;
		$pass  = $requestData->account_pass;
		$accountEmail  = $requestData->account_email;
		$country  = $requestData->account_country;
		$question  = $requestData->account_question;
		$answer  = $requestData->account_answer;
		
		$redirect	=	$this->input->get_post('redirect', TRUE);
		
		$accountEmail = $accountEmail===FALSE ? '' : $accountEmail;
		$country = $country===FALSE ? '' : $country;
		$question = $question===FALSE ? '' : $question;
		$answer = $answer===FALSE ? '' : $answer;
		
		if(!empty($name) && !empty($pass)) {
			$forbiddenWords = $this->config->item('forbidden_words');
			if(in_array($name, $forbiddenWords)) {
				$jsonData = Array(
					'errors'	=>	'ACCOUNT_REGISTER_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_REGISTER_FAIL_FORBIDDEN',
					'account_guid'	=>	'',
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
			}

			if($this->web_account->validate_duplicate($name, $pass)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	$accountEmail,
					'country'	=>	$country,
					'question'	=>	$question,
					'answer'	=>	$answer
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					if(!empty($redirect)) {
						redirect($redirect);
					} else {
						$user = $this->web_account->get($guid);
		            	unset($user->account_pass);
		            	unset($user->account_secret_key);
						$jsonData = Array(
							'message'	=>	'ACCOUNT_REGISTER_SUCCESS',
							'user'		=>	$user
						);
						echo $this->return_format->format($jsonData, $format);
						
						$logParameter = array(
							'log_action'	=>	'ACCOUNT_REGISTER_SUCCESS',
							'account_guid'	=>	$user->GUID,
							'account_name'	=>	$user->account_name
						);
						$this->logs->write($logParameter);
					}
				} else {
					$jsonData = Array(
						'errors'	=>	'ACCOUNT_REGISTER_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_FAIL',
						'account_guid'	=>	'',
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_REGISTER_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
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
		$requestData = json_decode(file_get_contents("php://input"));
		$name  = $requestData->account_name;
		$pass  = $requestData->account_pass;

		if(!empty($name) && !empty($pass)) {

			if($this->web_account->validate_duplicate($name, $pass)) {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_CHECK_SUCCESS'
				);
				echo $this->return_format->format($jsonData, $format);
			} else {
				$jsonData = Array(
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function change_password($format = 'json') {
		$requestData = json_decode(file_get_contents("php://input"));
		$accountName  = $requestData->account_name;
		$originPassword  = $requestData->origin_pass;
		$newPassword  = $requestData->new_pass;
		
		if(!empty($accountName) && !empty($originPassword) && !empty($newPassword)) {
			$userPass = $this->web_account->encrypt_pass($originPassword);
			$parameter = array(
				'account_name'		=>	$accountName,
				'account_pass'		=>	$userPass
			);
			$result = $this->web_account->getAllResult($parameter);
			if($result[0] != FALSE) {
				$guid = $result[0]->GUID;
				if($result[0]->account_pass == $userPass) {
					if($this->web_account->validate_duplicate($result[0]->account_name, $newPassword)) {
						$newPass = $this->web_account->encrypt_pass($newPassword);
						$parameter = array(
							'account_pass'	=>	$newPass
						);
						if($this->web_account->update($parameter, $guid)) {
							$jsonData = Array(
								'message'	=>	'ACCOUNT_PASSWORD_CHANGE_SUCCESS'
							);
							echo $this->return_format->format($jsonData, $format);
			
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_PASSWORD_CHANGE_SUCCESS',
								'account_guid'	=>	$guid,
								'account_name'	=>	$result[0]->account_name
							);
							$this->logs->write($logParameter);
						} else {
							$jsonData = Array(
								'errors'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL'
							);
							echo $this->return_format->format($jsonData, $format);
			
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL',
								'account_guid'	=>	$guid,
								'account_name'	=>	$result[0]->account_name
							);
							$this->logs->write($logParameter);
						}
					} else {
						$jsonData = Array(
							'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
						);
						echo $this->return_format->format($jsonData, $format);
					}
				} else {
					$jsonData = Array(
						'errors'	=>	'ACCOUNT_ERROR_PASSWORD'
					);
					echo $this->return_format->format($jsonData, $format);
				}
			} else {
				$jsonData = Array(
					'errors'	=>	'ACCOUNT_ERROR_NO_GUID'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
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
		$requestData = json_decode(file_get_contents("php://input"));
		$accountType  = $requestData->account_type;
			
		$this->load->library('guid');
		$this->load->helper('security');
		$guid = do_hash($this->guid->toString(), 'md5');
		$name = 'Guest' . $guid;
		$pass = do_hash($guid, 'md5');
			
		if(!empty($name) && !empty($pass)) {
			if($this->web_account->validate_duplicate($name, $pass)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	''
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					$user = $this->web_account->get($guid);
		            unset($user->account_secret_key);
	            	$user->account_pass = $pass;
	            	
					$jsonData = Array(
						'message'	=>	'ACCOUNT_DEMO_SUCCESS',
						'user'		=>	$user
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'account_name'	=>	$user->account_name
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'errors'	=>	'ACCOUNT_DEMO_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_FAIL',
						'account_guid'	=>	'',
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_DEMO_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
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
		$requestData = json_decode(file_get_contents("php://input"));
		$accountId  = $requestData->guid;
		$name  = $requestData->account_name;
		$pass  = $requestData->account_pass;
		
		if(!empty($accountId)) {
			//取得GUID
			$webAccount = $this->web_account->get($accountId);
			if($webAccount!=FALSE) {
				$guid = $webAccount->GUID;
			} else {
				$jsonData = Array(
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
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$sectionId		=	$this->input->get_post('server_section', TRUE);
		$serverId		=	$this->input->get_post('server_id', TRUE);
		$guid 			=	$this->input->get_post('guid', TRUE);
	
		if(!empty($gameId) && !empty($guid))
		{
			/*
			 * 检测参数合法性
			*/
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($guid, $gameId, $sectionId, $serverId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
			if(!$this->param_check->check($check, $authToken)) {
				$jsonData = Array(
						'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
						'log_action'	=>	'PARAM_INVALID',
						'account_guid'	=>	'',
						'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			*/
				
			$result = $this->web_account->get($guid);
			if(!empty($result))
			{
				$jsonData = Array(
						'message'	=>	'ACCOUNT_GUID_SUCCESS',
						'user'		=>	$result
				);
				exit($this->return_format->format($jsonData, $format));
			}
			else
			{
				$jsonData = Array(
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
