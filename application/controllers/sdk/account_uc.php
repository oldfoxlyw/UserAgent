<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_uc extends CI_Controller
{
	private $check_code = 'YI7RclFHiSIk4mbz*D9OGstjDN&QkjehA6SvfRj_2awnBUPOy@ITCctHmhhNDJbY';
	private $wanwan_server = 'http://sdk.g.uc.cn/ss/';
	private $service = 'ucid.user.sidInfo';
	private $cpId = '32634';
	private $gameId = '536890';
	
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function request_login()
	{
		$this->load->model('return_format');
		
		$uid = $this->input->get_post('uid', TRUE);
		$session_id = $this->input->get_post('session_id', TRUE);
		$partner_key = $this->input->get_post('partner_key', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($uid) || empty($partner_key))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$session_id = $inputParam->session_id;
			$partner_key = $inputParam->partner_key;
			$code = $inputParam->code;
		}
		
		if(!empty($uid) && !empty($partner_key))
		{
			$parameter = array(
					'uid'			=>	$uid,
					'session_id'	=>	$session_id,
					'partner_key'	=>	$partner_key
			);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->model('web_account');
				$this->load->model('mtoken');
				$this->load->model('webapi/connector');
				$this->load->helper('security');
				
				//向wanwan验证登录并获取uid
				$params = array(
						'token'		=>	$uid
				);
				$paramStr = $this->connector->getQueryString($params);
				$path = $this->wanwan_server . $this->api_name;
				$timestamp = date('D, d M Y H:i:s') . ' GMT';
				$sign = md5("{$timestamp}:{$this->api_name}:{$paramStr}:{$this->GAME_SECRET}");
				$auth = "{$this->VENDOR} {$this->GAME_ID}:{$sign}";
				$header = array(
						'Date:' . $timestamp,
						'Accept:application/json; version=' . $this->api_version,
						'Authentication:' . $auth
				);
				$result = $this->connector->post($path, $params, false, $header);
				//--------------------------------------
				$sql = "insert into debug(text)values('" . 'login:' . $result . "')";
				$this->web_account->db()->query($sql);
				//--------------------------------------
				$result = json_decode($result);
				if(!empty($result) && !empty($result->usergameid))
				{
					$parameter = array(
							'partner_key'			=>	$partner_key,
							'partner_id'			=>	$result->usergameid,
							'account_nickname !='	=>	''
					);
					$extension = array(
							'select'	=>	'GUID,account_name,server_id,account_nickname,account_status,account_job,profession_icon,account_level,account_mission,partner_key,partner_id',
							'order_by'	=>	array('account_lastlogin', 'desc')
					);
					$result = $this->web_account->read($parameter, $extension);
					if(empty($result))
					{
						$result = array();
					}
					$time = time();
					for($i = 0; $i<count($result); $i++)
					{
						$this->mtoken->update($result[$i]->GUID, array(
								'expire_time'	=>	0
						));
						$hash = do_hash($result[$i]->GUID . $time . mt_rand());
						$result[$i]->token = $hash;
						$parameter = array(
								'guid'			=>	$result[$i]->GUID,
								'token'			=>	$hash,
								'expire_time'	=>	$time + 600
						);
						$this->mtoken->create($parameter);
					}
					
					$json = array(
							'success'		=>	true,
							'message'		=>	'SDK_LOGIN_SUCCESS',
							'result'		=>	$result
					);
				}
				else
				{
					$json = array(
							'success'		=>	false,
							'message'		=>	'SDK_REGISTER_FAIL'
					);
				}
			}
			else
			{
				$json = array(
						'success'		=>	false,
						'message'		=>	'SDK_LOGIN_FAIL_ERROR_CHECK_CODE'
				);
			}
		}
		else
		{
			$json = array(
					'success'		=>	false,
					'message'		=>	'SDK_LOGIN_FAIL_NO_PARAM'
			);
		}
		
		echo $this->return_format->format($json);
	}
	
	public function request_register()
	{
		$this->load->model('return_format');
		
		$uid = $this->input->get_post('uid', TRUE);
		$session_id = $this->input->get_post('session_id', TRUE);
		$server_id = $this->input->get_post('server_id', TRUE);
		$partner_key = $this->input->get_post('partner_key', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($uid) || empty($server_id) || empty($partner_key))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$session_id = $inputParam->session_id;
			$server_id = $inputParam->server_id;
			$partner_key = $inputParam->partner_key;
			$code = $inputParam->code;
		}
		
		if(!empty($uid) && !empty($server_id) && !empty($partner_key))
		{
			$parameter = array(
					'uid'			=>	$uid,
					'session_id'	=>	$session_id,
					'server_id'		=>	$server_id,
					'partner_key'	=>	$partner_key
			);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->library('guid');
				$this->load->helper('security');
				$this->load->model('web_account');
				$this->load->model('mtoken');

				//向wanwan验证登录并获取uid
				$params = array(
						'token'		=>	$uid
				);
				$paramStr = $this->connector->getQueryString($params);
				$path = $this->wanwan_server . $this->api_name;
				$timestamp = date('D, d M Y H:i:s') . ' GMT';
				$sign = md5("{$timestamp}:{$this->api_name}:{$paramStr}:{$this->GAME_SECRET}");
				$auth = "{$this->VENDOR} {$this->GAME_ID}:{$sign}";
				$header = array(
						'Date:' . $timestamp,
						'Accept:application/json; version=' . $this->api_version,
						'Authentication:' . $auth
				);
				$result = $this->connector->post($path, $params, false, $header);
				//--------------------------------------
				$sql = "insert into debug(text)values('" . 'register:' . $result . "')";
				$this->web_account->db()->query($sql);
				//--------------------------------------
				$result = json_decode($result);
				if(!empty($result) && !empty($result->usergameid))
				{
// 				$parameter = array(
// 						'partner_id'	=>	$uid,
// 						'server_id'		=>	$server_id
// 				);
// 				$result = $this->web_account->read($parameter);
// 				if(!empty($result))
// 				{
// 					$json = array(
// 							'success'		=>	false,
// 							'message'		=>	'SDK_REGISTER_FAIL_EXIST'
// 					);
// 				}
// 				else
// 				{
					$name = strtolower(do_hash($this->guid->toString(), 'md5'));
					$pass = $name;
					$name = 'P' . $name;
					
					$parameter = array(
							'account_name'		=>	$name,
							'account_pass'		=>	$this->web_account->encrypt_pass($pass),
							'server_id'			=>	$server_id,
							'account_regtime'	=>	time(),
							'partner_key'		=>	$partner_key,
							'partner_id'		=>	$result->usergameid
					);
					$guid = $this->web_account->create($parameter);
					if($guid !== FALSE)
					{
						$user = $this->web_account->get($guid);
						unset($user->account_secret_key);
						$user->account_pass = $pass;
						
						$time = time();
						$hash = do_hash($guid . $time . mt_rand());
						$user->token = $hash;
						$parameter = array(
								'guid'			=>	$guid,
								'token'			=>	$hash,
								'expire_time'	=>	$time + 600
						);
						$this->mtoken->create($parameter);
						
						$json = array(
								'success'		=>	true,
								'message'		=>	'SDK_REGISTER_SUCCESS',
								'result'		=>	$user
						);
					}
					else
					{
						$json = array(
								'success'		=>	false,
								'message'		=>	'SDK_REGISTER_FAIL'
						);
					}
				}
				else
				{
					$json = array(
							'success'		=>	false,
							'message'		=>	'SDK_REGISTER_FAIL'
					);
				}
			}
			else
			{
				$json = array(
						'success'		=>	false,
						'message'		=>	'SDK_REGISTER_FAIL_ERROR_CHECK_CODE'
				);
			}
		}
		else
		{
			$json = array(
					'success'		=>	false,
					'message'		=>	'SDK_REGISTER_FAIL_NO_PARAM'
			);
		}
		
		echo $this->return_format->format($json);
	}
	
	private function verify_check_code($parameter, $code)
	{
		if(is_array($parameter))
		{
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