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
					}
					else
					{
						echo json_encode(array(
							'success'	=>	0,
							'error'		=>	'ALREADY_USE_COUPON'
						));
					}
				}
				else
				{
					echo json_encode(array(
						'success'	=>	0,
						'error'		=>	'COUPON_MAX_COUNT'
					));
				}
			}
			else
			{
				echo json_encode(array(
					'success'	=>	0,
					'error'		=>	'NOT_EXIST'
				));
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