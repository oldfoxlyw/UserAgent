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
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			//注册数
			$this->logdb->where('log_action', 'ACCOUNT_REGISTER_SUCCESS');
			$this->logdb->where('log_time >', $lastTimeStart);
			$this->logdb->where('log_time <=', $lastTimeEnd);
			$this->logdb->where('game_id', $row->game_id);
			$this->logdb->where('section_id', $row->account_server_section);
			$this->logdb->where('server_id', $row->account_server_id);
			$registerCount = $this->logdb->count_all_results('log_account');
			
			//登录数
			$this->logdb->where('log_action', 'ACCOUNT_LOGIN_SUCCESS');
			$this->logdb->where('log_time >', $lastTimeStart);
			$this->logdb->where('log_time <=', $lastTimeEnd);
			$this->logdb->where('game_id', $row->game_id);
			$this->logdb->where('section_id', $row->account_server_section);
			$this->logdb->where('server_id', $row->account_server_id);
			$loginCount = $this->logdb->count_all_results('log_account');

			$this->accountdb->where('game_id', $row->game_id);
			$this->accountdb->where('account_server_id', $row->account_server_id);
			$this->accountdb->where('account_server_section', $row->account_server_section);
			$this->accountdb->where('account_active <>', 0);
			$activeCount = $this->accountdb->count_all_results('game_account');
			
			//付费人数
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$this->fundsdb->group_by('account_guid');
			$payCount = $this->fundsdb->count_all_results('funds_checkinout');
			
			//付费次数
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$paymentCount = $this->fundsdb->count_all_results('funds_checkinout');
			
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
			
			//总充值游戏币
			$this->fundsdb->select_sum('funds_item_amount');
			$this->fundsdb->where('funds_flow_dir', 'CHECK_IN');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$checkResult = $this->fundsdb->get('funds_checkinout')->row();
			$checkinItemCount = intval($checkResult->funds_item_amount);
			
			//总消费金额
			$this->fundsdb->select_sum('funds_item_amount');
			$this->fundsdb->where('funds_flow_dir', 'CHECK_OUT');
			$this->fundsdb->where('funds_time >', $lastTimeStart);
			$this->fundsdb->where('funds_time <=', $lastTimeEnd);
			$this->fundsdb->where('game_id', $row->game_id);
			$this->fundsdb->where('server_id', $row->account_server_id);
			$this->fundsdb->where('server_section', $row->account_server_section);
			$checkResult = $this->fundsdb->get('funds_checkinout')->row();
			$checkoutCount = intval($checkResult->funds_item_amount);
			
			$parameter = array(
				'log_date'						=>	$date,
				'log_register_count'		=>	$registerCount,
				'log_login_count'			=>	$loginCount,
				'log_active_count'			=>	$activeCount,
				'log_pay_count'				=>	$payCount,
				'log_payment_count'		=>	$paymentCount,
				'log_checkin_count'			=>	$checkinCount,
				'log_item_count'				=>	$checkinItemCount,
				'log_checkout_count'		=>	$checkoutCount,
				'game_id'						=>	$row->game_id,
				'section_id'						=>	$row->account_server_section,
				'server_id'						=>	$row->account_server_id
			);
			$this->logcachedb->insert('log_overview', $parameter);
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
