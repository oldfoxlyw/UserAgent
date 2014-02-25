<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('websrv/order');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
	}
	
	public function check($format = 'json') {
		$accountId = $this->input->get_post('account_id', TRUE);
		$fundsAmount = $this->input->get_post('funds_amount', TRUE);
		$itemCount = $this->input->get_post('item_count', TRUE);
		$serverId = $this->input->get_post('server_id', TRUE);
		$playerId = $this->input->get_post('player_id', TRUE);
		$receiptData = $this->input->post('receipt_data', TRUE);
		$appstoreStatus = $this->input->post('status', TRUE);
		$checkSum = $this->input->get_post('checksum', TRUE);
		
		$receiptData = empty($receiptData) ? '' : $receiptData;
		$appstoreStatus = empty($appstoreStatus) ? -1 : intval($appstoreStatus);
		
		if(!empty($serverId) && !empty($playerId) && !empty($checkSum) && is_numeric($fundsAmount) && is_numeric($itemCount)) {
			$result = $this->order->get($checkSum);
			if($result==FALSE)
			{
				$parameter = array(
					'player_id'		=>	$playerId,
					'server_id'		=>	$serverId,
					'checksum'		=>	$checkSum
				);
				$this->order->insert($parameter);
				
				$jsonData = array(
					'message'		=>	'ORDERS_ADDED'
				);
				
				$this->load->model('web_account');
				$result = $this->web_account->get($playerId);
				if($result != FALSE) {
					$itemCount = intval($itemCount) < 0 ? -intval($itemCount) : intval($itemCount);
					$currentCash = intval($result->account_point);
					$currentCash += $itemCount;
					$parameter = array(
							'account_point'	=>	$currentCash
					);
					$this->web_account->update($parameter, $playerId);
				
					$time = time();
					
					$this->load->model('funds');
					$parameter = array(
							'account_guid'			=>	$result->GUID,
							'account_name'			=>	$result->account_name,
							'account_nickname'		=>	empty($result->account_nickname) ? '' : $result->account_nickname,
							'account_id'			=>	$accountId,
							'server_id'				=>	$serverId,
							'funds_flow_dir'		=>	'CHECK_IN',
							'funds_amount'			=>	$fundsAmount,
							'funds_item_amount'		=>	$itemCount,
							'funds_item_current'	=>	$currentCash,
							'funds_time'			=>	$time,
							'funds_time_local'		=>	date('Y-m-d H:i:s', $time),
							'funds_type'			=>	1,
							'partner_key'			=>	$result->partner_key,
							'receipt_data'			=>	$receiptData,
							'appstore_status'		=>	$appstoreStatus
					);
					$this->funds->insert($parameter);
				} else {
					$jsonData = Array(
							'message'	=>	'RECHARGE_ERROR_NO_ACCOUNT_ID'
					);
					echo $this->return_format->format($jsonData, $format);
					exit();
				}
			} else {
				$this->order->addCount($checkSum);
				$jsonData = array(
					'message'		=>	'ORDERS_EXIST'
				);
			}
		} else {
				$jsonData = array(
					'message'		=>	'ORDERS_NO_PARAM'
				);
		}
		echo $this->return_format->format($jsonData, $format);
	}
	
	public function consume($format = 'json')
	{
		$playerId					=	$this->input->get_post('player_id', TRUE);
		$roleId						=	$this->input->get_post('role_id', TRUE);
		$roleLevel					=	$this->input->get_post('role_level', TRUE);
		$roleMission				=	$this->input->get_post('role_mission', TRUE);
		$actionName					=	$this->input->get_post('action_name', TRUE);
		$currentGold				=	$this->input->get_post('current_gold', TRUE);
		$spendGold					=	$this->input->get_post('spend_gold', TRUE);
		$currentSpecialGold			=	$this->input->get_post('current_special_gold', TRUE);
		$spendSpecialGold			=	$this->input->get_post('spend_special_gold', TRUE);
		$itemName					=	$this->input->get_post('item_name', TRUE);
		$itemInfo					=	$this->input->get_post('item_info', TRUE);
		$itemType					=	$this->input->get_post('item_type', TRUE);
		$itemLevel					=	$this->input->get_post('item_level', TRUE);
		$itemValue					=	$this->input->get_post('item_value', TRUE);
		$itemJob					=	$this->input->get_post('item_job', TRUE);
		$serverId					=	$this->input->get_post('server_id', TRUE);
		
		$logTime = time();
		
		if(!empty($playerId) && !empty($roleId) && !empty($serverId))
		{
			$this->load->model('websrv/consume');
			$this->load->model('web_account');
			$this->load->model('funds');
			
			$currentGold = empty($currentGold) ? 0 : intval($currentGold);
			$spendGold = empty($spendGold) ? 0 : intval($spendGold);
			$roleLevel = empty($roleLevel) ? 0 : intval($roleLevel);
			$roleMission = empty($roleMission) ? '' : $roleMission;
			$actionName = empty($actionName) ? '' : $actionName;
			$itemName = empty($itemName) ? '' : $itemName;
			$itemInfo = empty($itemInfo) ? '' : $itemInfo;
			$itemType = empty($itemType) ? 0 : intval($itemType);
			$itemLevel = empty($itemLevel) ? 0 : intval($itemLevel);
			$itemValue = empty($itemValue) ? 0 : intval($itemValue);
			$itemValue = $itemValue > 4 ? 4 : $itemValue;
			$itemJob = empty($itemJob) ? '' : $itemJob;
			
			$account = $this->web_account->get($playerId);
			if($account !== FALSE)
			{
				$parameter = array(
					'account_point'		=>	$currentSpecialGold
				);
				$this->web_account->update($parameter, $playerId);
				
				$parameter = array(
					'player_id'					=>	$playerId,
					'role_id'					=>	$roleId,
					'role_level'				=>	$roleLevel,
					'role_mission'				=>	$roleMission,
					'action_name'				=>	$actionName,
					'current_gold'				=>	$currentGold,
					'spend_gold'				=>	$spendGold,
					'current_special_gold'		=>	$currentSpecialGold,
					'spend_special_gold'		=>	$spendSpecialGold,
					'item_name'					=>	$itemName,
					'item_info'					=>	$itemInfo,
					'item_type'					=>	$itemType,
					'item_level'				=>	$itemLevel,
					'item_value'				=>	$itemValue,
					'item_job'					=>	$itemJob,
					'log_time'					=>	$logTime,
					'server_id'					=>	$serverId,
					'partner_key'				=>	$account->partner_key
				);
				$this->consume->insert($parameter);
				
				if(intval($spendSpecialGold) > 0)
				{
					$parameter = array(
						'account_guid'				=>	$playerId,
						'account_name'				=>	$account->account_name,
						'account_id'				=>	$roleId,
						'server_id'					=>	$serverId,
						'funds_flow_dir'			=>	'CHECK_OUT',
						'funds_item_amount'			=>	-intval($spendSpecialGold),
						'funds_item_current'		=>	$currentSpecialGold,
						'funds_time'				=>	$logTime,
						'funds_time_local'			=>	date('Y-m-d H:i:s', $logTime),
						'funds_type'				=>	1,
						'partner_key'				=>	$account->partner_key
					);
					$this->funds->insert($parameter);
				}
				
// 				if($actionName == 'buy_equipment')
// 				{
// 					$this->load->model('websrv/equipment_name');
// 					$parameter = array(
// 							'equipment_name'	=>	$itemName,
// 							'type'				=>	$itemInfo
// 					);
// 					$this->equipment_name->insert($parameter);
// 				}
				
				$jsonData = Array(
						'success'	=>	true,
						'message'	=>	'CONSUME_COMPLETE'
				);
			}
			else
			{
				$jsonData = Array(
						'success'	=>	false,
						'message'	=>	'CONSUME_ERROR_ACCOUNT_NOT_EXIST'
				);
			}
		}
		else
		{
			$jsonData = Array(
					'success'	=>	false,
					'message'	=>	'CONSUME_NO_PARAM'
			);
		}
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
