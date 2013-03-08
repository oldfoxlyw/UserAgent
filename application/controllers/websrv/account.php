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
			}
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
			}
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
		$version = $this->input->get_post('version', TRUE);
			
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
				'version'				=>	$version,
				'server_recommend'		=>	'1'
			);
			if(!empty($accountType)) {
				$parameter['partner'] = $accountType;
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
			}
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
	            	$nickName = substr($user->account_name, 0, 11);
	            	$accountId = $this->_registerAccountId($name, $guid, $gameId, $server_id, $section_id, $nickName);
	            	
					$jsonData = Array(
						'message'	=>	'ACCOUNT_DEMO_SUCCESS',
						'user'		=>	$user,
						'account_id'=>	$accountId,
						'nick_name'	=>	$nickName
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
		$accountId	=	$this->input->get_post('account_id', TRUE);
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$nickName	=	$this->input->get_post('nick_name', TRUE);
		$accountEmail=	$this->input->get_post('account_email', TRUE);
		$country	=	$this->input->get_post('account_country', TRUE);
		$question	=	$this->input->get_post('account_question', TRUE);
		$answer		=	$this->input->get_post('account_answer', TRUE);
		$gameId		=	$this->input->get_post('game_id', TRUE);
		
		if(!empty($accountId) && $gameId!=FALSE) {
			/*
			 * 检测参数合法性
			 */
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($accountId, $gameId);
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
					$needEncrypt = false;
					if(empty($pass)) {
						$pass = $account->account_pass;
					} else {
						$needEncrypt = true;
					}
					if($account != FALSE) {
						if(!$this->web_account->validate_duplicate($name, $pass, $gameId, $account->server_id, $account->server_section, $needEncrypt)) {
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
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_MODIFY_NICKNAME_FAIL',
						'account_guid'	=>	$guid,
						'account_name'	=>	$name
					);
					$this->logs->write($logParameter);
					exit();
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
	
	public function requestAccountId($format = 'json') {
		$accountGuid	=	$this->input->get_post('account_guid', TRUE);
		$nickName		=	$this->input->get_post('nick_name', TRUE);
		$gameId			=	$this->input->get_post('game_id', TRUE);
		$serverSection	=	$this->input->get_post('server_section', TRUE);
		$serverId		=	$this->input->get_post('server_id', TRUE);
		$autoCreate		=	$this->input->get_post('auto_create', TRUE);
		
		if($autoCreate === FALSE || ($autoCreate !== '0' && $autoCreate !== '1')) {
			$autoCreate = true;
		} else {
			$autoCreate = $autoCreate=='1' ? true : false;
		}
		$nickName = $nickName===FALSE ? '' : $nickName;
		
		$this->load->model('game_account');
		$this->load->model('game_product');
		
		if(!empty($accountGuid) &&
		$gameId!==FALSE &&
		$serverId!==FALSE &&
		$serverSection!==FALSE) {
			/*
			 * 检测参数合法性
			 */
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($accountGuid, $gameId, $serverSection, $serverId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
			if(!$this->param_check->check($check, $authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	$accountGuid,
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			
			if($this->game_product->get($gameId) != false) {
				$accountResult = $this->web_account->get($accountGuid);
				if($accountResult != false) {
					$parameter = array(
						'account_guid'				=>	$accountGuid,
						'game_id'					=>	$gameId,
						'account_server_id'			=>	$serverId,
						'account_server_section'	=>	$serverSection
					);
					$result = $this->game_account->getAllResult($parameter);
					if($result != FALSE) {
						$accountId = $result[0]->account_id;
					} else {
						if($autoCreate) {
							$accountId = $this->_registerAccountId($accountResult->account_name, $accountGuid, $gameId, $serverId, $serverSection, $nickName);
							if($accountId === FALSE) {
								$jsonData = Array(
									'message'	=>	'ACCOUNT_ERROR_NO_SECTION'
								);
								exit($this->return_format->format($jsonData, $format));
							}
						} else {
							$jsonData = Array(
								'message'	=>	'ACCOUNT_ERROR_NO_ID'
							);
							exit($this->return_format->format($jsonData, $format));
							
							$logParameter = array(
								'log_action'	=>	'ACCOUNT_ERROR_NO_ID',
								'account_guid'	=>	$accountGuid,
								'account_name'	=>	''
							);
							$this->logs->write($logParameter);
						}
					}
					$parameter = array(
						'account_active'		=>	1,
						'account_active_week'		=>	1,
						'account_active_month'		=>	1
					);
					$this->game_account->update($parameter, $accountId);
					
					$jsonData = Array(
						'message'	=>	'ACCOUNT_REQUEST_ID_SUCCESS',
						'account_id'=>	$accountId,
						'nick_name'	=>	$nickName
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REQUEST_ID_SUCCESS',
						'account_guid'	=>	$accountGuid,
						'account_name'	=>	$accountId
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'message'	=>	'ACCOUNT_ERROR_NO_GUID'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_ERROR_NO_GUID',
						'account_guid'	=>	$accountGuid,
						'account_name'	=>	''
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'message'	=>	'ACCOUNT_ERROR_NO_GAME'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_ERROR_NO_GAME',
					'account_guid'	=>	$accountGuid,
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
			
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	private function _registerAccountId($accountName, $accountGuid, $gameId, $serverId, $serverSection, $nickName = '') {
		if(!empty($accountGuid) &&
		$gameId!==FALSE &&
		$serverId!==FALSE &&
		$serverSection!==FALSE) {
			$this->load->model('game_account');
			$accountId = $this->_generateAccountId($gameId, $serverId, $serverSection);
			if($accountId === false) {
				return false;
			}
			$parameter = array(
				'account_id'			=>	$accountId,
				'account_name'		=>	$accountName,
				'account_guid'			=>	$accountGuid,
				'game_id'				=>	$gameId,
				'account_server_id'		=>	$serverId,
				'account_server_section'=>	$serverSection,
				'nick_name'				=>	$nickName
			);
			$this->game_account->insert($parameter);
			$this->load->model('websrv/account_added_game', 'account_game');
			$gameAdded = $this->account_game->get($accountGuid, $gameId);
			if($gameAdded===FALSE) {
				$parameter = array(
					'GUID'		=>	$accountGuid,
					'game_id'	=>	$gameId
				);
				$this->account_game->insert($parameter);
			}
			return $accountId;
		} else {
			return false;
		}
	}
	
	private function _generateAccountId($gameId, $serverId, $serverSection) {
		$this->load->model('websrv/account_count');
		$parameter = array(
			'game_id'				=>	$gameId,
			'account_server_id'		=>	$serverId,
			'account_server_section'=>	$serverSection
		);
		$nextAvailableId = $this->account_count->getNextAvailableId($parameter);
		if($nextAvailableId < 0) {
			return false;
		}
		/*
		$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$accountId = substr($code, $gameId - 1, 1);
		$accountId .= substr($code, $serverSection - 1, 1);
		$accountId .= substr($code, $serverId - 1, 1);
		*/
		$accountId = $gameId . $serverSection . $serverId;
		
		$containCount = $this->config->item('contain_id_count');
		$accountId .= sprintf('%0' . $containCount . 'd', $nextAvailableId);
		return $accountId;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
