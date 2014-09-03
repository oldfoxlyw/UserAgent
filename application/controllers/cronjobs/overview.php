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

	public function statistics($server_id)
	{
		set_time_limit(3600);

		$this->load->model ( 'websrv/server' );
		if(!empty($server_id))
		{
			$parameter = array(
					'account_server_id'		=>	$server_id
			);
		}
		else
		{
			$parameter = array(
					'server_debug'	=>	0
			);
		}
		$serverResult = $this->server->getAllResult ($parameter);
		
		$this->load->model ( 'websrv/mpartner' );
		$partnerResult = $this->mpartner->getAllResult ();
		
		$this->process ( $serverResult, $partnerResult );
	}

	private function process($serverResult, $partnerResult = null)
	{
		$lastDate = $this->input->get('date', TRUE);
		
		if(!empty($lastDate))
		{
			$lastTimeStart = strtotime ( $lastDate . ' 00:00:00' );
			$lastTimeEnd = strtotime ( $lastDate . ' 23:59:59' );
		}
		else
		{
			$currentTimeStamp = time ();
			$currentDate = date ( 'Y-m-d', $currentTimeStamp );
			$lastTimeStart = strtotime ( $currentDate . ' 00:00:00' ) - 86400;
			$lastTimeEnd = strtotime ( $currentDate . ' 23:59:59' ) - 86400;
		}
		$date = date ( 'Y-m-d', $lastTimeStart );
		$preDate = date ( 'Y-m-d', $lastTimeStart - 86400 );
		
		foreach ( $serverResult as $row )
		{
			foreach ( $partnerResult as $partner )
			{
				$partnerKey = $partner->partner_key;
				
				log_message('custom', 'partner = ' . $partnerKey);
				// 总注册数
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$registerCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'registerCount = ' . $registerCount);
				
				// 新注册数
				$where = "`server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$lastTimeStart} and `account_regtime` <= {$lastTimeEnd}";
				$this->accountdb->where ( $where );
				$regNewCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'regNewCount = ' . $regNewCount);
				
				// 有效帐号（建立角色的帐号）
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				// $this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
				// $this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$this->accountdb->where ( 'account_level >', 0 );
				$validCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'validCount = ' . $validCount);
				
				// 新有效帐号（建立角色的帐号）
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$this->accountdb->where ( 'account_level >', 0 );
				$validNewCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'validNewCount = ' . $validNewCount);

				// 等级大于1的帐号
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$this->accountdb->where ( 'account_level >', 1 );
				$levelCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'levelCount = ' . $levelCount);
				
				// 总改名用户数
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'account_status', 1 );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$modifyCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'modifyCount = ' . $modifyCount);
				
				// 新改名用户数
				$where = "`server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_status`=1 and `account_regtime` >= {$lastTimeStart} and `account_regtime` <= {$lastTimeEnd}";
				$this->accountdb->where ( $where );
				$modifyNewCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'modifyNewCount = ' . $modifyNewCount);

				// 当天登录数
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE (`log_action` = 'ACCOUNT_LOGIN_SUCCESS' OR `log_action` = 'ACCOUNT_REGISTER_SUCCESS' OR `log_action` = 'ACCOUNT_DEMO_SUCCESS') AND `log_time` >= {$lastTimeStart} AND `log_time` <= {$lastTimeEnd} AND `server_id` = '{$row->account_server_id}' AND `partner_key` = '{$partnerKey}' GROUP BY `log_GUID`";
				$loginCount = $this->logdb->query($sql)->num_rows();
				log_message('custom', 'loginCount = ' . $loginCount);

				// 当天有效登录数
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE (`log_action` = 'ACCOUNT_LOGIN_SUCCESS' OR `log_action` = 'ACCOUNT_REGISTER_SUCCESS' OR `log_action` = 'ACCOUNT_DEMO_SUCCESS') AND `log_time` >= {$lastTimeStart} AND `log_time` <= {$lastTimeEnd} AND `server_id` = '{$row->account_server_id}' AND `partner_key` = '{$partnerKey}' AND `log_account_level` > 0 GROUP BY `log_GUID`";
				$loginValidCount = $this->logdb->query($sql)->num_rows();
				log_message('custom', 'loginValidCount = ' . $loginValidCount);

				// 活跃玩家数(三天以内登录过游戏的人数)
				$threeDaysAgoStart = $lastTimeStart - 2 * 86400;
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE (`log_action` = 'ACCOUNT_LOGIN_SUCCESS' OR `log_action` = 'ACCOUNT_REGISTER_SUCCESS' OR `log_action` = 'ACCOUNT_DEMO_SUCCESS') AND `log_time` >= {$threeDaysAgoStart} AND `log_time` <= {$lastTimeEnd} AND `server_id` = '{$row->account_server_id}' AND `partner_key` = '{$partnerKey}' GROUP BY `log_GUID`";
				$activeCount = $this->logdb->query($sql)->num_rows();
				log_message('custom', 'activeCount = ' . $activeCount);

				//DAU
				$dau = $loginCount - $validNewCount;
				log_message('custom', 'dau = ' . $dau);
				// $dau = $loginValidCount - $validNewCount;
				
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
					log_message('custom', 'reflowCount = ' . $reflowCount);
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
				log_message('custom', 'flowoverCount = ' . $flowoverCount);
				// 流失玩家放入临时表
				$this->accountdb->where ( 'account_lastlogin <=', $weekAgoStart );
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$query = $this->accountdb->get ( 'web_account' );
				$flowoverResult = $query->result ();
				log_message('custom', 'flowoverResult = ' . count($flowoverResult));
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
				$query->free_result();
				
				// 当天订单数
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'funds_time >=', $lastTimeStart );
				$this->fundsdb->where ( 'funds_time <=', $lastTimeEnd );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$this->fundsdb->where ( 'appstore_status', 0 );
				$ordersCount = $this->fundsdb->count_all_results ( 'funds_checkinout' );
				log_message('custom', 'ordersCount = ' . $ordersCount);
				
				// 当天订单总额
				$this->fundsdb->select_sum ( 'funds_amount' );
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'funds_time >=', $lastTimeStart );
				$this->fundsdb->where ( 'funds_time <=', $lastTimeEnd );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$this->fundsdb->where ( 'appstore_status', 0 );
				$query = $this->fundsdb->get ( 'funds_checkinout' );
				$checkResult = $query->row ();
				$ordersCurrentSum = intval ( $checkResult->funds_amount );
				$query->free_result();
				log_message('custom', 'ordersCurrentSum = ' . $ordersCurrentSum);
				
				// 订单总额
				$this->fundsdb->select_sum ( 'funds_amount' );
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$this->fundsdb->where ( 'appstore_status', 0 );
				$query = $this->fundsdb->get ( 'funds_checkinout' );
				$checkResult = $query->row ();
				$ordersSum = intval ( $checkResult->funds_amount );
				$query->free_result();
				log_message('custom', 'ordersSum = ' . $ordersSum);
				
				// 当天充值人数
				$this->fundsdb->where ( 'funds_flow_dir', 'CHECK_IN' );
				$this->fundsdb->where ( 'funds_time >=', $lastTimeStart );
				$this->fundsdb->where ( 'funds_time <=', $lastTimeEnd );
				$this->fundsdb->where ( 'server_id', $row->account_server_id );
				$this->fundsdb->where ( 'partner_key', $partnerKey );
				$this->fundsdb->where ( 'appstore_status', 0 );
				$this->fundsdb->group_by ( 'account_guid' );
				$query = $this->fundsdb->get ( 'funds_checkinout' );
				$rechargeAccount = $query->num_rows();
				$query->free_result();
				log_message('custom', 'rechargeAccount = ' . $rechargeAccount);
				
				// arpu
				if($dau > 0)
				{
					$arpu = floatval ( number_format ( $rechargeAccount / $dau, 4 ) ) * 10000;
				}
				else
				{
					$arpu = 0;
				}
				log_message('custom', 'arpu = ' . $arpu);
				
				// at 平均在线时长
				$sql = "SELECT SUM(`time`) as `time` FROM `log_rep` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `posttime`>={$lastTimeStart} AND `posttime`<={$lastTimeEnd}";
				$query = $this->logdb->query($sql);
				$atSum = $query->row();
				$query->free_result();
				if(empty($atSum))
				{
					$at = 0;
				}
				else
				{
					$atSum = $atSum->time;
					$sql = "SELECT * FROM `log_rep` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `posttime`>={$lastTimeStart} AND `posttime`<={$lastTimeEnd}";
					$query = $this->logdb->query($sql);
					$atCount = $query->num_rows();
					$query->free_result();
					if($atCount > 0)
					{
						$at = $atSum / $atCount;
					}
					else
					{
						$at = 0;
					}
				}
				log_message('custom', 'at = ' . $at);
				
				$parameter = array (
					'log_date' => $date,
					'server_id' => $row->account_server_id,
					'server_name' => $row->server_name,
					'reg_account' => $registerCount,
					'reg_new_account' => $regNewCount,
					'valid_account' => $validCount,
					'valid_new_account' => $validNewCount,
					'level_account' => $levelCount,
					'modify_account' => $modifyCount,
					'modify_new_account' => $modifyNewCount,
					'login_account' => $loginCount,
					'login_account_valid' => $loginValidCount,
					'active_account' => $activeCount,
					'dau' => $dau,
					'flowover_account' => $flowoverCount,
					'reflow_account' => $reflowCount,
					'orders_current_sum' => $ordersCurrentSum,
					'orders_sum' => $ordersSum,
					'arpu' => $arpu,
					'recharge_account' => $rechargeAccount,
					'order_count' => $ordersCount,
					'at' => $at,
					'partner_key' => $partnerKey 
				);
				$this->logcachedb->insert ( 'log_daily_statistics', $parameter );
				log_message('custom', 'insert log_daily_statistics');
				
				$this->flowover_detail_statistics ( $date, $row->account_server_id, $partnerKey );
				$this->buy_equipment_statistics ( $date, $row->account_server_id, $partnerKey );
			}
		}
	}

	private function flowover_detail_statistics($date, $server_id, $partnerKey)
	{
		$sql = "SELECT `account_job`, COUNT(*) AS `numrows` FROM `log_flowover_cache` WHERE `server_id` = '{$server_id}' and `partner_key` = '{$partnerKey}' GROUP BY `account_job`";
		$query = $this->logcachedb->query ( $sql );
		$countResult = $query->result ();
		$jobArray = array ();
		foreach ( $countResult as $row )
		{
			array_push ( $jobArray, $row->account_job . ':' . $row->numrows );
		}
		$jobResult = implode ( ',', $jobArray );
		$query->free_result();
		log_message('custom', 'jobResult = ' . $jobResult);
		
		$sql = "SELECT `account_level`, COUNT(*) AS `numrows` FROM `log_flowover_cache` WHERE `server_id` = '{$server_id}' and `partner_key` = '{$partnerKey}' GROUP BY `account_level`";
		$query = $this->logcachedb->query ( $sql );
		$countResult = $query->result ();
		$levelArray = array ();
		foreach ( $countResult as $row )
		{
			array_push ( $levelArray, $row->account_level . ':' . $row->numrows );
		}
		$levelResult = implode ( ',', $levelArray );
		$query->free_result();
		log_message('custom', 'levelResult = ' . $levelResult);
		
		$sql = "SELECT `account_mission`, COUNT(*) AS `numrows` FROM `log_flowover_cache` WHERE `server_id` = '{$server_id}' and `partner_key` = '{$partnerKey}' GROUP BY `account_mission`";
		$query = $this->logcachedb->query ( $sql );
		$countResult = $query->result ();
		$missionArray = array ();
		foreach ( $countResult as $row )
		{
			array_push ( $missionArray, $row->account_mission . ':' . $row->numrows );
		}
		$missionResult = implode ( ',', $missionArray );
		$query->free_result();
		log_message('custom', 'missionResult = ' . $missionResult);
		
		$parameter = array (
			'date' => $date,
			'server_id' => $server_id,
			'job' => $jobResult,
			'level' => $levelResult,
			'mission' => $missionResult,
			'partner_key' => $partnerKey 
		);
		$this->logcachedb->insert ( 'log_flowover_detail', $parameter );
		log_message('custom', 'insert log_flowover_detail');
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
			$query = $this->logdb->query ( $sql );
			$result = $query->result_array ();
			if (! empty ( $result ))
			{
				for ( $i=0; $i<count($result); $i++ )
				{
					$result[$i] = implode(':', $result[$i]);
				}
				$levelDetail[strval($m)] = implode(',', $result);
			}
			$query->free_result();
		}
		for($m=1; $m<=6; $m++)
		{
			$missionDetail[strval($m)] = '';
			$sql = "SELECT `role_mission`, COUNT(*) AS `count` FROM `log_consume` WHERE `action_name` = 'buy_equipment' AND `item_info` = {$m} AND `server_id` = '{$server_id}' AND `partner_key` = '{$partnerKey}' AND `log_time` >= {$timeStart} AND `log_time` <= {$timeEnd} GROUP BY `role_mission`";
			$query = $this->logdb->query ( $sql );
			$result = $query->result_array ();
			if (! empty ( $result ))
			{
				for ( $i=0; $i<count($result); $i++ )
				{
					$result[$i] = implode(':', $result[$i]);
				}
				$missionDetail[strval($m)] = implode(',', $result);
			}
			$query->free_result();
		}
		
		$parameter = array(
			'date'					=>	$date,
			'server_id'				=>	$server_id,
			'partner_key'			=>	$partnerKey,
			'level_detail'			=>	json_encode($levelDetail),
			'mission_detail'	=>	json_encode($missionDetail)
		);
		$this->logcachedb->insert('log_buy_equipment_detail', $parameter);
		log_message('custom', 'insert log_buy_equipment_detail');
	}
	
	public function retention1_statistics()
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
		//五天前
		$fiveTimeStart = $lastTimeStart - 4 * 86400;
		$fiveTimeEnd = $lastTimeEnd - 4 * 86400;
		//六天前
		$sixTimeStart = $lastTimeStart - 5 * 86400;
		$sixTimeEnd = $lastTimeEnd - 5 * 86400;
		//七天前
		$sevenTimeStart = $lastTimeStart - 6 * 86400;
		$sevenTimeEnd = $lastTimeEnd - 6 * 86400;
		
		foreach ( $serverResult as $row )
		{
			foreach ( $partnerResult as $partner )
			{
				$partnerKey = $partner->partner_key;
				
				//昨日注册数
				$prevTimeDate = date('Y-m-d', $prevTimeStart);
				$sql = "SELECT `level_account` FROM `log_retention1` WHERE `log_date`='{$prevTimeDate}' AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query ( $sql );
				$lastRegisterCount = $query->row();
				if(empty($lastRegisterCount))
				{
					$currentLogin = 0;
					$nextRetention = 0;
				}
				else
				{
					$lastRegisterCount = $lastRegisterCount->level_account;
					//今天登录数
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$currentLogin = $this->logdb->query($sql)->num_rows();
					
					$nextRetention = floor(($currentLogin / $lastRegisterCount) * 10000);
				}
				$query->free_result();
				
				//三天前注册数
				$thirdTimeDate = date('Y-m-d', $thirdTimeStart);
				$sql = "SELECT `level_account` FROM `log_retention1` WHERE `log_date`='{$thirdTimeDate}' AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query ( $sql );
				$thirdRegisterCount = $query->row();
				if(empty($thirdRegisterCount))
				{
					$thirdCurrentLogin = 0;
					$thirdRetention = 0;
					$thirdCurrentLoginRange = 0;
					$thirdRetentionRange = 0;
				}
				else
				{
					$thirdRegisterCount = $thirdRegisterCount->level_account;
					//点三
					//今天登录数
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$thirdTimeStart} AND `account_regtime`<={$thirdTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$thirdCurrentLoginResult = $this->logdb->query($sql);
					$thirdCurrentLogin = $thirdCurrentLoginResult->num_rows();
					
					$thirdRetention = floor(($thirdCurrentLogin / $thirdRegisterCount) * 10000);
					
					$thirdCurrentLoginResult = $thirdCurrentLoginResult->result_array();
					for($i=0; $i<count($thirdCurrentLoginResult); $i++)
					{
						$thirdCurrentLoginResult[$i] = $thirdCurrentLoginResult[$i]['log_GUID'];
					}
					//区间三
					//昨天登录
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$prevTimeStart} AND `log_time`<={$prevTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$thirdTimeStart} AND `account_regtime`<={$thirdTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$prevCurrentLoginResult = $this->logdb->query($sql)->result_array();
					for($i=0; $i<count($prevCurrentLoginResult); $i++)
					{
						$prevCurrentLoginResult[$i] = $prevCurrentLoginResult[$i]['log_GUID'];
					}
					
					$thirdCurrentLoginRange = array_intersect($thirdCurrentLoginResult, $prevCurrentLoginResult);
					$thirdCurrentLoginRange = count($thirdCurrentLoginRange);
					
					$thirdRetentionRange = floor(($thirdCurrentLoginRange / $thirdRegisterCount) * 10000);
				}
				$query->free_result();
				
				//七天前注册数
				$sevenTimeDate = date('Y-m-d', $sevenTimeStart);
				$sql = "SELECT `level_account` FROM `log_retention1` WHERE `log_date`='{$sevenTimeDate}' AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query ( $sql );
				$sevenRegisterCount = $query->row();
				if(empty($sevenRegisterCount))
				{
					$sevenCurrentLogin = 0;
					$sevenRetention = 0;
					$sevenCurrentLoginRange = 0;
					$sevenRetentionRange = 0;
					$sevenCurrentLoginHuge = 0;
					$sevenRetentionHuge = 0;
				}
				else
				{
					$sevenRegisterCount = $sevenRegisterCount->level_account;
					//点七
					//六天前登录
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$sixTimeStart} AND `log_time`<={$sixTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$sixCurrentLoginResult = $this->logdb->query($sql);
					$sixCurrentLoginResult = $sixCurrentLoginResult->result_array();
					for($i=0; $i<count($sixCurrentLoginResult); $i++)
					{
						$sixCurrentLoginResult[$i] = $sixCurrentLoginResult[$i]['log_GUID'];
					}
					//今天登录数
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$sevenCurrentLoginResult = $this->logdb->query($sql);
					$sevenCurrentLoginResult = $sevenCurrentLoginResult->result_array();
					for($i=0; $i<count($sevenCurrentLoginResult); $i++)
					{
						$sevenCurrentLoginResult[$i] = $sevenCurrentLoginResult[$i]['log_GUID'];
					}
					
					$sevenCurrentLogin = array_intersect($sixCurrentLoginResult, $sevenCurrentLoginResult);
					$sevenCurrentLogin = count($sevenCurrentLogin);
					
					$sevenRetention = floor(($sevenCurrentLogin / $sevenRegisterCount) * 10000);
					
					//小区间七
					//第三至今天登录数
					//今天登录数
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$fiveTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$fiveCurrentLoginResult = $this->logdb->query($sql);
					$fiveCurrentLoginResult = $fiveCurrentLoginResult->result_array();
					for($i=0; $i<count($fiveCurrentLoginResult); $i++)
					{
						$fiveCurrentLoginResult[$i] = $fiveCurrentLoginResult[$i]['log_GUID'];
					}
					
					$sevenCurrentLoginRange = array_intersect($sixCurrentLoginResult, $fiveCurrentLoginResult);
					$sevenCurrentLoginRange = count($sevenCurrentLoginRange);
					
					$sevenRetentionRange = floor(($sevenCurrentLoginRange / $sevenRegisterCount) * 10000);
					
					//大区间七
					$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$sixTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
					$hugeCurrentLoginResult = $this->logdb->query($sql);
					$hugeCurrentLoginResult = $hugeCurrentLoginResult->result_array();
					for($i=0; $i<count($hugeCurrentLoginResult); $i++)
					{
						$hugeCurrentLoginResult[$i] = $hugeCurrentLoginResult[$i]['log_GUID'];
					}
					
					$sevenCurrentLoginHuge = array_intersect($sixCurrentLoginResult, $hugeCurrentLoginResult);
					$sevenCurrentLoginHuge = count($sevenCurrentLoginHuge);
					
					$sevenRetentionHuge = floor(($sevenCurrentLoginHuge / $sevenRegisterCount) * 10000);
				}
				$query->free_result();
				
				//今天内等级为1的账户数
				$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`=1 AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$query = $this->accountdb->query ( $sql );
				$level1 = $query->row();
				$level1 = $level1->numrows;
				$query->free_result();

				// 等级大于1的帐号
				$this->accountdb->where ( 'server_id', $row->account_server_id );
				$this->accountdb->where ( 'partner_key', $partnerKey );
				$this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
				$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
				$this->accountdb->where ( 'account_level >', 1 );
				$levelCount = $this->accountdb->count_all_results ( 'web_account' );
				
				$parameter = array(
						'log_date'					=>	date('Y-m-d', $lastTimeStart),
						'server_id'					=>	$row->account_server_id,
						'partner_key'				=>	$partnerKey,
						'level_account'				=>	$levelCount,
						'next_current_login'		=>	$currentLogin,
						'next_retention'			=>	$nextRetention,
						'third_current_login'		=>	$thirdCurrentLogin,
						'third_retention'			=>	$thirdRetention,
						'third_current_login_range'	=>	$thirdCurrentLoginRange,
						'third_retention_range'		=>	$thirdRetentionRange,
						'seven_current_login'		=>	$sevenCurrentLogin,
						'seven_retention'			=>	$sevenRetention,
						'seven_current_login_range'	=>	$sevenCurrentLoginRange,
						'seven_retention_range'		=>	$sevenRetentionRange,
						'seven_current_login_huge'	=>	$sevenCurrentLoginHuge,
						'seven_retention_huge'		=>	$sevenRetentionHuge,
						'level1'					=>	$level1
				);
				
				$this->logcachedb->insert('log_retention1', $parameter);
			}
			

			$partnerKey = $partner->partner_key;
			
			//昨日注册数
			$prevTimeDate = date('Y-m-d', $prevTimeStart);
			$sql = "SELECT `level_account` FROM `log_retention1` WHERE `log_date`='{$prevTimeDate}' AND `server_id`='{$row->account_server_id}' AND `partner_key`=''";
			$query = $this->logcachedb->query ( $sql );
			$lastRegisterCount = $query->row();
			if(empty($lastRegisterCount))
			{
				$currentLogin = 0;
				$nextRetention = 0;
			}
			else
			{
				$lastRegisterCount = $lastRegisterCount->level_account;
				//今天登录数
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$currentLogin = $this->logdb->query($sql)->num_rows();
					
				$nextRetention = floor(($currentLogin / $lastRegisterCount) * 10000);
			}
			$query->free_result();
			
			//三天前注册数
			$thirdTimeDate = date('Y-m-d', $thirdTimeStart);
			$sql = "SELECT `level_account` FROM `log_retention1` WHERE `log_date`='{$thirdTimeDate}' AND `server_id`='{$row->account_server_id}' AND `partner_key`=''";
			$query = $this->logcachedb->query ( $sql );
			$thirdRegisterCount = $query->row();
			if(empty($thirdRegisterCount))
			{
				$thirdCurrentLogin = 0;
				$thirdRetention = 0;
				$thirdCurrentLoginRange = 0;
				$thirdRetentionRange = 0;
			}
			else
			{
				$thirdRegisterCount = $thirdRegisterCount->level_account;
				//点三
				//今天登录数
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$thirdTimeStart} AND `account_regtime`<={$thirdTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$thirdCurrentLoginResult = $this->logdb->query($sql);
				$thirdCurrentLogin = $thirdCurrentLoginResult->num_rows();
					
				$thirdRetention = floor(($thirdCurrentLogin / $thirdRegisterCount) * 10000);
					
				$thirdCurrentLoginResult = $thirdCurrentLoginResult->result_array();
				for($i=0; $i<count($thirdCurrentLoginResult); $i++)
				{
					$thirdCurrentLoginResult[$i] = $thirdCurrentLoginResult[$i]['log_GUID'];
				}
				//区间三
				//昨天登录
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$prevTimeStart} AND `log_time`<={$prevTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$thirdTimeStart} AND `account_regtime`<={$thirdTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$prevCurrentLoginResult = $this->logdb->query($sql)->result_array();
				for($i=0; $i<count($prevCurrentLoginResult); $i++)
				{
					$prevCurrentLoginResult[$i] = $prevCurrentLoginResult[$i]['log_GUID'];
				}
					
				$thirdCurrentLoginRange = array_intersect($thirdCurrentLoginResult, $prevCurrentLoginResult);
				$thirdCurrentLoginRange = count($thirdCurrentLoginRange);
					
				$thirdRetentionRange = floor(($thirdCurrentLoginRange / $thirdRegisterCount) * 10000);
			}
			$query->free_result();
			
			//七天前注册数
			$sevenTimeDate = date('Y-m-d', $sevenTimeStart);
			$sql = "SELECT `level_account` FROM `log_retention1` WHERE `log_date`='{$sevenTimeDate}' AND `server_id`='{$row->account_server_id}' AND `partner_key`=''";
			$query = $this->logcachedb->query ( $sql );
			$sevenRegisterCount = $query->row();
			if(empty($sevenRegisterCount))
			{
				$sevenCurrentLogin = 0;
				$sevenRetention = 0;
				$sevenCurrentLoginRange = 0;
				$sevenRetentionRange = 0;
				$sevenCurrentLoginHuge = 0;
				$sevenRetentionHuge = 0;
			}
			else
			{
				$sevenRegisterCount = $sevenRegisterCount->level_account;
				//点七
				//六天前登录
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$sixTimeStart} AND `log_time`<={$sixTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$sixCurrentLoginResult = $this->logdb->query($sql);
				$sixCurrentLoginResult = $sixCurrentLoginResult->result_array();
				for($i=0; $i<count($sixCurrentLoginResult); $i++)
				{
					$sixCurrentLoginResult[$i] = $sixCurrentLoginResult[$i]['log_GUID'];
				}
				//今天登录数
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$sevenCurrentLoginResult = $this->logdb->query($sql);
				$sevenCurrentLoginResult = $sevenCurrentLoginResult->result_array();
				for($i=0; $i<count($sevenCurrentLoginResult); $i++)
				{
					$sevenCurrentLoginResult[$i] = $sevenCurrentLoginResult[$i]['log_GUID'];
				}
				
				$sevenCurrentLogin = array_intersect($sixCurrentLoginResult, $sevenCurrentLoginResult);
				$sevenCurrentLogin = count($sevenCurrentLogin);
					
				$sevenRetention = floor(($sevenCurrentLogin / $sevenRegisterCount) * 10000);
					
				//小区间七
				//第三至今天登录数
				//今天登录数
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$fiveTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$fiveCurrentLoginResult = $this->logdb->query($sql);
				$fiveCurrentLoginResult = $fiveCurrentLoginResult->result_array();
				for($i=0; $i<count($fiveCurrentLoginResult); $i++)
				{
					$fiveCurrentLoginResult[$i] = $fiveCurrentLoginResult[$i]['log_GUID'];
				}
							
				$sevenCurrentLoginRange = array_intersect($sixCurrentLoginResult, $fiveCurrentLoginResult);
				$sevenCurrentLoginRange = count($sevenCurrentLoginRange);
					
				$sevenRetentionRange = floor(($sevenCurrentLoginRange / $sevenRegisterCount) * 10000);
					
				//大区间七
				$sql = "SELECT `log_GUID` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$sixTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$hugeCurrentLoginResult = $this->logdb->query($sql);
				$hugeCurrentLoginResult = $hugeCurrentLoginResult->result_array();
				for($i=0; $i<count($hugeCurrentLoginResult); $i++)
				{
					$hugeCurrentLoginResult[$i] = $hugeCurrentLoginResult[$i]['log_GUID'];
				}
					
				$sevenCurrentLoginHuge = array_intersect($sixCurrentLoginResult, $hugeCurrentLoginResult);
				$sevenCurrentLoginHuge = count($sevenCurrentLoginHuge);
					
				$sevenRetentionHuge = floor(($sevenCurrentLoginHuge / $sevenRegisterCount) * 10000);	
			}
			$query->free_result();
			
			//今天内等级为1的账户数
			$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`=1 AND `server_id`='{$row->account_server_id}'";
			$query = $this->accountdb->query ( $sql );
			$level1 = $query->row();
			$level1 = $level1->numrows;
			$query->free_result();

			// 等级大于1的帐号
			$this->accountdb->where ( 'server_id', $row->account_server_id );
			$this->accountdb->where ( 'account_regtime >=', $lastTimeStart );
			$this->accountdb->where ( 'account_regtime <=', $lastTimeEnd );
			$this->accountdb->where ( 'account_level >', 1 );
			$levelCount = $this->accountdb->count_all_results ( 'web_account' );
		
			$parameter = array(
					'log_date'					=>	date('Y-m-d', $lastTimeStart),
					'server_id'					=>	$row->account_server_id,
					'partner_key'				=>	'',
					'level_account'				=>	$levelCount,
					'next_current_login'		=>	$currentLogin,
					'next_retention'			=>	$nextRetention,
					'third_current_login'		=>	$thirdCurrentLogin,
					'third_retention'			=>	$thirdRetention,
					'third_current_login_range'	=>	$thirdCurrentLoginRange,
					'third_retention_range'		=>	$thirdRetentionRange,
					'seven_current_login'		=>	$sevenCurrentLogin,
					'seven_retention'			=>	$sevenRetention,
					'seven_current_login_range'	=>	$sevenCurrentLoginRange,
					'seven_retention_range'		=>	$sevenRetentionRange,
					'seven_current_login_huge'	=>	$sevenCurrentLoginHuge,
					'seven_retention_huge'		=>	$sevenRetentionHuge,
					'level1'					=>	$level1
			);
			$this->logcachedb->insert('log_retention1', $parameter);
		}
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
		//七天前
		$sevenTimeStart = $lastTimeStart - 6 * 86400;
		$sevenTimeEnd = $lastTimeEnd - 6 * 86400;

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
				
				$thirdRetention = floor(($thirdCurrentLogin / $thirdRegisterCount) * 10000);
				
				//七天前注册数
				$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1 AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$sevenRegisterCount = $this->accountdb->query ( $sql )->row();
				$sevenRegisterCount = $sevenRegisterCount->numrows;
				//今天登录数
				$sql = "SELECT `log_GUID` as `numrows` FROM `log_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `log_action`='ACCOUNT_LOGIN_SUCCESS' AND `log_time`>={$lastTimeStart} AND `log_time`<={$lastTimeEnd} AND `log_GUID` in (SELECT `GUID` FROM `agent1_account_db`.`web_account` WHERE `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}' AND `account_regtime`>={$sevenTimeStart} AND `account_regtime`<={$sevenTimeEnd} AND `account_level`>1) GROUP BY `log_GUID`";
				$sevenCurrentLogin = $this->logdb->query($sql)->num_rows();
				
				$sevenRetention = floor(($sevenCurrentLogin / $sevenRegisterCount) * 10000);
				
				//今天内等级为1的账户数
				$sql = "SELECT COUNT(*) as `numrows` FROM `web_account` WHERE `account_regtime`>={$prevTimeStart} AND `account_regtime`<={$prevTimeEnd} AND `account_level`=1 AND `server_id`='{$row->account_server_id}' AND `partner_key`='{$partnerKey}'";
				$level1 = $this->accountdb->query ( $sql )->row();
				$level1 = $level1->numrows;
				
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
						'seven_register'		=>	$sevenRegisterCount,
						'seven_current_login'	=>	$sevenCurrentLogin,
						'seven_retention'		=>	$sevenRetention,
						'level1'				=>	$level1
				);
				$this->logcachedb->insert('log_retention', $parameter);
				
				$parameter = array(
						'level_account'		=>	$registerCount
				);
				$this->logcachedb->where('log_date', date('Y-m-d', $prevTimeStart));
				$this->logcachedb->where('server_id', $row->account_server_id);
				$this->logcachedb->where('partner_key', $partnerKey);
				$this->logcachedb->update('log_daily_statistics', $parameter);
			}
		}
	}
}
?>
