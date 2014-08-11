<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_360 extends CI_Controller
{
	private $url = 'https://openapi.360.cn/oauth2/access_token';
	private $info_url = 'https://openapi.360.cn/user/me.json';
	private $client_id = '504b51f72f2ac3f7a8eff1ba857a2d55';
	private $client_secret = '3695c94df2c027899ad174eeedd4236b';
	
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function request_login()
	{
		$this->load->model('return_format');
		
		$uid = $this->input->get_post('uid', TRUE);
		$auth_code = $this->input->get_post('auth_code', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($auth_code))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$auth_code = $inputParam->auth_code;
			$code = $inputParam->code;
		}
		
		$partner_key = 'qihu360';
		if(!empty($uid))
		{
			$parameter = array(
					'partner_key'			=>	$partner_key,
					'partner_id'			=>	$uid,
					'account_nickname !='	=>	'',
					'account_status >='		=>	0
			);
			$extension = array(
					'select'	=>	'GUID,account_name,server_id,account_nickname,account_status,account_job,profession_icon,account_level,account_mission,partner_key,partner_id'
			);
			$result = $this->web_account->read($parameter, $extension);
			if(empty($result))
			{
				$result = array();
			}
			else
			{
				for($i = 0; $i<count($result); $i++)
				{
					$tokenResult = $this->msdktoken->read(array(
							'guid'		=>	$result[$i]->GUID,
							'partner'	=>	$partner_key
					));
					if(!empty($tokenResult))
					{
						$result[$i]->token = $tokenResult[0]->token;
					}
					else 
					{
						$parameter = array(
								'guid'			=>	$result[$i]->GUID,
								'partner'		=>	$partner_key,
								'token'			=>	$access_token,
								'refresh_token'	=>	$refresh_token,
								'expire_time'	=>	$expire_time
						);
						$this->msdktoken->create($parameter);

						$result[$i]->token = $access_token;
						$parameter = array(
								'guid'			=>	$result[$i]->GUID,
								'token'			=>	$access_token,
								'expire_time'	=>	$time + 365 * 86400
						);
						$this->mtoken->create($parameter);
					}
				}
			}
				
			$json = array(
					'success'		=>	1,
					'message'		=>	'SDK_LOGIN_SUCCESS',
					'access_token'	=>	$access_token,
					'refresh_token'	=>	$refresh_token,
					'uid'			=>	$uid,
					'result'		=>	$result
			);
		}
		elseif(!empty($auth_code))
		{

			$parameter = array(
					'auth_code'		=>	$auth_code
			);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->model('web_account');
				$this->load->model('msdktoken');
				$this->load->model('mtoken');
				$this->load->model('webapi/connector');
				$this->load->helper('security');
				
				//向360请求换取access_token
				$params = array(
						'grant_type'	=>	'authorization_code',
						'code'			=>	$auth_code,
						'client_id'		=>	$this->client_id,
						'client_secret'	=>	$this->client_secret,
						'redirect_uri'	=>	'oob'
				);
				$result = $this->connector->get($this->url, $params, false);
				//--------------------------------------
				$sql = "insert into debug(text)values('send:" . json_encode($params) . ', login:' . $result . "')";
				$this->web_account->db()->query($sql);
				//--------------------------------------
				$result = json_decode($result);
				if(empty($result) || empty($result->access_token))
				{
					$json = array(
							'success'		=>	0,
							'errors'		=>	'SDK_LOGIN_FAIL'
					);
					exit($this->return_format->format($json));
				}
				$time = time();
				$access_token = $result->access_token;
				$expire_time = $time + $result->expires_in;
				$refresh_token = $result->refresh_token;
				$params = array(
					'access_token'	=>	$access_token
				);
				$info = $this->connector->get($this->info_url, $params, false);
				//--------------------------------------
				$sql = "insert into debug(text)values('send:" . json_encode($params) . ', info:' . $info . "')";
				$this->web_account->db()->query($sql);
				//--------------------------------------

				$info = json_decode($info);
				if(empty($info) || empty($info->id))
				{
					$json = array(
							'success'		=>	0,
							'errors'		=>	'SDK_LOGIN_FAIL'
					);
					exit($this->return_format->format($json));
				}

				$uid = $info->id;

				$parameter = array(
						'partner_key'			=>	$partner_key,
						'partner_id'			=>	$uid,
						'account_nickname !='	=>	'',
						'account_status >='		=>	0
				);
				$extension = array(
						'select'	=>	'GUID,account_name,server_id,account_nickname,account_status,account_job,profession_icon,account_level,account_mission,partner_key,partner_id'
				);
				$result = $this->web_account->read($parameter, $extension);
				if(empty($result))
				{
					$result = array();
				}
				else
				{
					for($i = 0; $i<count($result); $i++)
					{
						$tokenResult = $this->msdktoken->read(array(
								'guid'		=>	$result[$i]->GUID,
								'partner'	=>	$partner_key
						));
						if(!empty($tokenResult))
						{
							$result[$i]->token = $tokenResult[0]->token;
						}
						else 
						{
							$parameter = array(
									'guid'			=>	$result[$i]->GUID,
									'partner'		=>	$partner_key,
									'token'			=>	$access_token,
									'refresh_token'	=>	$refresh_token,
									'expire_time'	=>	$expire_time
							);
							$this->msdktoken->create($parameter);

							$result[$i]->token = $access_token;
							$parameter = array(
									'guid'			=>	$result[$i]->GUID,
									'token'			=>	$access_token,
									'expire_time'	=>	$time + 365 * 86400
							);
							$this->mtoken->create($parameter);
						}
					}
				}
					
				$json = array(
						'success'		=>	1,
						'message'		=>	'SDK_LOGIN_SUCCESS',
						'access_token'	=>	$access_token,
						'refresh_token'	=>	$refresh_token,
						'uid'			=>	$uid,
						'result'		=>	$result
				);
			}
			else
			{
				$json = array(
						'success'		=>	0,
						'errors'		=>	'SDK_LOGIN_FAIL_ERROR_CHECK_CODE'
				);
			}
		}
		else
		{
			$json = array(
					'success'		=>	0,
					'errors'		=>	'SDK_LOGIN_FAIL_NO_PARAM'
			);
		}
		
		echo $this->return_format->format($json);
	}
	
	public function request_register()
	{
		$this->load->model('return_format');
		
		$uid = $this->input->get_post('uid', TRUE);
		$server_id = $this->input->get_post('server_id', TRUE);
		$access_token = $this->input->get_post('access_token', TRUE);
		$refresh_token = $this->input->get_post('refresh_token', TRUE);
		$expire_time = $this->input->get_post('expire_time', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($uid) || empty($server_id))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$server_id = $inputParam->server_id;
			$access_token = $inputParam->access_token;
			$refresh_token = $inputParam->refresh_token;
			$code = $inputParam->code;
		}
		
		if(!empty($uid) && !empty($server_id) && !empty($access_token) && !empty($refresh_token))
		{
			$partner_key = 'qihu360';
			$parameter = array(
					'uid'			=>	$uid,
					'server_id'		=>	$server_id,
					'access_token'	=>	$access_token,
					'refresh_token'	=>	$refresh_token
			);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->library('guid');
				$this->load->helper('security');
				$this->load->model('web_account');
				$this->load->model('webapi/connector');
				$this->load->model('msdktoken');
				$this->load->model('mtoken');
				
				$name = strtolower(do_hash($this->guid->toString(), 'md5'));
				$pass = $name;
				$name = 'P' . $name;
				
				$parameter = array(
						'account_name'		=>	$name,
						'account_pass'		=>	$this->web_account->encrypt_pass($pass),
						'server_id'			=>	$server_id,
						'account_regtime'	=>	time(),
						'partner_key'		=>	$partner_key,
						'partner_id'		=>	$uid
				);
				$guid = $this->web_account->create($parameter);
				if($guid !== FALSE)
				{
					$user = $this->web_account->get($guid);
					unset($user->account_secret_key);
					$user->account_pass = $pass;
					
					$expire_time = empty($expire_time) ? time() + 365 * 86400 : $expire_time;
					$time = time();
					$parameter = array(
							'guid'			=>	$guid,
							'partner'		=>	$partner_key,
							'token'			=>	$access_token,
							'refresh_token'	=>	$refresh_token,
							'expire_time'	=>	$expire_time
					);
					$this->msdktoken->create($parameter);

					$user->token = $access_token;
					$parameter = array(
							'guid'			=>	$guid,
							'token'			=>	$access_token,
							'expire_time'	=>	$time + 365 * 86400
					);
					$this->mtoken->create($parameter);
					
					$json = array(
							'success'		=>	1,
							'message'		=>	'SDK_REGISTER_SUCCESS',
							'result'		=>	$user
					);
					
					$this->load->model('logs');
					$logParameter = array(
							'log_action'	=>	'ACCOUNT_REGISTER_SUCCESS',
							'account_guid'	=>	$user->GUID,
							'account_name'	=>	$user->account_name,
							'server_id'		=>	$server_id
					);
					$this->logs->write($logParameter);
				}
				else
				{
					$json = array(
							'success'		=>	0,
							'errors'		=>	'SDK_REGISTER_FAIL'
					);
				}
			}
			else
			{
				$json = array(
						'success'		=>	0,
						'errors'		=>	'SDK_REGISTER_FAIL_ERROR_CHECK_CODE'
				);
			}
		}
		else
		{
			$json = array(
					'success'		=>	0,
					'errors'		=>	'SDK_REGISTER_FAIL_NO_PARAM'
			);
		}
		
		echo $this->return_format->format($json);
	}
	
	public function request_delete()
	{
		$this->load->model('return_format');
		
		$guid = $this->input->get_post('guid', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($guid))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$guid = $inputParam->guid;
			$code = $inputParam->code;
		}

		if(!empty($guid))
		{
			$parameter = array(
					'guid'		=>	$guid
			);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->model('web_account');
				
				$parameter = array(
						'account_status'	=>	-9
				);
				$this->web_account->update($parameter, $guid);

				$json = array(
						'success'		=>	true,
						'message'		=>	'SDK_DELETE_SUCCESS'
				);
			}
			else
			{
				$json = array(
						'success'		=>	false,
						'errors'		=>	'SDK_DELETE_FAIL_ERROR_CHECK_CODE'
				);
			}
		}
		else
		{
			$json = array(
					'success'		=>	false,
					'errors'		=>	'SDK_DELETE_FAIL_NO_PARAM'
			);
		}
		
		echo $this->return_format->format($json);
	}
	
	private function verify_check_code($parameter, $code)
	{
		if(is_array($parameter))
		{
			foreach($parameter as $key=>$value)
			{
				if(empty($value))
				{
					unset($parameter[$key]);
				}
			}
			ksort($parameter);
			array_push($parameter, $this->check_code);
			
			$str = strtolower(md5(implode('', $parameter)));

// 			$this->load->model('web_account');
// 			$database = $this->web_account->db();
// 			$database->query("insert into `debug`(`text`)VALUES('" . implode('', $parameter) . '   ' . $str . '   ' . $code . "')");
			
			if($code == $str)
			{
				return true;
			}
		}
		return false;
	}
}

?>