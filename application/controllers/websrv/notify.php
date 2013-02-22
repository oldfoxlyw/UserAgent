<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	const DARK_CRYSTAL_ID='9000004';
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('funds');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
		$this->authKey = $this->config->item('game_auth_key');
	}
	
	public function recharge($format = 'json') {
		$accountId		=	$this->input->get_post('account_id', TRUE);
		$fundsAmount	=	$this->input->get_post('funds_amount', TRUE);
		$fundsItemAmount	=	$this->input->get_post('funds_item_amount', TRUE);
		$gameId			=	$this->input->get_post('game_id', TRUE);
		$serverId		=	$this->input->get_post('server_id', TRUE);
		$sectionId		=	$this->input->get_post('server_section', TRUE);
		$fundsType	=	$this->input->get_post('funds_type', TRUE);
		
		$fundsType = ($fundsType===FALSE || !is_numeric($fundsType)) ? 1 : intval($fundsType);
		
		if(!empty($accountId) && !empty($gameId) && !empty($serverId) && !empty($sectionId) && is_numeric($fundsAmount) && is_numeric($fundsItemAmount)) {
			/*
			 * 检测参数合法性
			*/
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($accountId, $fundsAmount, $fundsItemAmount, $gameId, $sectionId, $serverId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
			if(!$this->param_check->check($check, $authToken)) {
				$jsonData = Array(
						'message'	=>	'PARAM_INVALID'
				);
				exit($this->return_format->format($jsonData, $format));
			}
			/*
			 * 检查完毕
			*/
			$this->load->model('game_account');
			$result = $this->game_account->get($accountId);
			if($result != FALSE) {
				if($fundsType != 0) {
					$fundsItemAmount = intval($fundsItemAmount) < 0 ? -intval($fundsItemAmount) : intval($fundsItemAmount);
				} else {
					$fundsItemAmount = intval($fundsItemAmount);
				}
				$currentCash = intval($result->account_cash);
				$currentCash += $fundsItemAmount;
				$parameter = array(
					'account_cash'	=>	$currentCash
				);
				$this->game_account->update($parameter, $accountId);
				
				$time = time();
				
				$this->load->model('funds');
				$parameter = array(
					'account_guid'				=>	$result->account_guid,
					'account_name'				=>	$result->account_name,
					'account_nickname'			=>	$result->nick_name,
					'account_id'					=>	$accountId,
					'game_id'						=>	$gameId,
					'server_id'						=>	$serverId,
					'server_section'				=>	$sectionId,
					'funds_flow_dir'				=>	'CHECK_IN',
					'funds_amount'				=>	$fundsAmount,
					'funds_item_amount'		=>	$fundsItemAmount,
					'funds_item_current'		=>	$currentCash,
					'funds_time'					=>	$time,
					'funds_time_local'			=>	date('Y-m-d H:i:s', $time),
					'funds_type'					=>	$fundsType
				);
				$this->funds->insert($parameter);
			} else {
				$jsonData = Array(
					'message'	=>	'RECHARGE_ERROR_NO_ACCOUNT_ID'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'NOTIFY_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function buy($format = 'json') {
		$accountId = $this->input->get_post('account_id', TRUE);
		$nickName = $this->input->get_post('nick_name', TRUE);
		$type = $this->input->get_post('type', TRUE);
		$itemSpendId = $this->input->get_post('item_spend_id', TRUE);
		$itemSpendName = urldecode($this->input->get_post('item_spend_name', TRUE));
		$itemSpendCount = $this->input->get_post('item_spend_count', TRUE);
		$itemGetId = $this->input->get_post('item_get_id', TRUE);
		$itemGetName = urldecode($this->input->get_post('item_get_name', TRUE));
		$itemGetCount = $this->input->get_post('item_get_count', TRUE);
		$gameId = $this->input->get_post('game_id', TRUE);
		$sectionId = $this->input->get_post('server_section', TRUE);
		$serverId = $this->input->get_post('server_id', TRUE);
		$isMall = $this->input->get_post('is_mall', TRUE);
		
		$isMall = $isMall === FALSE ? 0 : intval($isMall);
		
		if(!empty($accountId) && !empty($type) && !empty($itemSpendId) &&
		!empty($itemGetId) && $gameId!==FALSE && $serverId!==FALSE &&
		$sectionId!==FALSE) {
			/*
			 * 检测参数合法性
			 */
			$authToken	=	$this->authKey[$gameId]['auth_key'];
			$check = array($accountId, $type, $itemSpendId, $itemSpendName, $itemSpendCount, $itemGetId, $itemGetName, $itemGetCount, $gameId, $sectionId, $serverId);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
			if(!$this->param_check->check($check, $authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				exit($this->return_format->format($jsonData, $format));
			}
			/*
			 * 检查完毕
			 */
			$this->load->model('game_account');
			$result = $this->game_account->get($accountId);
			
			if($result != FALSE) {
				$time = time();
				$this->load->model('websrv/log_mall', 'log_mall');
				if($isMall==1) {
					$itemFee = intval($itemSpendCount) > 0 ? -intval($itemSpendCount) : intval($itemSpendCount);
					$currentCash = intval($result->account_cash);
					$currentCash += $itemFee;
					$parameter = array(
						'account_cash'	=>	$currentCash
					);
					$this->game_account->update($parameter, $accountId);

					$this->load->model('funds');
					$parameter = array(
						'account_guid'			=>	$result->account_guid,
						'account_name'			=>	$result->account_name,
						'account_nickname'		=>	empty($nickName) ? $result->nick_name : $nickName,
						'account_id'				=>	$accountId,
						'game_id'					=>	$gameId,
						'server_id'					=>	$serverId,
						'server_section'			=>	$sectionId,
						'funds_flow_dir'			=>	'CHECK_OUT',
						'funds_item_amount'	=>	$itemFee,
						'funds_item_current'	=>	$currentCash,
						'funds_time'				=>	$time,
						'funds_time_local'		=>	date('Y-m-d H:i:s', $time),
						'funds_type'				=>	0
					);
					$this->funds->insert($parameter);
				}
				$spendIdArray = explode(',', $itemSpendId);
				$getIdArray = explode(',', $itemGetId);
				if(count($spendIdArray) == count($getIdArray) && count($spendIdArray) == 1) {
					if($spendIdArray[0]==DARK_CRYSTAL_ID)
					{
						$itemFee = intval($itemSpendCount) > 0 ? -intval($itemSpendCount) : intval($itemSpendCount);
						$currentCash = intval($result->account_cash);
						$currentCash += $itemFee;
						$parameter = array(
							'account_cash'	=>	$currentCash
						);
						$this->game_account->update($parameter, $accountId);
					}
					$parameter = array(
						'log_account_id'				=>	$accountId,
						'log_account_name'		=>	$result->account_name,
						'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
						'log_type'						=>	$type,
						'log_spend_item_id'		=>	$itemSpendId,
						'log_spend_item_name'	=>	$itemSpendName,
						'log_spend_item_count'	=>	$itemSpendCount,
						'log_get_item_id'				=>	$itemGetId,
						'log_get_item_name'		=>	$itemGetName,
						'log_get_item_count'		=>	$itemGetCount,
						'log_time'						=>	$time,
						'log_local_time'				=>	date('Y-m-d H:i:s', $time),
						'game_id'						=>	$gameId,
						'server_section'				=>	$sectionId,
						'server_id'						=>	$serverId
					);
					$this->log_mall->insert($parameter);
				} elseif (count($spendIdArray) > 1 && count($getIdArray) == 1) {
					$spendNameArray = explode(',', $itemSpendName);
					$spendCountArray = explode(',', $itemSpendCount);
					if(count($spendIdArray)==count($spendNameArray) && count($spendIdArray)==count($spendCountArray)) {
						for($i=0; $i<count($spendIdArray); $i++) {
							if($spendIdArray[$i]==DARK_CRYSTAL_ID)
							{
								$itemFee = intval($spendCountArray[$i]) > 0 ? -intval($spendCountArray[$i]) : intval($spendCountArray[$i]);
								$currentCash = intval($result->account_cash);
								$currentCash += $itemFee;
								$parameter = array(
									'account_cash'	=>	$currentCash
								);
								$this->game_account->update($parameter, $accountId);
							}
							$parameter = array(
								'log_account_id'				=>	$accountId,
								'log_account_name'		=>	$result->account_name,
								'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
								'log_type'						=>	$type,
								'log_spend_item_id'		=>	$spendIdArray[$i],
								'log_spend_item_name'	=>	$spendNameArray[$i],
								'log_spend_item_count'	=>	$spendCountArray[$i],
								//'log_get_item_id'				=>	'',
								//'log_get_item_name'		=>	'',
								//'log_get_item_count'		=>	$itemGetCount,
								'log_time'						=>	$time,
								'log_local_time'				=>	date('Y-m-d H:i:s', $time),
								'game_id'						=>	$gameId,
								'server_section'				=>	$sectionId,
								'server_id'						=>	$serverId
							);
							$this->log_mall->insert($parameter);
						}
						$parameter = array(
							'log_account_id'				=>	$accountId,
							'log_account_name'		=>	$result->account_name,
							'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
							'log_type'						=>	$type,
// 							'log_spend_item_id'		=>	$spendIdArray[$i],
// 							'log_spend_item_name'	=>	$spendNameArray[$i],
// 							'log_spend_item_count'	=>	$spendCountArray[$i],
							'log_get_item_id'				=>	$itemGetId,
							'log_get_item_name'		=>	$itemGetName,
							'log_get_item_count'		=>	$itemGetCount,
							'log_time'						=>	$time,
							'log_local_time'				=>	date('Y-m-d H:i:s', $time),
							'game_id'						=>	$gameId,
							'server_section'				=>	$sectionId,
							'server_id'						=>	$serverId
						);
						$this->log_mall->insert($parameter);
					} else {
						$jsonData = Array(
							'message'	=>	'BUY_ERROR_COUNT_NOT_MATCH'
						);
						echo $this->return_format->format($jsonData, $format);
						exit();
					}
				} elseif (count($spendIdArray) == 1 && count($getIdArray) > 1) {
					if($spendIdArray[0]==DARK_CRYSTAL_ID)
					{
						$itemFee = intval($itemSpendCount) > 0 ? -intval($itemSpendCount) : intval($itemSpendCount);
						$currentCash = intval($result->account_cash);
						$currentCash += $itemFee;
						$parameter = array(
							'account_cash'	=>	$currentCash
						);
						$this->game_account->update($parameter, $accountId);
					}
					$parameter = array(
						'log_account_id'				=>	$accountId,
						'log_account_name'		=>	$result->account_name,
						'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
						'log_type'						=>	$type,
						'log_spend_item_id'		=>	$itemSpendId,
						'log_spend_item_name'	=>	$itemSpendName,
						'log_spend_item_count'	=>	$itemSpendCount,
// 						'log_get_item_id'				=>	$itemGetId,
// 						'log_get_item_name'		=>	$itemGetName,
// 						'log_get_item_count'		=>	$itemGetCount,
						'log_time'						=>	$time,
						'log_local_time'				=>	date('Y-m-d H:i:s', $time),
						'game_id'						=>	$gameId,
						'server_section'				=>	$sectionId,
						'server_id'						=>	$serverId
					);
					$this->log_mall->insert($parameter);
					$getNameArray = explode(',', $itemGetName);
					$getCountArray = explode(',', $itemGetCount);
					if(count($getIdArray)==count($getNameArray) && count($getIdArray)==count($getCountArray)) {
						for($i=0; $i<count($getIdArray); $i++) {
							$parameter = array(
								'log_account_id'				=>	$accountId,
								'log_account_name'		=>	$result->account_name,
								'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
								'log_type'						=>	$type,
// 								'log_spend_item_id'		=>	$spendIdArray[$i],
// 								'log_spend_item_name'	=>	$spendNameArray[$i],
// 								'log_spend_item_count'	=>	$spendCountArray[$i],
								'log_get_item_id'				=>	$getIdArray[$i],
								'log_get_item_name'		=>	$getNameArray[$i],
								'log_get_item_count'		=>	$getCountArray[$i],
								'log_time'						=>	$time,
								'log_local_time'				=>	date('Y-m-d H:i:s', $time),
								'game_id'						=>	$gameId,
								'server_section'				=>	$sectionId,
								'server_id'						=>	$serverId
							);
							$this->log_mall->insert($parameter);
						}
					} else {
						$jsonData = Array(
							'message'	=>	'BUY_ERROR_COUNT_NOT_MATCH'
						);
						echo $this->return_format->format($jsonData, $format);
						exit();
					}
				} else {
					$spendNameArray = explode(',', $itemSpendName);
					$spendCountArray = explode(',', $itemSpendCount);
					if(count($spendIdArray)==count($spendNameArray) && count($spendIdArray)==count($spendCountArray)) {
						for($i=0; $i<count($spendIdArray); $i++) {
							if($spendIdArray[$i]==DARK_CRYSTAL_ID)
							{
								$itemFee = intval($spendCountArray[$i]) > 0 ? -intval($spendCountArray[$i]) : intval($spendCountArray[$i]);
								$currentCash = intval($result->account_cash);
								$currentCash += $itemFee;
								$parameter = array(
									'account_cash'	=>	$currentCash
								);
								$this->game_account->update($parameter, $accountId);
							}
							if(intval($spendCountArray[$i]) > 0) {
								$parameter = array(
										'log_account_id'				=>	$accountId,
										'log_account_name'		=>	$result->account_name,
										'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
										'log_type'						=>	$type,
										'log_spend_item_id'		=>	$spendIdArray[$i],
										'log_spend_item_name'	=>	$spendNameArray[$i],
										'log_spend_item_count'	=>	$spendCountArray[$i],
										//'log_get_item_id'				=>	'',
										//'log_get_item_name'		=>	'',
										//'log_get_item_count'		=>	$itemGetCount,
										'log_time'						=>	$time,
										'log_local_time'				=>	date('Y-m-d H:i:s', $time),
										'game_id'						=>	$gameId,
										'server_section'				=>	$sectionId,
										'server_id'						=>	$serverId
								);
								$this->log_mall->insert($parameter);
							}
						}
					} else {
						$jsonData = Array(
							'message'	=>	'BUY_ERROR_COUNT_NOT_MATCH'
						);
						echo $this->return_format->format($jsonData, $format);
						exit();
					}
					$getNameArray = explode(',', $itemGetName);
					$getCountArray = explode(',', $itemGetCount);
					if(count($getIdArray)==count($getNameArray) && count($getIdArray)==count($getCountArray)) {
						for($i=0; $i<count($getIdArray); $i++) {
							if(intval($getCountArray[$i]) > 0) {
								$parameter = array(
									'log_account_id'				=>	$accountId,
									'log_account_name'		=>	$result->account_name,
									'log_account_nickname'	=>	empty($nickName) ? $result->nick_name : $nickName,
									'log_type'						=>	$type,
	// 								'log_spend_item_id'		=>	$spendIdArray[$i],
	// 								'log_spend_item_name'	=>	$spendNameArray[$i],
	// 								'log_spend_item_count'	=>	$spendCountArray[$i],
									'log_get_item_id'				=>	$getIdArray[$i],
									'log_get_item_name'		=>	$getNameArray[$i],
									'log_get_item_count'		=>	$getCountArray[$i],
									'log_time'						=>	$time,
									'log_local_time'				=>	date('Y-m-d H:i:s', $time),
									'game_id'						=>	$gameId,
									'server_section'				=>	$sectionId,
									'server_id'						=>	$serverId
								);
								$this->log_mall->insert($parameter);
							}
						}
					} else {
						$jsonData = Array(
							'message'	=>	'BUY_ERROR_COUNT_NOT_MATCH'
						);
						echo $this->return_format->format($jsonData, $format);
						exit();
					}
				}
				$jsonData = Array(
					'message'	=>	'BUY_NOTIFY_SUCCESS'
				);
				echo $this->return_format->format($jsonData, $format);
				exit();
			} else {
				$jsonData = Array(
					'message'	=>	'BUY_ERROR_NO_ACCOUNT_ID'
				);
				echo $this->return_format->format($jsonData, $format);
				exit();
			}
		} else {
			$jsonData = Array(
				'message'	=>	'BUY_ERROR_NO_PARAM'
			);
			exit($this->return_format->format($jsonData, $format));
		}
	}
}
?>
