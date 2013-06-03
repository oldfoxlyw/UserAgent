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
		$gameId = $this->input->get_post('game_id', TRUE);
		$sectionId = $this->input->get_post('server_section', TRUE);
		$serverId = $this->input->get_post('server_id', TRUE);
		$playerId = $this->input->get_post('player_id', TRUE);
		$checkSum = $this->input->get_post('checksum', TRUE);
		
		if(!empty($gameId) && !empty($sectionId) && !empty($serverId) && !empty($playerId) && !empty($checkSum) && is_numeric($fundsAmount) && is_numeric($itemCount)) {
			$result = $this->order->get($checkSum);
			if($result==FALSE)
			{
				$parameter = array(
					'player_id'		=>	$playerId,
					'game_id'		=>	$gameId,
					'section_id'	=>	$sectionId,
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
							'account_guid'				=>	$result->GUID,
							'account_name'				=>	$result->account_name,
							'account_nickname'			=>	empty($result->account_nickname) ? '' : $result->account_nickname,
							'account_id'				=>	$accountId,
							// 'game_id'					=>	$gameId,
							'server_id'					=>	$serverId,
							// 'server_section'			=>	$sectionId,
							'funds_flow_dir'			=>	'CHECK_IN',
							'funds_amount'				=>	$fundsAmount,
							'funds_item_amount'			=>	$itemCount,
							'funds_item_current'		=>	$currentCash,
							'funds_time'				=>	$time,
							'funds_time_local'			=>	date('Y-m-d H:i:s', $time),
							'funds_type'				=>	1
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
}
?>
