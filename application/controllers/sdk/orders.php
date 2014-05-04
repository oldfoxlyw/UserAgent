<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('websrv/order');
	}

	public function init()
	{
		$this->load->model('return_format');

		$serverId = $this->input->get_post('server_id', TRUE);
		$playerId = $this->input->get_post('player_id', TRUE);
		$deviceId = $this->input->get_post('unique_identifier', TRUE);

		$deviceId = empty($deviceId) ? '' : $deviceId;

		if(!empty($serverId) && !empty($playerId))
		{
			$this->load->model('web_account');
			$result = $this->web_account->get($playerId);
			if(!empty($result))
			{
				$this->load->model('funds');
				$parameter = array(
						'account_guid'			=>	$result->GUID,
						'account_name'			=>	$result->account_name,
						'account_nickname'		=>	empty($result->account_nickname) ? '' : $result->account_nickname,
						'account_id'			=>	$playerId,
						'server_id'				=>	$serverId,
						'funds_flow_dir'		=>	'CHECK_IN',
						'funds_amount'			=>	0,
						'funds_item_amount'		=>	0,
						'funds_item_current'	=>	0,
						'funds_time'			=>	$time,
						'funds_time_local'		=>	date('Y-m-d H:i:s', $time),
						'funds_type'			=>	1,
						'partner_key'			=>	$result->partner_key,
						'receipt_data'			=>	'',
						'appstore_status'		=>	-1,
						'appstore_device_id'	=>	$deviceId
				);
				$fundsId = $this->funds->insert($parameter);

				$json = array(
					'success'		=>	1,
					'message'		=>	'ORDER_INITED',
					'id'			=>	$fundsId
				);
			}
			else
			{
				$json = array(
					'success'		=>	0;
					'message'		=>	'ACCOUNT_NOT_EXIST'
				);
			}
		}
		else
		{
			$json = array(
				'success'		=>	0;
				'message'		=>	'PARAM_ERROR'
			);
		}

		echo $this->return_format->format($json);
	}

	public function complete()
	{
		$this->load->model('return_format');

		$fundsAmount = $this->input->get_post('funds_amount', TRUE);
		$itemCount = $this->input->get_post('item_count', TRUE);
		$funds_id = $this->input->get_post('order_id', TRUE)

		if(!empty($funds_id) && !empty($fundsAmount) && !empty($itemCount))
		{
			$this->load->model('funds');
			$result = $this->funds->get($funds_id);

			if($result->appstore_status != '0')
			{
				$account_id = $result->account_id;

				$this->load->model('web_account');
				$account = $this->web_account->get($account_id);

				if(!empty($account))
				{
					$itemCount = intval($itemCount) < 0 ? -intval($itemCount) : intval($itemCount);
					$currentCash = intval($account->account_point);
					$currentCash += $itemCount;
					$parameter = array(
							'account_point'	=>	$currentCash
					);
					$this->web_account->update($parameter, $account_id);

					$parameter = array(
						'funds_amount'			=>	$fundsAmount,
						'funds_item_amount'		=>	$itemCount,
						'funds_item_current'	=>	$currentCash,
						'appstore_status'		=>	0
					);
					$this->funds->update($parameter, $funds_id);
					$json = array(
						'success'		=>	1,
						'message'		=>	'SUCCESS'
					);
				}
				else
				{
					$json = array(
						'success'		=>	0;
						'message'		=>	'ACCOUNT_NOT_EXIST'
					);
				}
			}
			else
			{
				$json = array(
					'success'		=>	0,
					'message'		=>	'ORDER_ALREADY_COMPLETED'
				);
			}
		}
		else
		{
			$json = array(
				'success'		=>	0,
				'message'		=>	'PARAM_ERROR'
			);
		}

		echo $this->return_format->format($json);
	}
}