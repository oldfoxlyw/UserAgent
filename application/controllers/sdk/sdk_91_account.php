<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sdk_91_account extends CI_Controller
{
	private $url = "http://service.sj.91.com/usercenter/AP.aspx";
	private $app_id = 102328;
	private $app_key = "f686c38a3d50f4f91aa6289908a81b8f6662cef0d901af32";
	private $act = 4;
	
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
		
		if(empty($uid) || empty($session_id) || empty($partner_key))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$uid = $inputParam->uid;
			$session_id = $inputParam->session_id;
			$partner_key = $inputParam->partner_key;
		}
		
		if(!empty($uid) && !empty($session_id) && !empty($partner_key))
		{
			$this->load->model('webapi/connector');
			
			$verify = array($this->app_id, $this->act, $uid, $session_id, $this->app_key);
			$parameter = array(
					'AppId'		=>	$this->app_id,
					'Act'		=>	$this->act,
					'Uin'		=>	$uid,
					'SessionId'	=>	$session_id,
					'Sign'		=>	$this->generateVerifyCode($verify)
			);
			$result = $this->connector->get($this->url, $parameter);
			$json = json_decode($result);
			if($json->ErrorCode == '1')
			{
				$this->load->model('web_account');
				$this->load->model('mtoken');
				$this->load->helper('security');
				
				$parameter = array(
						'partner_key'	=>	$partner_key,
						'partner_id'	=>	$uid
				);
				$extension = array(
						'select'	=>	'GUID,account_name,server_id,account_status,account_job,account_level,account_mission,partner_key,partner_id'
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
						'result'		=>	$result
				);
			}
			else
			{
				$json = array(
						'success'		=>	false,
						'message'		=>	'SDK_LOGIN_FAIL'
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
	
	private function generateVerifyCode($parameter)
	{
		$str = implode('', $parameter);
		return strtolower(md5($str));
	}
}

?>