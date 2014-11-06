<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Market_life_time extends CI_Controller
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

	public function market_lifetime()
	{
		ini_set("display_error", 1);
		error_reporting(E_ALL);
		set_time_limit(3600);

		$this->load->model('mlogmarketlifetime');
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

		//两天前
		$timeStart2 = $lastTimeStart - 86400;
		$timeEnd2 = $lastTimeEnd - 86400;
		//三天前
		$timeStart3 = $lastTimeStart - 2 * 86400;
		$timeEnd3 = $lastTimeEnd - 2 * 86400;
		//四天前
		$timeStart4 = $lastTimeStart - 3 * 86400;
		$timeEnd4 = $lastTimeEnd - 3 * 86400;
		//五天前
		$timeStart5 = $lastTimeStart - 4 * 86400;
		$timeEnd5 = $lastTimeEnd - 4 * 86400;
		//六天前
		$timeStart6 = $lastTimeStart - 5 * 86400;
		$timeEnd6 = $lastTimeEnd - 5 * 86400;
		//七天前
		$timeStart7 = $lastTimeStart - 6 * 86400;
		$timeEnd7 = $lastTimeEnd - 6 * 86400;
		//十四天前
		$timeStart14 = $lastTimeStart - 13 * 86400;
		$timeEnd14 = $lastTimeEnd - 13 * 86400;
		//三十天前
		$timeStart30 = $lastTimeStart - 29 * 86400;
		$timeEnd30 = $lastTimeEnd - 29 * 86400;
		//六十天前
		$timeStart60 = $lastTimeStart - 59 * 86400;
		$timeEnd60 = $lastTimeEnd - 59 * 86400;
		//九十天前
		$timeStart90 = $lastTimeStart - 89 * 86400;
		$timeEnd90 = $lastTimeEnd - 89 * 86400;
		//一百八十天前
		$timeStart180 = $lastTimeStart - 179 * 86400;
		$timeEnd180 = $lastTimeEnd - 179 * 86400;
		
		foreach ( $serverResult as $row )
		{
			foreach ( $partnerResult as $partner )
			{
				$partnerKey = $partner->partner_key;

				//当天注册数
				$where = "`server_id` = '{$row->account_server_id}' and `account_level` > 0 and `partner_key`='{$partnerKey}' and `account_regtime` >= {$lastTimeStart} and `account_regtime` <= {$lastTimeEnd}";
				$this->accountdb->where ( $where );
				$regNewCount = $this->accountdb->count_all_results ( 'web_account' );
				log_message('custom', 'regNewCount = ' . $regNewCount);

				//当天付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$lastTimeStart} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$lastTimeStart} and `account_regtime` <= {$lastTimeEnd})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount1 = $result->count;
				$query->free_result();

				//当天付费率
				$paidRate1 = floatval ( number_format ( $paidCount1 / $regNewCount, 4 ) ) * 10000;

				//当天付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$lastTimeStart} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$lastTimeStart} and `account_regtime` <= {$lastTimeEnd})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount1 = $result->amount;
				$query->free_result();

				//插入当天数据
				$parameter = array(
					'date'				=>	date('Y-m-d', $lastTimeStart),
					'server_id'			=>	$row->account_server_id,
					'partner_key'		=>	$partnerKey,
					'register_count'	=>	$regNewCount,
					'paid_count_1'		=>	$paidCount1,
					'paid_rate_1'		=>	$paidRate1,
					'recharge_amount_1'	=>	$rechargeAmount1 ? $rechargeAmount1 : 0
				);
				//$this->mlogmarketlifetime->create($parameter);
				var_dump($parameter);

				//两天前注册数
				$date = date('Y-m-d', $timeStart2);
				$sql = "select `register_count` from `log_market_lifetime` where `date`='{$date}' and `server_id`='{$row->account_server_id}' and `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query($sql);
				$result = $query->row();
				$regCount2 = $result->register_count;
				$query->free_result();

				//两天前到当天为止付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart2} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart2} and `account_regtime` <= {$timeEnd2})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount2 = $result->count;
				$query->free_result();

				//两天前到当天为止付费率
				$paidRate2 = floatval ( number_format ( $paidCount2 / $regCount2, 4 ) ) * 10000;

				//两天前到当天为止付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart2} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart2} and `account_regtime` <= {$timeEnd2})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount2 = $result->amount;
				$query->free_result();

				//更新两天前数据
				$parameter = array(
					'paid_count_2'		=>	$paidCount2,
					'paid_rate_2'		=>	$paidRate2,
					'recharge_amount_2'	=>	$rechargeAmount2 ? $rechargeAmount2 : 0
				);
				// $this->mlogmarketlifetime->update(array(
				// 	'date'			=>	$date,
				// 	'server_id'		=>	$row->account_server_id,
				// 	'partner_key'	=>	$partnerKey
				// ), $parameter);
				var_dump($parameter);

				//三天前注册数
				$date = date('Y-m-d', $timeStart3);
				$sql = "select `register_count` from `log_market_lifetime` where `date`='{$date}' and `server_id`='{$row->account_server_id}' and `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query($sql);
				$result = $query->row();
				$regCount3 = $result->register_count;
				$query->free_result();

				//三天前到当天为止付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart3} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart3} and `account_regtime` <= {$timeEnd3})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount3 = $result->count;
				$query->free_result();

				//三天前到当天为止付费率
				$paidRate3 = floatval ( number_format ( $paidCount3 / $regCount3, 4 ) ) * 10000;

				//三天前到当天为止付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart3} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart3} and `account_regtime` <= {$timeEnd3})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount3 = $result->amount;
				$query->free_result();

				//更新三天前数据
				$parameter = array(
					'paid_count_3'		=>	$paidCount3,
					'paid_rate_3'		=>	$paidRate3,
					'recharge_amount_3'	=>	$rechargeAmount3 ? $rechargeAmount3 : 0
				);
				// $this->mlogmarketlifetime->update(array(
				// 	'date'			=>	$date,
				// 	'server_id'		=>	$row->account_server_id,
				// 	'partner_key'	=>	$partnerKey
				// ), $parameter);
				var_dump($parameter);

				//四天前注册数
				$date = date('Y-m-d', $timeStart4);
				$sql = "select `register_count` from `log_market_lifetime` where `date`='{$date}' and `server_id`='{$row->account_server_id}' and `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query($sql);
				$result = $query->row();
				$regCount4 = $result->register_count;
				$query->free_result();

				//四天前到当天为止付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart4} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart4} and `account_regtime` <= {$timeEnd4})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount4 = $result->count;
				$query->free_result();

				//四天前到当天为止付费率
				$paidRate4 = floatval ( number_format ( $paidCount4 / $regCount4, 4 ) ) * 10000;

				//四天前到当天为止付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart4} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart4} and `account_regtime` <= {$timeEnd4})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount4 = $result->amount;
				$query->free_result();

				//更新四天前数据
				$parameter = array(
					'paid_count_4'		=>	$paidCount4,
					'paid_rate_4'		=>	$paidRate4,
					'recharge_amount_4'	=>	$rechargeAmount4 ? $rechargeAmount4 : 0
				);
				// $this->mlogmarketlifetime->update(array(
				// 	'date'			=>	$date,
				// 	'server_id'		=>	$row->account_server_id,
				// 	'partner_key'	=>	$partnerKey
				// ), $parameter);
				var_dump($parameter);

				//五天前注册数
				$date = date('Y-m-d', $timeStart5);
				$sql = "select `register_count` from `log_market_lifetime` where `date`='{$date}' and `server_id`='{$row->account_server_id}' and `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query($sql);
				$result = $query->row();
				$regCount5 = $result->register_count;
				$query->free_result();

				//五天前到当天为止付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart5} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart5} and `account_regtime` <= {$timeEnd5})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount5 = $result->count;
				$query->free_result();

				//五天前到当天为止付费率
				$paidRate5 = floatval ( number_format ( $paidCount5 / $regCount5, 4 ) ) * 10000;

				//五天前到当天为止付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart5} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart5} and `account_regtime` <= {$timeEnd5})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount5 = $result->amount;
				$query->free_result();

				//更新五天前数据
				$parameter = array(
					'paid_count_5'		=>	$paidCount5,
					'paid_rate_5'		=>	$paidRate5,
					'recharge_amount_5'	=>	$rechargeAmount5 ? $rechargeAmount5 : 0
				);
				// $this->mlogmarketlifetime->update(array(
				// 	'date'			=>	$date,
				// 	'server_id'		=>	$row->account_server_id,
				// 	'partner_key'	=>	$partnerKey
				// ), $parameter);
				var_dump($parameter);

				//六天前注册数
				$date = date('Y-m-d', $timeStart6);
				$sql = "select `register_count` from `log_market_lifetime` where `date`='{$date}' and `server_id`='{$row->account_server_id}' and `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query($sql);
				$result = $query->row();
				$regCount6 = $result->register_count;
				$query->free_result();

				//六天前到当天为止付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart6} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart6} and `account_regtime` <= {$timeEnd6})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount6 = $result->count;
				$query->free_result();

				//六天前到当天为止付费率
				$paidRate6 = floatval ( number_format ( $paidCount6 / $regCount6, 4 ) ) * 10000;

				//六天前到当天为止付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart6} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart6} and `account_regtime` <= {$timeEnd6})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount6 = $result->amount;
				$query->free_result();

				//更新六天前数据
				$parameter = array(
					'paid_count_6'		=>	$paidCount6,
					'paid_rate_6'		=>	$paidRate6,
					'recharge_amount_6'	=>	$rechargeAmount6 ? $rechargeAmount6 : 0
				);
				// $this->mlogmarketlifetime->update(array(
				// 	'date'			=>	$date,
				// 	'server_id'		=>	$row->account_server_id,
				// 	'partner_key'	=>	$partnerKey
				// ), $parameter);
				var_dump($parameter);

				//七天前注册数
				$date = date('Y-m-d', $timeStart7);
				$sql = "select `register_count` from `log_market_lifetime` where `date`='{$date}' and `server_id`='{$row->account_server_id}' and `partner_key`='{$partnerKey}'";
				$query = $this->logcachedb->query($sql);
				$result = $query->row();
				$regCount7 = $result->register_count;
				$query->free_result();

				//七天前到当天为止付费人数
				$sql = "select count(*) as `count` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart7} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart7} and `account_regtime` <= {$timeEnd7})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$paidCount7 = $result->count;
				$query->free_result();

				//七天前到当天为止付费率
				$paidRate7 = floatval ( number_format ( $paidCount7 / $regCount7, 4 ) ) * 10000;

				//六天前到当天为止付费总额
				$sql = "select sum(`funds_amount`) as `amount` from `funds_checkinout` where `funds_flow_dir`='CHECK_IN' and `appstore_status`=0 and `funds_time` >= {$timeStart7} and `funds_time` <= {$lastTimeEnd} and `account_guid` in (select `GUID` from `agent1_account_db`.`web_account` where `server_id` = '{$row->account_server_id}' and `partner_key`='{$partnerKey}' and `account_regtime` >= {$timeStart7} and `account_regtime` <= {$timeEnd7})";
				$query = $this->fundsdb->query($sql);
				$result = $query->row();
				$rechargeAmount7 = $result->amount;
				$query->free_result();

				//更新七天前数据
				$parameter = array(
					'paid_count_7'		=>	$paidCount7,
					'paid_rate_7'		=>	$paidRate7,
					'recharge_amount_7'	=>	$rechargeAmount7 ? $rechargeAmount7 : 0
				);
				// $this->mlogmarketlifetime->update(array(
				// 	'date'			=>	$date,
				// 	'server_id'		=>	$row->account_server_id,
				// 	'partner_key'	=>	$partnerKey
				// ), $parameter);
				var_dump($parameter);
			}
		}
	}
}
?>
