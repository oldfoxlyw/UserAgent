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
		$accountName  = $this->input->get_post('account_name', TRUE);
		$accountPass  = $this->input->get_post('account_pass', TRUE);
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$section_id	=	$this->input->get_post('server_section', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);
		$redirect	= $this->input->get_post('redirect', TRUE);
		
		if(!empty($accountName) && !empty($accountPass) &&
		$gameId!==FALSE &&
		//$server_id!==FALSE &&
		$section_id!==FALSE) {
			/*
			 * 检测参数合法性
			 */
/*
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($accountName, $accountPass, $gameId, $section_id, $server_id);
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
					'account_name'	=>	$accountName
				);
				$this->logs->write($logParameter);
				exit();
			}*/
			/*
			 * 检查完毕
			 */
			
			$user = $this->web_account->validate($accountName, $accountPass, $gameId, $server_id, $section_id);
			if($user != FALSE) {
            	unset($user->account_pass);
            	unset($user->account_secret_key);
				if($user->account_status == '0') {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_VALIDATE_FAIL_FREEZED',
						'user'		=>	$user
					);
					exit($this->return_format->format($jsonData, $format));
				} elseif ($user->account_status == '-1') {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_VALIDATE_FAIL_BANNED',
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
						'account_name'	=>	$user->account_name,
						'game_id'			=>	$gameId,
						'section_id'			=>	$section_id,
						'server_id'			=>	$server_id
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
					'account_name'	=>	$accountName,
					'game_id'			=>	$gameId,
					'section_id'			=>	$section_id,
					'server_id'			=>	$server_id
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
	
	public function register($format = 'json') {
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$accountEmail=	$this->input->get_post('account_email', TRUE);
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$section_id	=	$this->input->get_post('server_section', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);
		$country	=	$this->input->get_post('account_country', TRUE);
		$question	=	$this->input->get_post('account_question', TRUE);
		$answer		=	$this->input->get_post('account_answer', TRUE);
		$redirect	=	$this->input->get_post('redirect', TRUE);
		
		$accountEmail = $accountEmail===FALSE ? '' : $accountEmail;
		$country = $country===FALSE ? '' : $country;
		$question = $question===FALSE ? '' : $question;
		$answer = $answer===FALSE ? '' : $answer;
		
		if(!empty($name) && !empty($pass) &&
		$gameId!==FALSE &&
		$server_id!==FALSE &&
		$section_id!==FALSE) {
			/*
			 * 检测参数合法性
			 */
/*
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($name, $pass, $gameId, $section_id, $server_id);
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
					'account_name'	=>	$name
				);
				$this->logs->write($logParameter);
				exit();
			}*/
			/*
			 * 检查完毕
			 */
			$forbiddenWords = $this->config->item('forbidden_words');
			if(in_array($name, $forbiddenWords)) {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_REGISTER_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_REGISTER_FAIL_FORBIDDEN',
					'account_guid'	=>	'',
					'account_name'	=>	$name,
					'game_id'			=>	$gameId,
					'section_id'			=>	$section_id,
					'server_id'			=>	$server_id
				);
				$this->logs->write($logParameter);
			}

			if($this->web_account->validate_duplicate($name, $pass, $gameId, $server_id, $section_id)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	$accountEmail,
					'country'	=>	$country,
					'question'	=>	$question,
					'answer'	=>	$answer,
					'game_id'	=>	$gameId,
					'server_id'	=>	$server_id,
					'server_section'=>	$section_id
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
							'account_name'	=>	$user->account_name,
							'game_id'			=>	$gameId,
							'section_id'			=>	$section_id,
							'server_id'			=>	$server_id
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
						'account_name'	=>	$name,
						'game_id'			=>	$gameId,
						'section_id'			=>	$section_id,
						'server_id'			=>	$server_id
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
					'account_name'	=>	$name,
					'game_id'			=>	$gameId,
					'section_id'			=>	$section_id,
					'server_id'			=>	$server_id
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
	
	public function check_duplicated($format = 'json') {
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$section_id	=	$this->input->get_post('server_section', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);

		if(!empty($name) && !empty($pass) &&
				$gameId!==FALSE &&
				$server_id!==FALSE &&
				$section_id!==FALSE) {

			if($this->web_account->validate_duplicate($name, $pass, $gameId, $server_id, $section_id)) {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_CHECK_SUCCESS'
				);
				echo $this->return_format->format($jsonData, $format);
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function change_password($format = 'json') {
		$accountName  = $this->input->get_post('account_name', TRUE);
		$originPassword	=	$this->input->get_post('origin_pass', TRUE);
		$newPassword	=	$this->input->get_post('new_pass', TRUE);
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$section_id	=	$this->input->get_post('server_section', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);
		
		if(!empty($accountName) && !empty($originPassword) && !empty($newPassword) && !empty($gameId) && !empty($section_id)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($accountName, $originPassword, $newPassword, $gameId, $section_id, $server_id);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
/*
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
*/
			/*
			 * 检查完毕
			 */
			$userPass = $this->web_account->encrypt_pass($originPassword);
			$parameter = array(
				'account_name'		=>	$accountName,
				'account_pass'		=>	$userPass,
				'game_id'		=>	$gameId,
				'server_section'		=>	$section_id,
				'server_id'		=>	$server_id
			);
			$result = $this->web_account->getAllResult($parameter);
			if($result[0] != FALSE) {
				$guid = $result[0]->GUID;
				if($result[0]->account_pass == $userPass) {
					if($this->web_account->validate_duplicate($result[0]->account_name, $newPassword, $result[0]->game_id, $result[0]->server_id, $result[0]->server_section)) {
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
								'message'	=>	'ACCOUNT_PASSWORD_CHANGE_FAIL'
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
	
	public function demo($format = 'json') {
		$gameId		=	$this->input->get_post('game_id', TRUE);
		//$section_id	=	$this->input->get_post('server_section', TRUE);
		//$server_id	=	$this->input->get_post('server_id', TRUE);
		$accountType	=	$this->input->get_post('account_type', TRUE);
			
		$this->load->library('guid');
		$this->load->helper('security');
		$guid = do_hash($this->guid->toString(), 'md5');
		$name = 'Guest' . $guid;
		$pass = do_hash($guid, 'md5');
		
		//if($section_id === FALSE) {
			//$section_id = $this->config->item('game_section_id');
		//}
		//if($server_id === FALSE) {
			$this->load->model('websrv/server', 'server');
			$parameter = array(
				'game_id'				=>	$gameId,
				'server_recommend'		=>	'1'
			);
			if(!empty($accountType)) {
				$parameter['server_mode'] = 'partner';
			}
			$result = $this->server->getAllResult($parameter);
			if($result!=FALSE) {
				$section_id = $result[0]->account_server_section;
				$server_id = $result[0]->account_server_id;
			} else {
				$parameter = array(
					'game_id'				=>	$gameId,
					'order_by'				=>	'account_count'
				);
				$result = $this->server->getAllResult($parameter);
				$section_id = $result[0]->account_server_section;
				$server_id = $result[0]->account_server_id;
			}
		//}
			
		if(!empty($name) && !empty($pass) &&
		$gameId!==FALSE &&
		!empty($server_id) &&
		!empty($section_id)) {
			/*
			 * 检测参数合法性
			 */
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($gameId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
/*
			if(!$this->param_check->check($check, $authToken)) {
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
			}*/
			/*
			 * 检查完毕
			 */
			if($this->web_account->validate_duplicate($name, $pass, $gameId, $server_id, $section_id)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	'',
					'game_id'	=>	$gameId,
					'server_id'	=>	$server_id,
					'server_section'=>	$section_id
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
						'account_name'	=>	$user->account_name,
						'game_id'			=>	$gameId,
						'section_id'			=>	$section_id,
						'server_id'			=>	$server_id
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_DEMO_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_FAIL',
						'account_guid'	=>	'',
						'account_name'	=>	$name,
						'game_id'			=>	$gameId,
						'section_id'			=>	$section_id,
						'server_id'			=>	$server_id
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_DEMO_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'account_name'	=>	$name,
					'game_id'			=>	$gameId,
					'section_id'			=>	$section_id,
					'server_id'			=>	$server_id
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
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
		$gameId		=	$this->input->get_post('game_id', TRUE);
		
		if(!empty($accountId) && $gameId!=FALSE) {
			/*
			 * 检测参数合法性
			 */
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($accountId, $gameId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
/*
			if(!$this->param_check->check($check, $authToken)) {
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
			}*/
			/*
			 * 检查完毕
			 */
			
			//取得GUID
			$webAccount = $this->web_account->get($accountId);
			if($webAccount!=FALSE) {
				$guid = $webAccount->GUID;
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_ERROR_NOT_EXIST'
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
					if(!$this->web_account->validate_duplicate($name, $pass, $gameId, $webAccount->server_id, $webAccount->server_section, $needEncrypt)) {
						$jsonData = Array(
							'message'	=>	'ACCOUNT_ERROR_DUPLICATE'
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
	
	public function checkGuid($format = 'json')
	{
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$sectionId		=	$this->input->get_post('server_section', TRUE);
		$serverId		=	$this->input->get_post('server_id', TRUE);
		$guid 			=	$this->input->post('guid', TRUE);
	
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
				
			$result = $this->account->get($guid);
			if(!empty($result))
			{
				$jsonData = Array(
						'message'	=>	'ACCOUNT_GUID_OK',
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
