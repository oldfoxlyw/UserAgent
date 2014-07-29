<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mall extends CI_Controller {
	private $logcachedb = null;
	private $logdb = null;
	private $fundsdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
		$this->logcachedb = $this->load->database('log_cachedb', true);
		$this->logdb = $this->load->database('logdb', true);
		$this->fundsdb = $this->load->database('fundsdb', true);
		date_default_timezone_set('Etc/GMT+3');
	}
	
	public function props_sell() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$this->logdb->select('`log_get_item_id`, `log_get_item_name`, SUM(`log_get_item_count`) AS `log_get_item_count`, SUM(`log_spend_item_count`) AS `log_spend_item_count`', FALSE);
			$this->logdb->where('log_type', 'shop_buy');
			$this->logdb->where('log_time >', $lastTimeStart);
			$this->logdb->where('log_time <=', $lastTimeEnd);
			$this->logdb->where('game_id', $row->game_id);
			$this->logdb->where('server_section', $row->account_server_section);
			$this->logdb->where('server_id', $row->account_server_id);
			$this->logdb->group_by('log_get_item_name');
			$result = $this->logdb->get('log_mall')->result();
			
			foreach($result as $value) {
				$parameter = array(
					'log_date'						=>	$date,
					'game_id'						=>	$row->game_id,
					'section_id'						=>	$row->account_server_section,
					'server_id'						=>	$row->account_server_id,
					'item_id'							=>	$value->log_get_item_id,
					'item_name'					=>	$value->log_get_item_name,
					'item_count'					=>	$value->log_get_item_count,
					'item_fee'						=>	$value->log_spend_item_count
				);
				$this->logcachedb->insert('log_props_sell', $parameter);
			}
		}
	}
}
?>