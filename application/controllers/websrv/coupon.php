<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model('return_format');
	}

	public function get_coupon()
	{
		$role_id = $this->input->get_post('role_id');

		if(!empty($role_id))
		{
			$this->load->model('mcoupon');
			$result = $this->mcoupon->read(array(
				'role_id'	=>	$role_id
			));
			if(!empty($result))
			{
				$row = $result[0];
				echo json_encode(array(
					'success'	=>	1,
					'message'	=>	'GET_COUPON_SUCCESS',
					'coupon'	=>	$row->coupon,
					'time'		=>	$row->count
				));
			}
			else
			{
				$coupon = $this->_generate_coupon();
				$data = array(
					'coupon'	=>	$coupon,
					'role_id'	=>	$role_id,
					'count'		=>	0
				);
				if($this->mcoupon->create($data))
				{
					echo json_encode(array(
						'success'	=>	1,
						'message'	=>	'GET_COUPON_SUCCESS',
						'coupon'	=>	$coupon,
						'time'		=>	0
					));
				}
				else
				{
					$coupon = $this->_generate_coupon();
					$data = array(
						'coupon'	=>	$coupon,
						'role_id'	=>	$role_id,
						'count'		=>	0
					);
					if($this->mcoupon->create($data))
					{
						echo json_encode(array(
							'success'	=>	1,
							'message'	=>	'GET_COUPON_SUCCESS',
							'coupon'	=>	$coupon,
							'time'		=>	0
						));
					}
					else
					{
						echo json_encode(array(
							'success'	=>	0,
							'error'		=>	'GET_COUPON_FAIL'
						));
					}
				}
			}
		}
		else
		{
			echo json_encode(array(
				'success'		=>	0,
				'error'			=>	'NO_PARAM'
			));
		}
	}

	public function use_coupon()
	{
		$coupon = $this->input->get_post('coupon');
		$role_id = $this->input->get_post('role_id');

		if(!empty($coupon) && !empty($role_id))
		{
			$this->load->model('mcoupon');
			$this->load->model('mcouponused');

			$result = $this->mcoupon->read(array(
				'coupon'	=>	$coupon
			));
			if(!empty($result))
			{
				$row = $result[0];
				if(intval($row->count) < 10)
				{
					$result = $this->mcouponused->read(array(
						'role_id'	=>	$role_id
					));
					if(empty($result))
					{
						$master_id = intval($row->role_id);
						$master_id = hexdec($master_id);
						$server_id = substr(strval($master_id), 0, 3);
						$this->load->model('mserver');
						$serverResult = $this->mserver->read(array(
							'account_server_id'		=>	$server_id
						));
						if(!empty($serverResult))
						{
							$server = $serverResult[0];
							$server = json_decode($server->server_ip);
							$server = $server[0];
							$this->load->model('webapi/connector');
							$remote_data = $this->connector->post('http://' . $server->lan . ':8089/ser_invitation_times', array(
								'role_id'	=>	$master_id
							));
							if($remote_data == '1')
							{
								$count = intval($row->count) + 1;
								$this->mcoupon->update($coupon, array(
									'count'	=>	$count
								));
								$data = array(
									'role_id'	=>	$role_id,
									'coupon'	=>	$coupon,
									'timestamp'	=>	time()
								);
								$this->mcouponused->create($data);
								echo json_encode(array(
									'success'	=>	1,
									'message'	=>	'USED_COUPON_SUCCESS'
								));
								log_message('info', 'USED_COUPON_SUCCESS');
							}
							else
							{
								echo json_encode(array(
									'success'	=>	1,
									'message'	=>	'REMOTE_DATA_ERROR'
								));
								log_message('error', 'REMOTE_DATA_ERROR: remote data = ' . $remote_data . ', Post URL = ' . 'http://' . $server->lan . ':8089/ser_invitation_times' . ', server_id = ' . $server_id);
							}
						}
						else
						{
							echo json_encode(array(
								'success'	=>	0,
								'error'		=>	'SERVER_ID_ERROR'
							));
							log_message('error', 'SERVER_ID_ERROR: server_id = ' . $server_id);
						}
					}
					else
					{
						echo json_encode(array(
							'success'	=>	0,
							'error'		=>	'ALREADY_USE_COUPON'
						));
						log_message('error', 'ALREADY_USE_COUPON: role_id = ' . $role_id);
					}
				}
				else
				{
					echo json_encode(array(
						'success'	=>	0,
						'error'		=>	'COUPON_MAX_COUNT'
					));
					log_message('error', 'COUPON_MAX_COUNT: coupon = ' . $coupon);
				}
			}
			else
			{
				echo json_encode(array(
					'success'	=>	0,
					'error'		=>	'NOT_EXIST'
				));
				log_message('error', 'NOT_EXIST: coupon = ' . $coupon);
			}
		}
		else
		{
			echo json_encode(array(
				'success'	=>	0,
				'error'		=>	'NO_PARAM'
			));
		}
	}

	public function shared_callback()
	{
		$role_id = $this->input->post('role_id');
		if(!empty($role_id))
		{

		}
	}

	private function _generate_coupon()
	{
		$width = 8;
		$chars = array('1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z');
		$this->load->helper('array');

		$coupon = '';
		for($j = 0; $j<$width; $j++)
		{
			$coupon .= random_element($chars);
		}

		return $coupon;
	}
}

?>