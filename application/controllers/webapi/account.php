<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	private $gameId = null;
	private $sectionId = null;
	private $authToken = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('web_account');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
		$this->authKey = $this->config->item('game_auth_key');
		$this->gameId = $this->config->item('battlenet_id');
		$this->sectionId = $this->config->item('game_section_id');
		$this->authToken = $this->authKey[$this->gameId]['auth_key'];
	}
	
	public function login($format = 'json') {
		$accountName  = $this->input->get_post('account_name', TRUE);
		$accountPass  = $this->input->get_post('account_pass', TRUE);
		$redirect	= $this->input->get_post('redirect', TRUE);
		
		if(!empty($accountName) && !empty($accountPass) &&
		$this->gameId!==FALSE &&
		$this->sectionId!==FALSE) {
			/*
			 * 检测参数合法性
			 */
			$check = array($accountName, $accountPass);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	'',
					'account_name'	=>	$accountName
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			
			$user = $this->web_account->validate($accountName, $accountPass, $this->gameId, '', $this->sectionId);
			if($user != FALSE) {
				$cookieString = json_encode($user);
            	$this->load->helper('cookie');
	            $cookie = array(
					'name'		=> 'user',
					'value'		=> $cookieString,
					'expire'	=> $this->config->item('cookie_expire'),
					'domain'	=> $this->config->item('cookie_domain'),
					'path'		=> $this->config->item('cookie_path'),
					'prefix'	=> $this->config->item('cookie_prefix')
	            );
	            $this->input->set_cookie($cookie);
	            
	            $parameter = array(
	            	'account_lastlogin'		=>	$user->account_currentlogin,
	            	'account_currentlogin'	=>	time(),
	            	'account_lastip'		=>	$user->account_currentip,
	            	'account_currentip'		=>	$this->input->ip_address()
	            );
	            $this->web_account->update($parameter, $user->GUID);
	            
	            if(!empty($redirect)) {
	            	redirect($redirect);
	            } else {
	            	unset($user->account_pass);
	            	unset($user->account_secret_key);
	            	unset($user->account_pass_answer);
	            	unset($user->account_pass_question);
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
					'message'	=>	'ACCOUNT_VALIDATE_FAIL'
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
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_LOGIN_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function validate($format = 'json') {
		$this->load->helper('cookie');
		if(!$this->input->cookie('agent_user', TRUE)) {
			exit('-1');
		} else {
			exit($this->input->cookie('agent_user', TRUE));
		}
	}
	
	public function register($format = 'json') {
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$accountEmail=	$this->input->get_post('account_email', TRUE);
		$country	=	$this->input->get_post('account_country', TRUE);
		$firstname	=	$this->input->get_post('account_firstname', TRUE);
		$lastname	=	$this->input->get_post('account_lastname', TRUE);
		$question	=	$this->input->get_post('account_question', TRUE);
		$answer		=	$this->input->get_post('account_answer', TRUE);
		$redirect	=	$this->input->get_post('redirect', TRUE);
		
		$accountEmail = $accountEmail===FALSE ? '' : $accountEmail;
		$country = $country===FALSE ? '' : $country;
		$question = $question===FALSE ? '' : $question;
		$answer = $answer===FALSE ? '' : $answer;
		
		if(!empty($name) && !empty($pass) &&
		!empty($this->gameId) &&
		!empty($this->sectionId)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($name, $pass, $accountEmail, $country, $firstname, $lastname, $question, $answer);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	'',
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			
			if($this->web_account->validate_duplicate($name, $pass, $this->gameId, '', $this->sectionId)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	$accountEmail,
					'country'	=>	$country,
					'firstname'	=>	$firstname,
					'lastname'	=>	$lastname,
					'question'	=>	$question,
					'answer'	=>	$answer,
					'game_id'	=>	$this->gameId,
					'server_id'	=>	0,
					'server_section'=>	$this->sectionId
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
						'message'	=>	'ACCOUNT_REGISTER_FAIL'
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
					'message'	=>	'ACCOUNT_ERROR_DUPLICATE'
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
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
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
	
	public function modify($format = 'json') {
		$accountId	=	$this->input->get_post('account_id', TRUE);
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$nickName	=	$this->input->get_post('nick_name', TRUE);
		$accountEmail=	$this->input->get_post('account_email', TRUE);
		$country	=	$this->input->get_post('account_country', TRUE);
		$question	=	$this->input->get_post('account_question', TRUE);
		$answer		=	$this->input->get_post('account_answer', TRUE);
		
		if(!empty($accountId) && !empty($this->gameId)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($accountId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	'',
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			//取得GUID
			$this->load->model('game_account');
			$gameAccount = $this->game_account->get($accountId);
			if($gameAccount!=FALSE) {
				$guid = $gameAccount->account_guid;
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_ERROR_NOT_EXIST'
				);
				exit($this->return_format->format($jsonData, $format));
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_MODIFY_ERROR_NOT_EXIST',
					'account_guid'	=>	$accountId,
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
			}
			
			$row = array();
			if(!empty($name)) {
				$row['account_name'] = $name;
			}
			if(!empty($pass)) {
				$this->load->helper('security');
				$row['account_pass'] = strtoupper(do_hash(do_hash($pass, 'md5') . do_hash($pass), 'md5'));
			}
			if(!empty($accountEmail)) {
				$row['account_email'] = $accountEmail;
			}
			if(!empty($country)) {
				$row['account_country'] = $country;
			}
			if(!empty($question)) {
				$row['account_pass_question'] = $question;
			}
			if(!empty($answer)) {
				$row['account_pass_answer'] = $answer;
			}
			
			if(!empty($row)) {
				if(!empty($name) || !empty($pass)) {
					$account = $this->web_account->get($guid);
					$name = empty($name) ? $account->account_name : $name;
					$pass = empty($pass) ? $account->account_pass : $pass;
					if($account != FALSE) {
						if(!$this->web_account->validate_duplicate($name, $pass, $this->gameId, $account->server_section, false)) {
							$jsonData = Array(
								'message'	=>	'ACCOUNT_ERROR_DUPLICATE'
							);
							exit($this->return_format->format($jsonData, $format));
							
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_MODIFY_FAIL_DUPLICATE',
								'account_guid'	=>	$guid,
								'account_name'	=>	$name
							);
							$this->logs->write($logParameter);
						}
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
						'message'	=>	'ACCOUNT_MODIFY_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_MODIFY_FAIL',
						'account_guid'	=>	$guid,
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
				}
			} elseif(!empty($nickName)) {
				$parameter = array(
					'nick_name'		=>	$nickName
				);
				if($this->game_account->update($parameter, $accountId)) {
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
						'message'	=>	'ACCOUNT_MODIFY_NICKNAME_FAIL'
					);
					exit($this->return_format->format($jsonData, $format));
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_MODIFY_NICKNAME_FAIL',
						'account_guid'	=>	$guid,
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_MODIFY_NOTHING'
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
				'message'	=>	'ACCOUNT_MODIFY_ERROR_NO_PARAM'
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
	
	public function games($format = 'json') {
		$guid  = $this->input->get_post('guid', TRUE);
		
		if(!empty($guid)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($guid);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	$guid,
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			$this->load->model('webapi/game_list', 'game_list');
			$result = $this->game_list->get($guid);
			if($result !== FALSE) {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_GAMELIST_SUCCESS',
					'result'	=>	$result
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'ACCOUNT_GAMELIST_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_GAMELIST_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function change_password($format = 'json') {
		$guid			=	$this->input->post('guid', TRUE);
		$originPassword	=	$this->input->post('origin_pass', TRUE);
		$newPassword	=	$this->input->post('new_pass', TRUE);
		
		if(!empty($guid) && !empty($originPassword) && !empty($newPassword)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($guid, $originPassword, $newPassword);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	$guid,
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			
			$result = $this->web_account->get($guid);
			if($result != FALSE) {
				$userPass = $this->web_account->encrypt_pass($originPassword);
				if($result->account_pass == $userPass) {
					if($this->web_account->validate_duplicate($result->account_name, $newPassword, $result->game_id, '', $result->server_section)) {
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
								'account_name'	=>	$result->account_name
							);
							$this->logs->write($logParameter);
						} else {
							$jsonData = Array(
								'message'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL'
							);
							echo $this->return_format->format($jsonData, $format);
			
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL',
								'account_guid'	=>	$guid,
								'account_name'	=>	$result->account_name
							);
							$this->logs->write($logParameter);
						}
					} else {
						$jsonData = Array(
							'message'	=>	'ACCOUNT_ERROR_DUPLICATE'
						);
						echo $this->return_format->format($jsonData, $format);
					}
				} else {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_ERROR_PASSWORD'
					);
					echo $this->return_format->format($jsonData, $format);
				}
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_ERROR_NO_GUID'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
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
	
	public function change_profile($format = 'json') {
		$guid			=	$this->input->post('guid', TRUE);
		$accountEmail	=	$this->input->post('account_email', TRUE);
		$firstName		=	$this->input->post('first_name', TRUE);
		$lastName		=	$this->input->post('last_name', TRUE);
		$account_country=	$this->input->post('account_country', TRUE);
		$account_question=	$this->input->post('account_question', TRUE);
		$account_answer	=	$this->input->post('account_answer', TRUE);
		
		$accountEmail = $accountEmail===FALSE ? '' : $accountEmail;
		$firstName = $firstName===FALSE ? '' : $firstName;
		$lastName = $lastName===FALSE ? '' : $lastName;
		$account_country = $account_country===FALSE ? '' : $account_country;
		$account_question = empty($account_question) ? '' : $account_question;
		$account_answer = empty($account_answer) ? '' : $account_answer;
		
		if(!empty($guid)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($guid, $accountEmail, $firstName, $lastName, $account_country, $account_question, $account_answer);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	$guid,
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			$parameter = array();
			if(!empty($accountEmail)) {
				$parameter['account_email'] = $accountEmail;
			}
			if(!empty($firstName)) {
				$parameter['account_firstname'] = $firstName;
			}
			if(!empty($lastName)) {
				$parameter['account_lastname'] = $lastName;
			}
			if(!empty($account_country)) {
				$parameter['account_country'] = $account_country;
			}
			if(!empty($account_question)) {
				$parameter['account_pass_question'] = $account_question;
			}
			if(!empty($account_answer)) {
				$parameter['account_pass_answer'] = $account_answer;
			}
			if(!empty($parameter)) {
				if($this->web_account->update($parameter, $guid)) {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_PROFILE_CHANGE_SUCCESS'
					);
					echo $this->return_format->format($jsonData, $format);
	
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_PROFILE_CHANGE_SUCCESS',
						'account_guid'	=>	$guid,
						'account_name'	=>	''
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_PROFILE_CHANGE_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
	
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_PROFILE_CHANGE_FAIL',
						'account_guid'	=>	$guid,
						'account_name'	=>	''
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_PROFILE_CHANGE_NONE'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
			
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_PROFILE_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}

	public function test()
	{
		$this->load->helper('string');
		$this->load->model('maccount');
		$parameter = array(
			'GUID'	=>	200100191000003
		);
		$result = $this->maccount->read($parameter);
		$result = $result[0];

		echo utf8_unicode($result->account_nickname);
		// echo $result->account_nickname;
		// echo '<Br>';
		// echo trim($result->account_nickname);
		// echo '<br>';
		// echo base64_encode($result->account_nickname);
		// echo '<br>';
		// echo base64_encode(trim($result->account_nickname));
	}

	/*
	爱立德专用帐号验证接口
	*/
	public function check($format = 'json')
	{
		$this->load->model('logs');
		$server_id = $this->input->get_post('serv_id', TRUE);
		$nickname = trim($this->input->get_post('usr_id', TRUE));

		if(!empty($server_id) && !empty($nickname))
		{
			$this->load->model('maccount');

			$parameter = array(
				'server_id'			=>	$server_id,
				// 'account_nickname'	=>	$nickname . ' '
				'account_nickname'	=>	$nickname
			);
			$result = $this->maccount->read($parameter);

			if(!empty($result))
			{
				$result = $result[0];

				$jsonData = array(
					'err_code'			=>	0,
					'player_id'			=>	$result->GUID,
					'usr_name'			=>	$nickname,
					'usr_rank'			=>	$result->account_level
				);

				$log = array(
					'log_action'		=>	'OFFLINE_RECHARGE_ACCOUNT_SUCCESS',
					'account_guid'		=>	$result->GUID,
					'account_name'		=>	$nickname,
					'server_id'			=>	$server_id
				);
				$this->logs->write_api($log);
			}
			else
			{
				$jsonData = array(
					'err_code'			=>	1,
					'desc'				=>	'Account not exist'
				);

				$log = array(
					'log_action'		=>	'OFFLINE_RECHARGE_ERROR_NOT_EXIST',
					'account_guid'		=>	0,
					'account_name'		=>	$nickname,
					'server_id'			=>	$server_id
				);
				$this->logs->write_api($log);
			}
		}
		else
		{
			$jsonData = array(
				'err_code'			=>	0,
				'desc'				=>	'invalid params'
			);
		}

		echo $this->return_format->format($jsonData, $format);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */