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

	public function combine_login($format = 'json')
	{
		$accountName	=	$this->input->get_post('account_name', TRUE);
		$accountPass	=	$this->input->get_post('account_pass', TRUE);
		$server_id		=	$this->input->get_post('server_id', TRUE);
		$device_id		=	$this->input->get_post('device_id', TRUE);
		$partner		=	$this->input->get_post('partner', TRUE);

		$device_id = empty($device_id) ? '' : $device_id;
		$partner = empty($partner) ? '' : $partner;

		if(!empty($accountName) && !empty($accountPass) && !empty($server_id))
		{
			$user = $this->web_account->validate($accountName, $accountPass, $server_id);
			if($user != FALSE)
			{
				unset($user->account_pass);
				unset($user->account_secret_key);

				$db = $this->web_account->db();
				$time = time();
				
				if ($user->account_status == '-1') {
					if($user->closure_endtime <= $time)
					{
						$sql = "update `web_account` set `account_status`=1, `closure_endtime`=0 where `GUID`='{$user->GUID}'";
						$db->query($sql);
					}
					else
					{
						$jsonData = Array(
							'success'	=>	0,
							'error_code'=>	ERROR_ACCOUNT_BANNED,
							'errors'	=>	'ACCOUNT_VALIDATE_FAIL_BANNED',
							'endtime'	=>	$user->closure_endtime
						);
						exit($this->return_format->format($jsonData, $format));
					}
				}

				$sql = "update `web_account` set `account_lastlogin`={$time}, `account_activity`=`account_activity`+1 where `GUID`='{$user->GUID}'";
				$db->query($sql);
				$jsonData = Array(
					'success'	=>	1,
					'error_code'=>	0,
					'message'	=>	'ACCOUNT_VALIDATE_SUCCESS',
					'user'		=>	$user
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_LOGIN_SUCCESS',
					'account_guid'	=>	$user->GUID,
					'device_id'		=>	empty($device_id) ? $user->device_id : $device_id,
					'account_name'	=>	$user->account_name,
					'account_level'	=>	$user->account_level,
					'server_id'		=>	$server_id,
					'partner_key'	=>	$user->partner_key
				);
				$this->logs->write($logParameter);
			}
			else
			{
				$parameter = array(
					'name'		=>	$accountName,
					'pass'		=>	$accountPass,
					'email'		=>	'',
					'question'	=>	'',
					'answer'	=>	'',
					'server_id'	=>	$server_id,
					'status'	=>	1,
					'partner'	=>	$partner,
					'device_id'	=>	$device_id
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					$user = $this->web_account->get($guid);
					unset($user->account_pass);
					unset($user->account_secret_key);
					$user->guid_code = md5(sha1($user->GUID));
					$jsonData = Array(
						'success'	=>	1,
						'error_code'=>	0,
						'message'	=>	'ACCOUNT_VALIDATE_SUCCESS',
						'user'		=>	$user
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'device_id'		=>	$device_id,
						'account_name'	=>	$user->account_name,
						'server_id'		=>	$server_id,
						'partner_key'	=>	$partner
					);
					$this->logs->write($logParameter);
				}
				else
				{
					$jsonData = Array(
						'success'	=>	0,
						'error_code'=>	ERROR_REGISTER_FAIL,
						'errors'	=>	'ACCOUNT_REGISTER_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_FAIL',
						'account_guid'	=>	'',
						'device_id'		=>	$device_id,
						'account_name'	=>	$accountName,
						'server_id'		=>	$server_id,
						'partner_key'	=>	$partner
					);
					$this->logs->write($logParameter);
				}
			}
		}
		else
		{
			$jsonData = Array(
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
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
	
	public function login($format = 'json') {
		$accountName	=	$this->input->get_post('account_name', TRUE);
		$accountPass	=	$this->input->get_post('account_pass', TRUE);
		$server_id		=	$this->input->get_post('server_id', TRUE);
		$device_id		=	$this->input->get_post('device_id', TRUE);

		$device_id = empty($device_id) ? '' : $device_id;
		
		if(!empty($accountName) && !empty($accountPass) && !empty($server_id))
		{
			$user = $this->web_account->validate($accountName, $accountPass, $server_id);
			if($user != FALSE) {
				unset($user->account_pass);
				unset($user->account_secret_key);

				$db = $this->web_account->db();
				$time = time();
				
				if ($user->account_status == '-1') {
					if($user->closure_endtime <= $time)
					{
						$sql = "update `web_account` set `account_status`=1, `closure_endtime`=0 where `GUID`='{$user->GUID}'";
						$db->query($sql);
					}
					else
					{
						$jsonData = Array(
							'success'	=>	0,
							'error_code'=>	ERROR_ACCOUNT_BANNED,
							'errors'	=>	'ACCOUNT_VALIDATE_FAIL_BANNED',
							'endtime'	=>	$user->closure_endtime
						);
						exit($this->return_format->format($jsonData, $format));
					}
				}

				$sql = "update `web_account` set `account_lastlogin`={$time}, `account_activity`=`account_activity`+1 where `GUID`='{$user->GUID}'";
				$db->query($sql);
				$jsonData = Array(
					'success'	=>	1,
					'error_code'=>	0,
					'message'	=>	'ACCOUNT_VALIDATE_SUCCESS',
					'user'		=>	$user
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_LOGIN_SUCCESS',
					'account_guid'	=>	$user->GUID,
					'device_id'		=>	empty($device_id) ? $user->device_id : $device_id,
					'account_name'	=>	$user->account_name,
					'account_level'	=>	$user->account_level,
					'server_id'		=>	$server_id,
					'partner_key'	=>	$user->partner_key
				);
				$this->logs->write($logParameter);
			} else {
				$jsonData = Array(
					'success'	=>	0,
					'error_code'=>	ERROR_LOGIN_FAIL,
					'errors'	=>	'ACCOUNT_VALIDATE_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_LOGIN_FAIL',
					'account_guid'	=>	'',
					'device_id'		=>	$device_id,
					'account_name'	=>	$accountName,
					'server_id'		=>	$server_id
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
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
	
	public function enter($format = 'json')
	{
		$guid = $this->input->get_post('guid', TRUE);
		$job = $this->input->get_post('job', TRUE);
		$face = $this->input->get_post('profession_icon', TRUE);
		$level = $this->input->get_post('level', TRUE);
		$mission = $this->input->get_post('mission', TRUE);
		$nickname = $this->input->get_post('nickname', TRUE);
		$device_id = $this->input->get_post('device_id', TRUE);
		$ad_id = $this->input->get_post('ad_id', TRUE);
		
		if(!empty($guid) && (!empty($job) || !empty($face) || !empty($level) || !empty($mission) || !empty($nickname)))
		{
			if(!empty($job)) {
				$parameter['account_job'] = $job;
			}
			if(!empty($face)) {
				$parameter['profession_icon'] = $face;
			}
			if(!empty($level)) {
				$parameter['account_level'] = intval($level);
			}
			if(!empty($mission)) {
				$parameter['account_mission'] = $mission;
			}
			if(!empty($nickname)) {
				$parameter['account_nickname'] = $nickname;
			}
			if(!empty($device_id)) {
				$parameter['device_id'] = $device_id;
			}
			if(!empty($ad_id))
			{
				$parameter['ad_id'] = $ad_id;
			}
			$this->web_account->update($parameter, $guid);

			$jsonData = Array(
					'success'	=>	1,
					'error_code'=>	0,
					'message'	=>	'ACCOUNT_ENTER_SUCCESS'
			);
			echo $this->return_format->format($jsonData, $format);
		}
		else
		{
			$jsonData = Array(
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
				'errors'	=>	'ACCOUNT_ENTER_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
			
			$logParameter = array(
					'account_guid'	=>	$guid,
					'account_name'	=>	'',
					'log_action'	=>	'ACCOUNT_ENTER_ERROR_NO_PARAM'
			);
			$this->logs->write_api($logParameter);
		}
	}
	
	public function generate()
	{
		for($i=0; $i<1000; $i++)
		{
			$name = sprintf('test%03u', $i);
			$post_data = array(
					'account_name'	=>	$name,
					'account_pass'	=>	$name,
					'server_id'		=>	'V'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://192.168.2.230/UserAgent_zhanshen/websrv/account/register");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);
			echo $output;
			echo '<br>';
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
		$partner	=	$this->input->get_post('partner', TRUE);
		$device_id	=	$this->input->get_post('device_id', TRUE);
		
		$partner = empty($partner) ? 'default' : $partner;
		$accountEmail = $accountEmail===FALSE ? '' : $accountEmail;
		$country = $country===FALSE ? '' : $country;
		$question = $question===FALSE ? '' : $question;
		$answer = $answer===FALSE ? '' : $answer;
		$device_id = empty($device_id) ? '' : $device_id;
		
		if(!empty($name) && !empty($pass) && !empty($server_id))
		{
			$forbiddenWords = $this->config->item('forbidden_words');
			if(in_array($name, $forbiddenWords))
			{
				$jsonData = Array(
					'success'	=>	0,
					'error_code'=>	ERROR_REGISTER_FAIL,
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
					'server_id'	=>	$server_id,
					'status'	=>	1,
					'partner'	=>	$partner,
					'device_id'	=>	$device_id
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					$user = $this->web_account->get($guid);
					unset($user->account_pass);
					unset($user->account_secret_key);
					$user->guid_code = md5(sha1($user->GUID));
					$jsonData = Array(
						'success'	=>	1,
						'error_code'=>	0,
						'message'	=>	'ACCOUNT_REGISTER_SUCCESS',
						'user'		=>	$user
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'device_id'		=>	$device_id,
						'account_name'	=>	$user->account_name,
						'server_id'		=>	$server_id,
						'device_id'		=>	$device_id,
						'partner_key'	=>	$partner
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'success'	=>	0,
						'error_code'=>	ERROR_REGISTER_FAIL,
						'errors'	=>	'ACCOUNT_REGISTER_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_REGISTER_FAIL',
						'account_guid'	=>	'',
						'device_id'		=>	$device_id,
						'account_name'	=>	$name,
						'server_id'		=>	$server_id,
						'partner_key'	=>	$partner
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'success'	=>	0,
					'error_code'=>	ERROR_ACCOUNT_DUPLICATED,
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_REGISTER_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'device_id'		=>	$device_id,
					'account_name'	=>	$name,
					'server_id'		=>	$server_id,
					'partner_key'	=>	$partner
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
				'errors'	=>	'ACCOUNT_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
			
			$logParameter = array(
				'log_action'	=>	'ACCOUNT_REGISTER_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'device_id'		=>	$device_id,
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function check_duplicated($format = 'json') {
		$name		=	$this->input->get_post('account_name', TRUE);
		$pass		=	$this->input->get_post('account_pass', TRUE);
		$server_id	=	$this->input->get_post('server_id', TRUE);

		if(!empty($name)) {

			if($this->web_account->validate_duplicate($name, $pass, $server_id)) {
				$jsonData = Array(
					'success'	=>	1,
					// 'error_code'=>	0,
					'message'	=>	'ACCOUNT_CHECK_SUCCESS'
				);
				echo $this->return_format->format($jsonData, $format);
			} else {
				$jsonData = Array(
					'success'	=>	0,
					// 'error_code'=>	ERROR_ACCOUNT_DUPLICATED,
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'success'	=>	0,
				// 'error_code'=>	ERROR_NO_PARAM,
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
								'success'	=>	1,
								'error_code'=>	0,
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
								'success'	=>	0,
								'error_code'=>	ERROR_CHANGE_FAIL,
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
							'success'	=>	0,
							'error_code'=>	ERROR_ACCOUNT_DUPLICATED,
							'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
						);
						echo $this->return_format->format($jsonData, $format);
					}
				} else {
					$jsonData = Array(
						'success'	=>	0,
						'error_code'=>	ERROR_PASSWORD_ERROR,
						'errors'	=>	'ACCOUNT_ERROR_PASSWORD'
					);
					echo $this->return_format->format($jsonData, $format);
				}
			} else {
				$jsonData = Array(
					'success'	=>	0,
					'error_code'=>	ERROR_ACCOUNT_NOT_EXIST,
					'errors'	=>	'ACCOUNT_ERROR_NO_GUID'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
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
		$server_id = $this->input->get_post('server_id', TRUE);
		$partner = $this->input->get_post('partner', TRUE);
		$device_id = $this->input->get_post('device_id', TRUE);
		$ad_id = $this->input->get_post('ad_id', TRUE);

		$device_id = empty($device_id) ? '' : $device_id;
		$ad_id = empty($ad_id) ? '' : $ad_id;

		if(empty($partner))
		{
			$partner = 'default';
		}
		log_message('error', 'demo: server_id=' . $server_id . ', partner=' . $partner);
		$this->load->library('guid');
		$this->load->helper('security');
		$guid = do_hash($this->guid->toString(), 'md5');
		$name = 'G' . substr($guid, 0, 11);
		$pass = substr(do_hash($this->guid->newGuid()->toString(), 'md5'), 0, 8);
		
		if(empty($server_id))
		{
			$this->load->model('websrv/server', 'server');
			$parameter = array(
				'server_recommend'		=>	'1'
			);
			$result = $this->server->getAllResult($parameter);
			if($result!=FALSE) {
				$server_id = $result[0]->account_server_id;
			} else {
				$parameter = array(
					'order_by'				=>	'server_sort'
				);
				$result = $this->server->getAllResult($parameter);
				$server_id = $result[0]->account_server_id;
			}
		}
		if(!empty($name) && !empty($pass) && !empty($server_id))
		{
			if($this->web_account->validate_duplicate($name, $pass, $server_id)) {
				$parameter = array(
					'name'		=>	$name,
					'pass'		=>	$pass,
					'email'		=>	'',
					'server_id'	=>	$server_id,
					'status'	=>	0,
					'partner'	=>	$partner,
					'device_id'	=>	$device_id
				);
				$guid = $this->web_account->register($parameter);
				if(!empty($guid)) {
					$user = $this->web_account->get($guid);
					unset($user->account_secret_key);
					$user->account_pass = $pass;
					$user->guid_code = md5(sha1($user->GUID));

					$jsonData = Array(
						'success'	=>	1,
						'error_code'=>	0,
						'message'	=>	'ACCOUNT_DEMO_SUCCESS',
						'user'		=>	$user,
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_SUCCESS',
						'account_guid'	=>	$user->GUID,
						'device_id'		=>	$device_id,
						'account_name'	=>	$user->account_name,
						'server_id'		=>	$server_id,
						'partner_key'	=>	$partner
					);
					$this->logs->write($logParameter);
				} else {
					$jsonData = Array(
						'success'	=>	0,
						'error_code'=>	ERROR_DEMO_FAIL,
						'errors'	=>	'ACCOUNT_DEMO_FAIL'
					);
					echo $this->return_format->format($jsonData, $format);
					
					$logParameter = array(
						'log_action'	=>	'ACCOUNT_DEMO_FAIL',
						'account_guid'	=>	'',
						'device_id'		=>	$device_id,
						'account_name'	=>	$name,
						'server_id'		=>	$server_id,
						'partner_key'	=>	$partner
					);
					$this->logs->write($logParameter);
				}
			} else {
				$jsonData = Array(
					'success'	=>	0,
					'error_code'=>	ERROR_ACCOUNT_DUPLICATED,
					'errors'	=>	'ACCOUNT_ERROR_DUPLICATE'
				);
				echo $this->return_format->format($jsonData, $format);
				
				$logParameter = array(
					'log_action'	=>	'ACCOUNT_DEMO_FAIL_DUPLICATE',
					'account_guid'	=>	'',
					'device_id'		=>	$device_id,
					'account_name'	=>	$name,
					'server_id'		=>	$server_id
				);
				$this->logs->write($logParameter);
			}
		} else {
			$jsonData = Array(
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
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
					'success'	=>	0,
					'error_code'=>	ERROR_ACCOUNT_NOT_EXIST,
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
							'success'	=>	0,
							'error_code'=>	ERROR_ACCOUNT_DUPLICATED,
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
						'success'	=>	1,
						'error_code'=>	0,
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
						'success'	=>	0,
						'error_code'=>	ERROR_MODIFY_FAIL,
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
					'success'	=>	0,
					'error_code'=>	ERROR_MODIFY_NOTHING,
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
				'success'	=>	0,
				'error_code'=>	ERROR_NO_PARAM,
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
	
	public function clear($format = 'json')
	{
		$serverId = $this->input->get_post('server_id', TRUE);
		
		if(!empty($serverId))
		{
			
		}
	}
	
	public function checkGuid($format = 'json')
	{
		$guid 			=	$this->input->get_post('guid', TRUE);
	
		if(!empty($guid))
		{
				
			$result = $this->web_account->get($guid);
			$result->guid_code = md5(sha1($result->GUID));
			if(!empty($result))
			{
				$jsonData = Array(
						'success'	=>	1,
						'error_code'=>	0,
						'message'	=>	'ACCOUNT_GUID_SUCCESS',
						'user'		=>	$result
				);
				exit($this->return_format->format($jsonData, $format));
			}
			else
			{
				$jsonData = Array(
						'success'	=>	0,
						'error_code'=>	ERROR_GUID_ERROR,
						'message'	=>	'ACCOUNT_GUID_ERROR',
						'user'		=>	null
				);
				exit($this->return_format->format($jsonData, $format));
			}
		}
	}
	
	public function verify_login_token()
	{
		$this->load->model('return_format');
		
		$guid = $this->input->get_post('guid', TRUE);
		$token = $this->input->get_post('token', TRUE);
		
		if(!empty($guid) && !empty($token))
		{
			$this->load->model('mtoken');
			$this->load->model('web_account');
			
			$parameter = array(
					'guid'	=>	$guid,
					'token'	=>	$token
			);
			$result = $this->mtoken->read($parameter);
			if(!empty($result))
			{
// 				$result = $result[0];
// 				if($result->expire_time > time())
// 				{
					$user = $this->web_account->get($guid);
					$json = array(
							'success'	=>	1,
							'error_code'=>	0,
							'code'		=>	LOGIN_TOKEN_SUCCESS,
							'user'		=>	$user
					);
					
					$parameter = array(
							'account_lastlogin'		=>	time()
					);
					$this->web_account->update($parameter, $guid);

					$logParameter = array(
							'log_action'	=>	'ACCOUNT_LOGIN_SUCCESS',
							'account_guid'	=>	$user->GUID,
							'account_name'	=>	$user->account_name,
							'account_level'	=>	empty($user->account_level) ? 1 : $user->account_level,
							'server_id'		=>	$user->server_id,
							'partner_key'	=>	$user->partner_key
					);
					$this->logs->write($logParameter);
// 				}
// 				else
// 				{
// 					$json = array(
// 							'success'	=>	0,
// 							'code'		=>	VERIFY_LOGIN_TOKEN_ERROR_EXPIRED
// 					);
// 				}
			}
			else
			{
				$json = array(
						'success'	=>	0,
						'error_code'=>	ERROR_LOGIN_TOKEN_ERROR,
						'code'		=>	VERIFY_LOGIN_TOKEN_ERROR_NOT_EXIST
				);
			}
		}
		else
		{
			$json = array(
					'success'	=>	0,
					'error_code'=>	ERROR_NO_PARAM,
					'code'		=>	VERIFY_LOGIN_TOKEN_ERROR_NO_PARAM
			);
		}
		
		echo $this->return_format->format($json);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
