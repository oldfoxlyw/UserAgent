<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	private $appkey = 'e024d44d86d6d701ecac8ef23c8e8b1bcd7bc0d8';
	
	public function __construct() {
		parent::__construct();
		$this->load->model('return_format');
	}

	public function init($format = 'json')
	{
		$server_id = $this->input->get_post('serv_id', TRUE);
		$player_id = $this->input->get_post('player_id', TRUE);
		$order_id = $this->input->get_post('order_id', TRUE);
		$amount = $this->input->get_post('money', TRUE);
		$create_time = $this->input->get_post('create_time', TRUE);
		$sign = $this->input->get_post('sign', TRUE);

		$check = array($this->appkey . $server_id . $nickname . $order_id . $amount . $create_time . $this->appkey);
		$check = md5(implode('', $check));
		if($sign == $check)
		{
			$this->load->model('maccount');

			$parameter = array(
				'GUID'			=>	$player_id
			);
			$result = $this->maccount->read($parameter);

			if(!empty($result))
			{
				$account = $result[0];

				$this->load->model('morder');
				$parameter = array(
					'account_guid'		=>	$account->GUID,
					'account_name'		=>	$account->account_name,
					'account_nickname'	=>	$account->account_nickname,
					'server_id'			=>	$server_id,
					'funds_flow_dir'	=>	'CHECK_IN',
					'funds_amount'		=>	floatval($amount) * 100,
					'funds_time'		=>	$create_time,
					'funds_time_local'	=>	date('Y-m-d H:i:s', $create_time),
					'funds_type'		=>	1,
					'partner_key'		=>	'arab_sdk',
					'appstore_status'	=>	-1,
					'order_id'			=>	$order_id
				);
				$id = $this->morder->create($parameter);

				$jsonData = array(
					'err_code'		=>	0,
					'desc'			=>	'success',
					'order_id'		=>	$id
				);
			}
			else
			{
				$jsonData = array(
					'err_code'		=>	1,
					'desc'			=>	'Account not exist'
				);
			}
		}
		else
		{
			$jsonData = array(
				'err_code'			=>	1,
				'desc'				=>	'sign invalid'
			);
		}

		echo $this->return_format->format($jsonData, $format);
	}

	public function notify($format = 'json')
	{
		$player_id = $this->input->get_post('player_id', TRUE);
		$apporderid = $this->input->get_post('apporderid', TRUE);
		$appmoney = $this->input->get_post('appmoney', TRUE);
		$money = $this->input->get_post('money', TRUE);
		$createtime = $this->input->get_post('createtime', TRUE);
		$sign = $this->input->get_post('sign', TRUE);

		$check = array($this->appkey . $player_id . $apporderid . $appmoney . $money . $createtime . $this->appkey);
		$check = md5(implode('', $check));
		if($sign == $check)
		{
			$this->load->model('morder');

			$parameter = array(
				'funds_id'		=>	$apporderid
			);
			$result = $this->morder->read($parameter);
			if(!empty($result))
			{
				$order = $result[0];

				if($order->appstore_status != '0')
				{
					$this->load->model('maccount');

					$parameter = array(
						'GUID'			=>	$player_id
					);
					$result = $this->maccount->read($parameter);

					if(!empty($result))
					{
						$account = $result[0];

						$this->load->model('mserver');
						$parameter = array(
							'account_server_id'		=>	$account->server_id
						);
						$result = $this->mserver->read($parameter);

						if(!empty($result))
						{
							$server = $result[0];

							$server = json_decode($server->server_ip);
							$url = 'http://' . $server->ip . ':' . $server->port . '/platform_payment_notification';

							//通知对应服务器
							$this->load->model('webapi/connector');
							$parameter = array(
								'billno'	=>	$apporderid,
								'nickname'	=>	$account->account_nickname,
								'pay_money'	=>	$money
							);
							$this->connector->post($url, $parameter);

							$parameter = array(
								'funds_time'		=>	$createtime,
								'funds_time_local'	=>	date('Y-m-d H:i:s', $createtime),
								'appstore_status'	=>	0
							);
							$this->morder->update($apporderid, $parameter);

							$jsonData = array(
								'success'		=>	1,
								'desc'			=>	$order->order_id
							);
						}
						else
						{
							$jsonData = array(
								'success'		=>	0,
								'desc'			=>	'Server not exist'
							);
						}
					}
					else
					{
						$jsonData = array(
							'success'		=>	0,
							'desc'			=>	'Account not exist'
						);
					}
				}
				else
				{
					$jsonData = array(
						'success'		=>	0,
						'desc'			=>	'Order already completed'
					);
				}
			}
			else
			{
				$jsonData = array(
					'success'		=>	0,
					'desc'			=>	'Order not exist'
				);
			}
		}
		else
		{
			$jsonData = array(
				'success'			=>	0,
				'desc'				=>	'sign invalid'
			);
		}

		echo $this->return_format->format($jsonData, $format);
	}
}
?>
