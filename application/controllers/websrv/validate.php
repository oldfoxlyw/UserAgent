<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validate extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model('return_format');
	}
	
	public function activate($format = 'json')
	{
		$code = $this->input->get_post('code', TRUE);
		
		if(empty($code))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			$code = $inputParam->code;
			
			if(empty($code))
			{
				$jsonData = array(
						'success'		=>	false,
						'error'			=>	'ACTIVATE_ERROR_NO_PARAM'
				);
				echo $this->return_format->format($jsonData, $format);
				exit();
			}
		}

		$this->load->model('mcode');
		$this->load->model('logs');
		
		$parameter = array(
				'code'		=>	$code,
				'disabled'	=>	0
		);
		$result = $this->mcode->read($parameter);
		
		if(!empty($result))
		{
			$parameter = array(
					'disabled'	=>	1
			);
			$this->mcode->update($code, $parameter);
			
			$jsonData = array(
					'success'		=>	true,
					'message'		=>	'ACTIVATE_SUCCESS'
			);
			echo $this->return_format->format($jsonData, $format);

			$logParameter = array(
					'account_guid'	=>	$code,
					'account_name'	=>	'',
					'log_action'	=>	'ACTIVATE_SUCCESS'
			);
			$this->logs->write_api($logParameter);
		}
		else
		{
			$jsonData = array(
					'success'		=>	false,
					'message'		=>	'ACTIVATE_FAIL'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function is_checked_code($format = 'json')
	{
		$code = $this->input->post('code', TRUE);

		$this->load->model('mcode');

		$parameter = array(
				'code'			=>	$code,
				'disabled'		=>	1
		);
		$result = $this->mcode->read($parameter);

		if(!empty($result))
		{
			$jsonData = array(
					'success'		=>	true,
					'message'		=>	'CHECKED'
			);
			echo $this->return_format->format($jsonData, $format);
		}
		else
		{
			$jsonData = array(
					'success'		=>	false,
					'message'		=>	'UNCHECKED'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function promotion_code($format = 'json')
	{
		$code = $this->input->get_post('code', TRUE);
		// $channel = $this->input->post('channel', TRUE);
		$code = strtoupper($code);

		$this->load->model('mcode');
		$this->load->model('logs');
		
		$parameter = array(
				'code'		=>	$code
		);
		// if(!empty($channel))
		// {
		// 	$parameter['comment'] = $channel;
		// }
		$result = $this->mcode->read($parameter);
		
		if(!empty($result))
		{
			$result = $result[0];
			if($result->disabled != '1')
			{
				$parameter = array(
						'disabled'	=>	1
				);
				$this->mcode->update($code, $parameter);
				
				$jsonData = array(
						'success'		=>	1,
						'message'		=>	'PROMOTION_SUCCESS'
				);

				$logParameter = array(
						'account_guid'	=>	$code,
						'account_name'	=>	'',
						'log_action'	=>	'PROMOTION_SUCCESS'
				);
				$this->logs->write_api($logParameter);
			}
			else
			{
				$jsonData = array(
						'success'		=>	0,
						'message'		=>	'PROMOTION_FAIL_USED'
				);
			}
		}
		else
		{
			$jsonData = array(
					'success'		=>	0,
					'message'		=>	'PROMOTION_FAIL_NOT_EXIST'
			);
		}

		echo $this->return_format->format($jsonData, $format);
	}

	public function get_code($format = 'json')
	{
		$server_id = $this->input->post('server_id', TRUE);
		$channel = $this->input->post('channel', TRUE);

		if(!empty($server_id) && !empty($channel))
		{
			$this->load->model('mcode');
			$this->load->model('logs');

			$parameter = array(
				'comment'		=>	$channel,
				'disabled'		=>	0,
				// 'server_id'		=>	$server_id
			);
			$result = $this->mcode->read($parameter, null, 1);

			if(!empty($result))
			{
				$result = $result[0];

				$parameter = array(
					'disabled'	=>	1
				);
				$this->mcode->update($result->code, $parameter);

				$jsonData = array(
					'success'	=>	1,
					'message'	=>	'SUCCESS',
					'code'		=>	$result->code
				);

				$logParameter = array(
						'account_guid'	=>	$result->code,
						'account_name'	=>	'',
						'log_action'	=>	'GET_CODE_SUCCESS'
				);
				$this->logs->write_api($logParameter);
			}
			else
			{
				$jsonData = array(
						'success'		=>	0,
						'message'		=>	'NO_CODE'
				);
			}
		}
		else
		{
			$jsonData = array(
					'success'		=>	0,
					'message'		=>	'NO_PARAM'
			);
		}

		echo $this->return_format->format($jsonData, $format);
	}
}

?>