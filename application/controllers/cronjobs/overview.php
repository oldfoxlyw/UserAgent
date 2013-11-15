<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Overview extends CI_Controller
{
	private $accountdb = null;
	private $logcachedb = null;
	private $logdb = null;
	private $fundsdb = null;

	public function __construct()
	{
		parent::__construct ();
		$this->accountdb = $this->load->database ( 'accountdb', true );
		$this->logcachedb = $this->load->database ( 'log_cachedb', true );
		$this->logdb = $this->load->database ( 'logdb', true );
		$this->fundsdb = $this->load->database ( 'fundsdb', true );
	}

	public function statistics()
	{
		$this->load->model ( 'websrv/server' );
		$serverResult = $this->server->getAllResult ();
		
		$this->load->model ( 'websrv/mpartner' );
		$partnerResult = $this->mpartner->getAllResult ();
		
		$this->process ( $serverResult, $partnerResult );
	}

	private function process($serverResult, $partnerResult = null)
	{
		$currentTimeStamp = time ();
		$currentDate = date ( 'Y-m-d', $currentTimeStamp );
		$lastTimeStart = strtotime ( $currentDate . ' 00:00:00' ) - 86400;
		$lastTimeEnd = strtotime ( $currentDate . ' 23:59:59' ) - 86400;
		$date = date ( 'Y-m-d', $lastTimeStart );
		$preDate = date ( 'Y-m-d', $lastTimeStart - 86400 );
		
		foreach ( $serverResult as $row )
		{
			foreach ( $partnerResult as $partner )
			{
				$partnerKey = $partner->partner_key;
				
				// 总注册数
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$registerCount = $this->accountdb->count_all_results ( 'web_account' );
				
				// 新注册数
				$where = "`server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$lastTimeStart} and `account_regtime` <= {$lastTimeEnd}";
				$this->accountdb->where ( $where );
				$regNewCount = $this->accountdb->count_all_results ( 'web_account' );
				
				// 有效帐号（建立角色的帐号）
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$this->accountdb->where ( 'account_level >', 0 );
				$validCount = $this->accountdb->count_all_results ( 'web_account' );

				// 等级大于1的帐号
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$this->accountdb->where ( 'account_level >', 1 );
				$levelCount = $this->accountdb->count_all_results ( 'web_account' );
				
				// 总改名用户数
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'account_status', 1 );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$modifyCount = $this->accountdb->count_all_results ( 'web_account' );

				// 昨日改名用户数
				$this->logcachedb->where ( 'log_date', $preDate );
				$this->logcachedb->where ( 'server_id', $row->account_server_id );
				$this->logcachedb->where ( 'partner_key', $partnerKey );
				$lastResult = $this->logcachedb->get ( 'log_daily_statistics' );
				if (! empty ( $lastResult ))
				{
					$lastResult = $lastResult->row ();
					$lastModifyAccount = intval ( $lastResult->modify_account );
				} else
				{
					$lastModifyAccount = 0;
				}

				// 新改名用户数
				$modifyNewCount = $modifyCount - $lastModifyAccount;
				
				// 当天活跃玩家数(登录数)
				$this->logdb->select( 'log_GUID' );
				$this->logdb->where ( 'log_action', 'ACCOUNT_LOGIN_SUCCESS' );
				$this->logdb->where ( 'log_time >=', $lastTimeStart );
				$this->logdb->where ( 'log_time <=', $lastTimeEnd );
				$this->logdb->where ( 'server_id', $row->account_server_id );
				$this->logdb->where ( 'partner_key', $partnerKey );
				$this->logdb->group_by ( 'log_GUID' );
				$loginCount = $this->logdb->get ( 'log_account' );
				$loginCount = $loginCount->num_rows();

				// 活跃玩家数(三天以内登录过游戏的人数)
				$threeDaysAgoStart = $lastTimeStart - 2 * 86400;
				$this->accountdb->where ( 'account_lastlogin >=', $threeDaysAgoStart );
				$this->accountdb->where ( 'account_lastlogin <=', $lastTimeEnd );
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$activeCount = $this->accountdb->count_all_results ( 'web_account' );
				
				// 回流玩家数(超过一周没有登录但最近有登录的玩家数)
				$this->logcachedb->where ( 'server_id', $row->account_server_id );
				$this->logcachedb->where ( 'partner_key', $partnerKey );
				$query = $this->logcachedb->get ( 'log_flowover_cache' );
				$guidArray = $query->result ();
				$flowoverCacheResult = array ();
				foreach ( $guidArray as $guid )
				{
					array_push ( $flowoverCacheResult, $guid->guid );
				}
				$reflowCount = 0;
				if (! empty ( $guidArray ))
				{
					$threeDaysAgoStart = $lastTimeStart - 2 * 86400;
					$this->accountdb->where ( 'account_lastlogin >=', $threeDaysAgoStart );
					$this->accountdb->where ( 'account_lastlogin <=', $lastTimeEnd );
					$this->accountdb->where ( 'server_id', $row->account_server_id );
					$this->accountdb->where ( 'partner_key', $partnerKey );
					$this->accountdb->where_in ( 'GUID', $flowoverCacheResult );
					$reflowCount = $this->accountdb->count_all_results ( 'web_account' );
				}
				$this->logcachedb->delete ( 'log_flowover_cache', array (
					'server_id' => $row->account_server_id,
					'partner_key' => $partnerKey 
				) );
				
				// 流失玩家数(超过一周没有登录的玩家数)
				$weekAgoStart = $lastTimeStart - 6 * 86400;
				$this->accountdb->where ( 'account_lastlogin <=', $weekAgoStart );
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$flowoverCount = $this->accountdb->count_all_results ( 'web_account' );
				// 流失玩家放入临时表
				$this->accountdb->where ( 'account_lastlogin <=', $weekAgoStart );
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$query = $this->accountdb->get ( 'web_account' );
				$flowoverResult = $query->result ();
				foreach ( $flowoverResult as $flowover )
				{
					$this->logcachedb->insert ( 'log_flowover_cache', array (
						'guid' => $flowover->GUID,
						'server_id' => $row->account_server_id,
						'account_job' => empty ( $flowover->account_job ) ? '' : $flowover->account_job,
						'account_level' => empty ( $flowover->account_level ) ? 0 : $flowover->account_level,
						'account_mission' => empty ( $flowover->account_mission ) ? 0 : $flowover->account_mission,
						'partner_key' => $partnerKey 
					) );
				}
				
				// 当天订单数
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'funds_time >', $lastTimeStart );
				$this->fundsdb->where ( 'funds_time <=', $lastTimeEnd );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$ordersCount = $this->fundsdb->count_all_results ( 'funds_checkinout' );
				
				// 当天订单总额
				$this->fundsdb->select_sum ( 'funds_amount' );
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'funds_time >', $lastTimeStart );
				$this->fundsdb->where ( 'funds_time <=', $lastTimeEnd );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$checkResult = $this->fundsdb->get ( 'funds_checkinout' )->row ();
				$ordersCurrentSum = intval ( $checkResult->funds_amount );
				
				// 订单总额
				$this->fundsdb->select_sum ( 'funds_amount' );
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$checkResult = $this->fundsdb->get ( 'funds_checkinout' )->row ();
				$ordersSum = intval ( $checkResult->funds_amount );
				
				// 当天充值人数
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'funds_time >', $lastTimeStart );
				$this->fundsdb->where ( 'funds_time <=', $lastTimeEnd );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$this->fundsdb->group_by ( 'account_guid' );
				$rechargeAccount = $this->fundsdb->get ( 'funds_checkinout' );
				$rechargeAccount = $rechargeAccount->num_rows();
				
				// arpu
				$arpu = floatval ( number_format ( $rechargeAccount / $activeCount, 2 ) ) * 100;
				
				$parameter = array (
					'log_date' => $date,
					'server_id' => $row->account_server_id,
					'server_name' => $row->server_name,
					'reg_account' => $registerCount,
					'reg_new_account' => $regNewCount,
					'valid_account' => $validCount,
					'level_account' => $levelCount,
					'modify_account' => $modifyCount,
					'modify_new_account' => $modifyNewCount,
					'login_account' => $loginCount,
					'active_account' => $activeCount,
					'flowover_account' => $flowoverCount,
					'reflow_account' => $reflowCount,
					'orders_current_sum' => $ordersCurrentSum,
					'orders_sum' => $ordersSum,
					'arpu' => $arpu,
					'recharge_account' => $rechargeAccount,
					'order_count' => $ordersCount,
					'partner_key' => $partnerKey 
				);
				$this->logcachedb->insert ( 'log_daily_statistics', $parameter );
				
				$this->flowover_detail_statistics ( $date, $row->account_server_id, $partnerKey );
				$this->buy_equipment_statistics ( $date, $row->account_server_id, $partnerKey );
			}
		}
	}

	private function flowover_detail_statistics($date, $server_id, $partnerKey)
	{
		$sql = "SELECT `account_job`, COUNT(*) AS `numrows` FROM `log_flowover_cache` WHERE `server_id` = '{$server_id}' and `partner_key` = '{$partnerKey}' GROUP BY `account_job`";
		$countResult = $this->logcachedb->query ( $sql )->result ();
		$jobArray = array ();
		foreach ( $countResult as $row )
		{
			array_push ( $jobArray, $row->account_job . ':' . $row->numrows );
		}
		$jobResult = implode ( ',', $jobArray );
		
		$sql = "SELECT `account_level`, COUNT(*) AS `numrows` FROM `log_flowover_cache` WHERE `server_id` = '{$server_id}' and `partner_key` = '{$partnerKey}' GROUP BY `account_level`";
		$countResult = $this->logcachedb->query ( $sql )->result ();
		$levelArray = array ();
		foreach ( $countResult as $row )
		{
			array_push ( $levelArray, $row->account_level . ':' . $row->numrows );
		}
		$levelResult = implode ( ',', $levelArray );
		
		$sql = "SELECT `account_mission`, COUNT(*) AS `numrows` FROM `log_flowover_cache` WHERE `server_id` = '{$server_id}' and `partner_key` = '{$partnerKey}' GROUP BY `account_mission`";
		$countResult = $this->logcachedb->query ( $sql )->result ();
		$missionArray = array ();
		foreach ( $countResult as $row )
		{
			array_push ( $missionArray, $row->account_mission . ':' . $row->numrows );
		}
		$missionResult = implode ( ',', $missionArray );
		
		$parameter = array (
			'date' => $date,
			'server_id' => $server_id,
			'job' => $jobResult,
			'level' => $levelResult,
			'mission' => $missionResult,
			'partner_key' => $partnerKey 
		);
		$this->logcachedb->insert ( 'log_flowover_detail', $parameter );
	}

	public function buy_equipment_statistics($date, $server_id, $partnerKey)
	{
		$levelDetail = array();
		$missionDetail = array();
		$timeStart = strtotime("{$date} 00:00:00");
		$timeEnd = strtotime("{$date} 23:59:59");
		for($m=1; $m<=6; $m++)
		{
			$levelDetail[strval($m)] = '';
			$sql = "SELECT `role_level`, COUNT(*) AS `count` FROM `log_consume` WHERE `action_name` = 'buy_equipment' AND `item_info` = {$m} AND `server_id` = '{$server_id}' AND `partner_key` = '{$partnerKey}' AND `log_time` >= {$timeStart} AND `log_time` <= {$timeEnd} GROUP BY `role_level`";
			$result = $this->logdb->query ( $sql )->result_array ();
			if (! empty ( $result ))
			{
				for ( $i=0; $i<count($result); $i++ )
				{
					$result[$i] = implode(':', $result[$i]);
				}
				$levelDetail[strval($m)] = implode(',', $result);
			}
		}
		for($m=1; $m<=6; $m++)
		{
			$missionDetail[strval($m)] = '';
			$sql = "SELECT `role_mission`, COUNT(*) AS `count` FROM `log_consume` WHERE `action_name` = 'buy_equipment' AND `item_info` = {$m} AND `server_id` = '{$server_id}' AND `partner_key` = '{$partnerKey}' AND `log_time` >= {$timeStart} AND `log_time` <= {$timeEnd} GROUP BY `role_mission`";
			$result = $this->logdb->query ( $sql )->result_array ();
			if (! empty ( $result ))
			{
				for ( $i=0; $i<count($result); $i++ )
				{
					$result[$i] = implode(':', $result[$i]);
				}
				$missionDetail[strval($m)] = implode(',', $result);
			}
		}
		
		$parameter = array(
			'date'					=>	$date,
			'server_id'				=>	$server_id,
			'partner_key'			=>	$partnerKey,
			'level_detail'			=>	json_encode($levelDetail),
			'mission_detail'	=>	json_encode($missionDetail)
		);
		$this->logcachedb->insert('log_buy_equipment_detail', $parameter);
	}
	
	public function retention_statistics()
	{
		$this->load->model ( 'websrv/server' );
		$serverResult = $this->server->getAllResult ();
		
		$this->load->model ( 'websrv/mpartner' );
		$partnerResult = $this->mpartner->getAllResult ();
		
		$currentTimeStamp = time ();
		$currentDate = date ( 'Y-m-d', $currentTimeStamp );
		//昨日
		$lastTimeStart = strtotime ( $currentDate . ' 00:00:00' ) - 86400;
		$lastTimeEnd = strtotime ( $currentDate . ' 23:59:59' ) - 86400;
		//前日
		$prevTimeStart = $lastTimeStart - 86400;
		$prevTimeEnd = $lastTimeEnd - 86400;
		//三天前
		$thirdTimeStart = $lastTimeStart - 2 * 86400;
		$thirdTimeEnd = $lastTimeEnd - 2 * 86400;

		foreach ( $serverResult as $row )
		{
			foreach ( $partnerResult as $partner )
			{
				$partnerKey = $partner->partner_key;
				
				//昨日注册数
				$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`>1 AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$registerCount = $this->accountdb->query ( $sql )->row();
				$registerCount = $registerCount->numrows;
				//今天登录数
				$sql = "SELECT `log_GUID` as `numrows` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$currentLogin = $this->logdb->query($sql)->num_rows();
				
				$nextRetention = floor(($currentLogin / $registerCount) * 10000);
				
				//三天前注册数
				$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$thirdTimeStart} AND `account_regtime`<={$thirdTimeEnd} AND `account_level`>1 AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$thirdRegisterCount = $this->accountdb->query ( $sql )->row();
				$thirdRegisterCount = $thirdRegisterCount->numrows;
				//今天登录数
				$sql = "SELECT `log_GUID` as `numrows` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$thirdTimeStart} AND `account_regtime`<={$thirdTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$thirdCurrentLogin = $this->logdb->query($sql)->num_rows();
				
				//今天内等级为1的账户数
				$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`=1 AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$level1 = $this->accountdb->query ( $sql )->row();
				$level1 = $level1->numrows;
				
				$thirdRetention = floor(($thirdCurrentLogin / $thirdRegisterCount) * 10000);
				
				$parameter = array(
						'log_date'				=>	date('Y-m-d', $lastTimeStart),
						'server_id'				=>	$row->account_server_id,
						'partner_key'			=>	$partnerKey,
						'prev_register'			=>	$registerCount,
						'prev_current_login'	=>	$currentLogin,
						'next_retention'		=>	$nextRetention,
						'third_register'		=>	$thirdRegisterCount,
						'third_current_login'	=>	$thirdCurrentLogin,
						'third_retention'		=>	$thirdRetention,
						'level1'				=>	$level1
				);
				$this->logcachedb->insert('log_retention', $parameter);
			}
		}
	}
}
?>
