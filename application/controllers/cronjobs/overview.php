<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {
	private $accountdb = null;
	private $logcachedb = null;
	private $logdb = null;
	private $fundsdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
		$this->logcachedb = $this->load->database('log_cachedb', true);
		$this->logdb = $this->load->database('logdb', true);
		$this->fundsdb = $this->load->database('fundsdb', true);
	}
	
	public function statistics() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
		$preDate = date('Y-m-d', $lastTimeStart - 86400);
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			//总注册数
			$this->accountdb->where('server_id', $row->account_server_id);
			$registerCount = $this->accountdb->count_all_results('web_account');
			
			//昨日新注册数
			$this->logcachedb->where('log_date', $preDate);
			$this->logcachedb->where('server_id', $row->account_server_id);
			$lastResult = $this->logcachedb->get('log_daily_statistics')->row();
			$lastNewReg = intval($lastResult->reg_new_account);
			
			//新注册数
			$where = "`server_id` = '{$row->account_server_id}' and `log_time` >= {$lastTimeStart} and `log_time` <= {$lastTimeEnd} and (`log_action` = 'ACCOUNT_REGISTER_SUCCESS' or `log_action` = 'ACCOUNT_DEMO_SUCCESS')";
			$this->logdb->where($where);
			$regNewCount = $this->logdb->count_all_results('log_account');
			
			//总改名用户数
			$this->accountdb->where('server_id', $row->account_server_id);
			$this->accountdb->where('account_status', 1);
			$modifyCount = $this->accountdb->count_all_results('web_account');

			//当天活跃玩家数(登录数)
			$this->logdb->where('log_action', 'ACCOUNT_LOGIN_SUCCESS');
			$this->logdb->where('log_time >', $lastTimeStart);
			$this->logdb->where('log_time <=', $lastTimeEnd);
			$this->logdb->where('server_id', $row->account_server_id);
			$this->logdb->group_by('log_GUID');
			$loginCount = $this->logdb->count_all_results('log_account');
			
			//活跃玩家数(三天以内登录过游戏的人数)
			$threeDaysAgoStart = $lastTimeStart - 3 * 86400;
			$this->accountdb->where('account_lastlogin >=', $threeDaysAgoStart);
			$this->accountdb->where('account_lastlogin <=', $lastTimeEnd);
			$this->accountdb->where('server_id', $row->account_server_id);
			$activeCount = $this->accountdb->count_all_results('web_account');
			
			//回流玩家数(超过一周没有登录但最近有登录的玩家数)
			$query = $this->logcachedb->get('log_flowover_cache');
			$guidArray = $query->result();
			$flowoverCacheResult = array();
			foreach($guidArray as $guid)
			{
				array_push($flowoverCacheResult, $guid->guid);
			}
			$reflowCount = 0;
			if(!empty($guidArray))
			{
				$threeDaysAgoStart = $lastTimeStart - 3 * 86400;
				$this->accountdb->where('account_lastlogin >=', $threeDaysAgoStart);
				$this->accountdb->where('account_lastlogin <=', $lastTimeEnd);
				$this->accountdb->where('server_id', $row->account_server_id);
				$this->accountdb->where_in('GUID', $flowoverCacheResult);
				$reflowCount = $this->accountdb->count_all_results('web_account');
			}
			$query->free_result();
			$this->logcachedb->truncate('log_flowover_cache');
			
			//流失玩家数(超过一周没有登录的玩家数)
			$weekAgoStart = $lastTimeStart - 7 * 86400;
			$this->accountdb->where('account_lastlogin <=', $weekAgoStart);
			$this->accountdb->where('server_id', $row->account_server_id);
			$flowoverCount = $this->accountdb->count_all_results('web_account');
			//流失玩家放入临时表
			$this->accountdb->where('account_lastlogin <=', $weekAgoStart);
			$this->accountdb->where('server_id', $row->account_server_id);
			$query = $this->accountdb->get('web_account');
			$flowoverResult = $query->result();
			foreach($flowoverResult as $flowover)
			{
				$this->logcachedb->insert('log_flowover_cache', array('guid'=>$flowover->GUID));
			}
			$query->free_result();

			//次日留存
			$secondSurvive = floatval(number_format(($loginCount - $regNewCount) / $lastNewReg, 2)) * 100;

			//当天订单数
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$ordersCount = $this->fundsdb->count_all_results('funds_checkinout');

			//当天订单总额
			$this->fundsdb->select_sum('funds_amount');
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$checkResult = $this->fundsdb->get('funds_checkinout')->row();
			$ordersCurrentSum = intval($checkResult->funds_amount);

			//订单总额
			$this->fundsdb->select_sum('funds_amount');
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('server_id', $row->account_server_id);
			$checkResult = $this->fundsdb->get('funds_checkinout')->row();
			$ordersSum = intval($checkResult->funds_amount);
			
			//当天充值人数
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->group_by('account_guid');
			$rechargeAccount = $this->fundsdb->count_all_results('funds_checkinout');

			//arpu
			$arpu = floatval(number_format($ordersCurrentSum / $loginCount, 2)) * 100;
			
			$parameter = array(
				'log_date'						=>	$date,
				'server_id'						=>	$row->account_server_id,
				'server_name'				=>	$row->server_name,
				'reg_account'				=>	$registerCount,
				'reg_new_account'			=>	$regNewCount,
				'modify_account'			=>	$modifyCount,
				'login_account'				=>	$loginCount,
				'active_account'			=>	$activeCount,
				'flowover_account'		=>	$flowoverCount,
				'reflow_account'			=>	$reflowCount,
				'orders_current_sum'		=>	$ordersCurrentSum,
				'orders_sum'					=>	$ordersSum,
				'arpu'							=>	$arpu,
				'recharge_account'		=>	$rechargeAccount,
				'order_count'				=>	$ordersCount,
				'second_survive'			=>	$secondSurvive
			);
			$this->logcachedb->insert('log_daily_statistics', $parameter);
		}
	}

	public function update_from_ruby() {
		$gameId = $this->input->get_post('game_id', TRUE);
		$date = $this->input->get_post('date', TRUE);
		$serverName = $this->input->get_post('server_name', TRUE);
		$regCount = $this->input->get_post('registery', TRUE);
		$modifyCount = $this->input->get_post('modification', TRUE);
		$loginCount = $this->input->get_post('login', TRUE);
		$ordersCount = $this->input->get_post('orders_num', TRUE);
		$ordersSum = $this->input->get_post('orders_sum', TRUE);
		$key = $this->input->get_post('key', TRUE);
		$format = 'json';

		$this->load->model('return_format');
		if(!empty($gameId) && !empty($serverName) && is_numeric($regCount) && is_numeric($modifyCount) && is_numeric($loginCount) && is_numeric($ordersCount) && is_numeric($ordersSum)) {
			/*
			 * 检测参数合法性
			*/
			$authKey = $this->config->item('game_auth_key');
			$this->load->model('param_check');
			$this->load->model('websrv/update_ruby', 'ruby');
			
			$authToken	=	$authKey[$gameId]['auth_key'];
			$check = array($gameId, $date, $serverName, $regCount, $modifyCount, $loginCount, $ordersCount, $ordersSum);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $authToken));
			if(!$this->param_check->check($check, $authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				exit();
			}
			/*
			 * 检查完毕
			*/
			
			if(empty($date)) {
				$date = date('Y-m-d');
			}
			
			$parameter = array(
				'log_date'			=>	$date,
				'server_name'		=>	$serverName,
				'reg_account'		=>	$regCount,
				'modify_account'	=>$modifyCount,
				'login_account'	=>	$loginCount,
				'orders_num'		=>	$ordersCount,
				'orders_sum'		=>	$ordersSum,
				'partner_key'		=>	empty($key) ? '' : $key
			);
			if($this->ruby->insert($parameter)) {
				$jsonData = Array(
					'message'	=>	'UPDATE_SUCCESS'
				);
				echo $this->return_format->format($jsonData, $format);
			} else {
				$jsonData = Array(
					'message'	=>	'UPDATE_FAIL'
				);
				echo $this->return_format->format($jsonData, $format);
			}
		} else {
			$jsonData = Array(
				'message'	=>	'NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
}
?>
