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
	
	public function is_received_pack($format = 'json')
	{
		$code = $this->input->post('code', TRUE);

		$this->load->model('mcode');

		$parameter = array(
				'code'			=>	$code,
				'is_get_pack'	=>	0
		);

		if(!empty($result))
		{
			$jsonData = array(
					'success'		=>	false,
					'message'		=>	'PACK_NOT_RECEIVED'
			);
			echo $this->return_format->format($jsonData, $format);
		}
		else
		{
			$jsonData = array(
					'success'		=>	true,
					'message'		=>	'PACK_RECEIVED'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function receive_pack($format = 'json')
	{
		$code = $this->input->post('code', TRUE);

		$this->load->model('mcode');
		
		$parameter = array(
				'code'			=>	$code,
				'is_get_pack'	=>	0
		);


		if(!empty($result))
		{
			$parameter = array(
					'is_get_pack'	=>	1
			);
			$this->mcode->update($code, $parameter);

			$jsonData = array(
					'success'		=>	true,
					'message'		=>	'PACK_RECEIVE_SUCCESS'
			);
			echo $this->return_format->format($jsonData, $format);
			
			$logParameter = array(
					'account_guid'	=>	$code,
					'account_name'	=>	'',
					'log_action'	=>	'PACK_RECEIVE_SUCCESS'
			);
			$this->logs->write_api($logParameter);
		}
		else
		{
			$jsonData = array(
					'success'		=>	false,
					'error'			=>	'PACK_RECEIVED'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
}

?>