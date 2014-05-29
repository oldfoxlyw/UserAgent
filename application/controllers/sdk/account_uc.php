<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_uc extends CI_Controller
{
	private $check_code = 'YI7RclFHiSIk4mbz*D9OGstjDN&QkjehA6SvfRj_2awnBUPOy@ITCctHmhhNDJbY';
	private $url = 'http://sdk.g.uc.cn/ss/';
// 	private $url = 'http://sdk.test4.g.uc.cn/ss';
	private $service = 'ucid.user.sidInfo';
	private $cpId = 32634;
	private $gameId = 536890;
	private $apiKey = 'e35824e52b996a5e6ec17bd9763f17ad';
	private $channelId = 2;
	private $serverId = 2623;
	
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
		
		if(!empty($partner_key))
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
				
				if(empty($uid))
				{
					//向uc验证登录并获取uid
					$params = array(
							'sid'	=>	$session_id
					);
					$paramStr = $this->connector->getQueryString($params);
					$paramStr = str_replace('&', '', $paramStr);
					$time = explode ( " ", microtime () );
					$time = $time [1] . ($time [0] * 1000);
					$time2 = explode ( ".", $time );
					$time = $time2 [0];
					$game = array(
							'cpId'		=>	$this->cpId,
							'gameId'	=>	$this->gameId,
							'channelId'	=>	$this->channelId,
							'serverId'	=>	$this->serverId
					);
					$sign = md5("{$this->cpId}{$paramStr}{$this->apiKey}");
					$header = array(
							'Content-Type: application/json'
					);
					$params = array(
							'id'		=>	$time,
							'service'	=>	$this->service,
							'data'		=>	$params,
							'game'		=>	$game,
							'sign'		=>	$sign
					);
					$result = $this->connector->post($this->url, json_encode($params), false, $header);
					//--------------------------------------
// 					$sql = "insert into debug(text)values('" . 'send:' . json_encode($params) . ', uc_login:' . $result . "')";
// 					$this->web_account->db()->query($sql);
					//--------------------------------------
					$result = json_decode($result);
					if(empty($result) || $result->state->code != '1')
					{
						$json = array(
								'success'		=>	false,
								'errors'		=>	'SDK_LOGIN_FAIL'
						);
						exit($this->return_format->format($json));
					}
					$uid = $result->data->ucid;
				}
				$parameter = array(
						'partner_key'			=>	$partner_key,
						'partner_id'			=>	$uid,
						'account_nickname !='	=>	'',
						'account_status >='		=>	0
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
					$tokenResult = $this->mtoken->read(array(
							'guid'	=>	$result[$i]->GUID
					));
					if(!empty($tokenResult))
					{
						$result[$i]->token = $tokenResult[0]->token;
					}
					else 
					{
						$hash = do_hash($result[$i]->GUID . $time . mt_rand());
						$result[$i]->token = $hash;
						$parameter = array(
								'guid'			=>	$result[$i]->GUID,
								'token'			=>	$hash,
								'expire_time'	=>	$time + 365 * 86400
						);
						$this->mtoken->create($parameter);
					}
				}
				
				$json = array(
						'success'		=>	true,
						'message'		=>	'SDK_LOGIN_SUCCESS',
						'result'		=>	$result,
						'uid'			=>	$uid
				);
			}
			else
			{
				$json = array(
						'success'		=>	false,
						'errors'		=>	'SDK_LOGIN_FAIL_ERROR_CHECK_CODE'
				);
			}
		}
		else
		{
			$json = array(
					'success'		=>	false,
					'errors'		=>	'SDK_LOGIN_FAIL_NO_PARAM'
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
		
		if(!empty($server_id) && !empty($partner_key))
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
				$this->load->model('webapi/connector');
				$this->load->model('mtoken');

				if(empty($uid))
				{
				//向uc验证登录并获取uid
					$params = array(
							'sid'	=>	$session_id
					);
					$paramStr = $this->connector->getQueryString($params);
					$paramStr = str_replace('&', '', $paramStr);
					$time = explode ( " ", microtime () );
					$time = $time [1] . ($time [0] * 1000);
					$time2 = explode ( ".", $time );
					$time = $time2 [0];
					$game = array(
							'cpId'		=>	$this->cpId,
							'gameId'	=>	$this->gameId,
							'channelId'	=>	'2',
							'serverId'	=>	0
					);
					$sign = md5("{$this->cpId}{$paramStr}{$this->apiKey}");
					$header = array(
							'Content-Type: application/json'
					);
					$params = array(
							'id'		=>	$time,
							'service'	=>	$this->service,
							'data'		=>	$params,
							'game'		=>	$game,
							'sign'		=>	$sign
					);
					$result = $this->connector->post($this->url, json_encode($params), false, $header);
					//--------------------------------------
// 					$sql = "insert into debug(text)values('" . 'send:' . json_encode($params) . ', uc_register:' . $result . "')";
// 					$this->web_account->db()->query($sql);
					//--------------------------------------
					$result = json_decode($result);
					if(!empty($result) && $result->state->code == '1')
					{
						$json = array(
								'success'		=>	false,
								'errors'		=>	'SDK_REGISTER_FAIL'
						);
						exit($this->return_format->format($json));
					}
					$uid = $result->data->ucid;
				}
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
					
					$time = time();
					$hash = do_hash($guid . $time . mt_rand());
					$user->token = $hash;
					$parameter = array(
							'guid'			=>	$guid,
							'token'			=>	$hash,
							'expire_time'	=>	$time + 365 * 86400
					);
					$this->mtoken->create($parameter);
					
					$json = array(
							'success'		=>	true,
							'message'		=>	'SDK_REGISTER_SUCCESS',
							'result'		=>	$user,
							'uid'			=>	$uid
					);

					$this->load->model('logs');
					$logParameter = array(
							'log_action'	=>	'ACCOUNT_REGISTER_SUCCESS',
							'account_guid'	=>	$user->GUID,
							'account_name'	=>	$user->account_name,
							'server_id'		=>	$server_id,
							'partner_key'	=>	$partner_key
					);
					$this->logs->write($logParameter);
				}
				else
				{
					$json = array(
							'success'		=>	false,
							'errors'		=>	'SDK_REGISTER_FAIL'
					);
					$logParameter = array(
							'log_action'	=>	'SDK_REGISTER_FAIL',
							'account_guid'	=>	'',
							'account_name'	=>	$name,
							'server_id'		=>	$server_id,
							'partner_key'	=>	$partner_key
					);
					$this->logs->write($logParameter);
				}
			}
			else
			{
				$json = array(
						'success'		=>	false,
						'errors'		=>	'SDK_REGISTER_FAIL_ERROR_CHECK_CODE'
				);
				$logParameter = array(
						'log_action'	=>	'SDK_REGISTER_FAIL_ERROR_CHECK_CODE',
						'account_guid'	=>	$user->GUID,
						'account_name'	=>	$user->account_name,
						'server_id'		=>	$server_id,
						'partner_key'	=>	$partner_key
				);
				$this->logs->write($logParameter);
			}
		}
		else
		{
			$json = array(
					'success'		=>	false,
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