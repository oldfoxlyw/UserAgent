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
			
			//总充值金额
			$this->fundsdb->select_sum('funds_amount');
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$checkResult = $this->fundsdb->get('funds_checkinout')->row();
			$checkinCount = intval($checkResult->funds_amount);
			
			$query = $this->logcachedb->query("select * from `log_daily_statistics` where (`log_date`='{$date}' or `log_date`='{$preDate}') and `game_id`='{$row->game_id}' and `server_section`='{$row->account_server_section}' and `server_id`='{$row->account_server_id}'");
			$result = $query->result();
			if($result[0]->log_date == $preDate)
			{
				$newReg = intval($result[1]->reg_account) - intval($result[0]->reg_account);
			}
			else
			{
				$newReg = intval($result[0]->reg_account) - intval($result[1]->reg_account);
			}

			$this->logcachedb->where('log_date', $date);
			$this->logcachedb->where('game_id', $row->game_id);
			$this->logcachedb->where('server_id', $row->account_server_id);
			$this->logcachedb->where('server_section', $row->account_server_section);
			$this->logcachedb->update('log_daily_statistics', array(
				'reg_new_account'	=>	$newReg
			));
		}
	}
	
	public function statistics_group() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
	
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$result = array();
			
			$this->accountdb->where('game_id', $row->game_id);
			$this->accountdb->where('account_server_id', $row->account_server_id);
			$this->accountdb->where('account_server_section', $row->account_server_section);
			$this->accountdb->where('account_active <>', 0);
			$activeCount = $this->accountdb->count_all_results('game_account');
				
			//付费人数
			$this->fundsdb->select('funds_type');
			$this->fundsdb->select('count(*) as `count`', FALSE);
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$this->fundsdb->group_by(array('account_guid', 'funds_type'));
			$payResult = $this->fundsdb->get('funds_checkinout')->result();
			foreach($payResult as $value) {
				$result[$value->funds_type]['pay_count'] = $value->count;
			}
				
			//付费次数
			$this->fundsdb->select('funds_type');
			$this->fundsdb->select('count(*) as `count`', FALSE);
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$this->fundsdb->group_by('funds_type');
			$paymentResult = $this->fundsdb->get('funds_checkinout')->result();
			foreach($paymentResult as $value) {
				$result[$value->funds_type]['payment_count'] = $value->count;
			}
				
			//总充值金额
			$this->fundsdb->select('funds_type');
			$this->fundsdb->select('SUM(`funds_amount`) as `sum`', FALSE);
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$this->fundsdb->group_by('funds_type');
			$checkinResult = $this->fundsdb->get('funds_checkinout')->result();
			foreach($checkinResult as $value) {
				$result[$value->funds_type]['checkin_count'] = $value->sum;
			}
				
			//总充值游戏币
			$this->fundsdb->select('funds_type');
			$this->fundsdb->select('SUM(`funds_item_amount`) as `sum`', FALSE);
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$this->fundsdb->group_by('funds_type');
			$checkinItemResult = $this->fundsdb->get('funds_checkinout')->result();
			foreach($checkinItemResult as $value) {
				$result[$value->funds_type]['checkinitem_count'] = $value->sum;
			}
				
			//总消费金额
			$this->fundsdb->select('funds_type');
			$this->fundsdb->select('SUM(`funds_item_amount`) as `sum`', FALSE);
			$this->fundsdb->where('funds_flow_dir', 'CHECK_OUT');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$this->fundsdb->group_by('funds_type');
			$checkoutResult = $this->fundsdb->get('funds_checkinout')->result();
			foreach($checkoutResult as $value) {
				$result[$value->funds_type]['checkout_count'] = $value->sum;
			}
			
			foreach($result as $key => $value) {
				$parameter = array(
						'log_date'						=>	$date,
						'log_active_count'			=>	$activeCount,
						'log_pay_count'				=>	intval($value['pay_count']),
						'log_payment_count'		=>	intval($value['payment_count']),
						'log_checkin_count'			=>	intval($value['checkin_count']),
						'log_item_count'				=>	intval($value['checkinitem_count']),
						'log_checkout_count'		=>	intval($value['checkout_count']),
						'game_id'						=>	$row->game_id,
						'section_id'						=>	$row->account_server_section,
						'server_id'						=>	$row->account_server_id,
						'funds_type'					=>	intval($key)
				);
				$this->logcachedb->insert('log_overview_group_by_type', $parameter);
			}
		}
	}
	
	public function statistics_daily_active() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$this->accountdb->where('game_id', $row->game_id);
			$this->accountdb->where('account_server_id', $row->account_server_id);
			$this->accountdb->where('account_server_section', $row->account_server_section);
			$this->accountdb->where('account_active <>', 0);
			$activeCount = $this->accountdb->count_all_results('game_account');
			$parameter = array(
					'log_date'						=>	$date,
					'game_id'						=>	$row->game_id,
					'section_id'						=>	$row->account_server_section,
					'server_id'						=>	$row->account_server_id,
					'cache_count'					=>	$activeCount
			);
			$this->logcachedb->insert('log_active_count_daily', $parameter);
		}
		
		$this->accountdb->set('account_active', 0);
		$this->accountdb->update('game_account');
	}
	
	public function statistics_week_active() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$currentWeek = intval(date('w', $currentTimeStamp));
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - ($currentWeek + 7) * 86400;
		$date = date('Y-m-d', $lastTimeStart);
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$this->accountdb->where('game_id', $row->game_id);
			$this->accountdb->where('account_server_id', $row->account_server_id);
			$this->accountdb->where('account_server_section', $row->account_server_section);
			$this->accountdb->where('account_active_week <>', 0);
			$activeCount = $this->accountdb->count_all_results('game_account');
			$parameter = array(
					'log_date'						=>	$date,
					'game_id'						=>	$row->game_id,
					'section_id'						=>	$row->account_server_section,
					'server_id'						=>	$row->account_server_id,
					'cache_count'					=>	$activeCount
			);
			$this->logcachedb->insert('log_active_count_week', $parameter);
		}
	
		$this->accountdb->set('account_active_week', 0);
		$this->accountdb->update('game_account');
	}
	
	public function statistics_month_active() {
		$lastTimeStart = strtotime('-1 month');
		$currentDate = date('Y-m', $lastTimeStart);
		$lastTimeStart = strtotime($currentDate . '-1 00:00:00');
		$date = date('Y-m-d', $lastTimeStart);
	
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$this->accountdb->where('game_id', $row->game_id);
			$this->accountdb->where('account_server_id', $row->account_server_id);
			$this->accountdb->where('account_server_section', $row->account_server_section);
			$this->accountdb->where('account_active_month <>', 0);
			$activeCount = $this->accountdb->count_all_results('game_account');
			$parameter = array(
					'log_date'						=>	$date,
					'game_id'						=>	$row->game_id,
					'section_id'						=>	$row->account_server_section,
					'server_id'						=>	$row->account_server_id,
					'cache_count'					=>	$activeCount
			);
			$this->logcachedb->insert('log_active_count_month', $parameter);
		}
	
		$this->accountdb->set('account_active_month', 0);
		$this->accountdb->update('game_account');
	}

	public function update_from_ruby() {
		$gameId = $this->input->get_post('game_id', TRUE);
		$sectionId = $this->input->get_post('server_section', TRUE);
		$serverId = $this->input->get_post('server_id', TRUE);
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
		if(!empty($gameId) && !empty($serverName) && is_numeric($regCount) && is_numeric($modifyCount) && is_numeric($loginCount)) {
			/*
			 * 检测参数合法性
			*/
			$authKey = $this->config->item('game_auth_key');
			$this->load->model('param_check');
			$this->load->model('websrv/update_ruby', 'ruby');
			
			$authToken	=	$authKey[$gameId]['auth_key'];
			$check = array($gameId, $serverName, $regCount, $modifyCount, $loginCount);
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
				'game_id'			=>	$gameId,
				'server_section'	=>	$sectionId,
				'server_id'			=>	$serverId,
				'server_name'		=>	$serverName,
				'reg_account'		=>	$regCount,
				'modify_account'=>	$modifyCount,
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
