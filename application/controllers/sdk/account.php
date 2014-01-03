<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller
{
	private $check_code = 'decdd397d0e4959aae4bcc319ee8e41d68ada9c5';
	
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function request_login()
	{
		$this->load->model('return_format');
		
		$uid = $this->input->get_post('uid', TRUE);
		$partner_key = $this->input->get_post('partner_key', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($uid) || empty($partner_key))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$partner_key = $inputParam->partner_key;
			$code = $inputParam->code;
		}
		
		if(!empty($uid) && !empty($partner_key))
		{
			$parameter = array($uid, $partner_key);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->model('web_account');
				$this->load->model('mtoken');
				$this->load->helper('security');
				
				$parameter = array(
						'partner_key'	=>	$partner_key,
						'partner_id'	=>	$uid
				);
				$extension = array(
						'select'	=>	'GUID,account_name,server_id,account_nickname,account_status,account_job,account_level,account_mission,partner_key,partner_id'
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
		$server_id = $this->input->get_post('server_id', TRUE);
		$partner_key = $this->input->get_post('partner_key', TRUE);
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($uid) || empty($server_id) || empty($partner_key))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$server_id = $inputParam->server_id;
			$partner_key = $inputParam->partner_key;
		}
		
		if(!empty($uid) && !empty($server_id) && !empty($partner_key))
		{
			$parameter = array($uid, $server_id, $partner_key);
			if($this->verify_check_code($parameter, $code))
			{
				$this->load->library('guid');
				$this->load->helper('security');
				$this->load->model('web_account');
				
				$parameter = array(
						'partner_id'	=>	$uid,
						'server_id'		=>	$server_id
				);
				$result = $this->web_account->read($parameter);
				if(!empty($result))
				{
					$json = array(
							'success'		=>	false,
							'message'		=>	'SDK_REGISTER_FAIL_EXIST'
					);
				}
				else
				{
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
			array_push($parameter, $this->check_code);
			
			$str = strtolower(md5(implode('', $parameter)));
			
			if($code == $str)
			{
				return true;
			}
		}
		return false;
	}
}

?>