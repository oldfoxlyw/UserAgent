<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
	private $logdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->logdb = $this->load->database('logdb', true);
	}
	
	public function register_per_minutes() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$sql = "INSERT INTO `agent_log_db`.`log_register_count_per_minutes` SELECT
		FROM_UNIXTIME(`log_time`, '%Y')AS `cache_year`,
		FROM_UNIXTIME(`log_time`, '%c')AS `cache_month`,
		FROM_UNIXTIME(`log_time`, '%e')AS `cache_date`,
		FROM_UNIXTIME(`log_time`, '%k')AS `cache_hour`,
		FROM_UNIXTIME(`log_time`, '%i')AS `cache_minutes`,
		`game_id`,
		`section_id`,
		`server_id`,
		`platform`,
		COUNT(`log_id`)AS `cache_count`
	FROM
		`log_account`
	WHERE
		(`log_action` = 'ACCOUNT_REGISTER_SUCCESS' OR `log_action` = 'ACCOUNT_DEMO_SUCCESS')
		AND `game_id` = '{$row->game_id}'
		AND `section_id` = '{$row->account_server_section}'
		AND `server_id` = '{$row->account_server_id}'
		AND `log_time` > {$lastTimeStart}
		AND `log_time` <= {$lastTimeEnd}
	GROUP BY
		`cache_year`,
		`cache_month`,
		`cache_date`,
		`cache_hour`,
		`cache_minutes`,
		`platform`";
			
			$this->logdb->query($sql);
		}
	}
	
	public function register_per_date() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
		
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$sql = "INSERT INTO `agent_log_db`.`log_register_count_per_date` SELECT
		FROM_UNIXTIME(`log_time`, '%Y')AS `cache_year`,
		FROM_UNIXTIME(`log_time`, '%c')AS `cache_month`,
		FROM_UNIXTIME(`log_time`, '%e')AS `cache_date`,
		`game_id`,
		`section_id`,
		`server_id`,
		`platform`,
		COUNT(`log_id`)AS `cache_count`
	FROM
		`log_account`
	WHERE
		(`log_action` = 'ACCOUNT_REGISTER_SUCCESS' OR `log_action` = 'ACCOUNT_DEMO_SUCCESS')
		AND `game_id` = '{$row->game_id}'
		AND `section_id` = '{$row->account_server_section}'
		AND `server_id` = '{$row->account_server_id}'
		AND `log_time` > {$lastTimeStart}
		AND `log_time` <= {$lastTimeEnd}
	GROUP BY
		`cache_year`,
		`cache_month`,
		`cache_date`,
		`platform`";
			
			$this->logdb->query($sql);
		}
	}
	
	public function login_per_minutes() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
	
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$sql = "INSERT INTO `agent_log_db`.`log_login_count_per_minutes` SELECT
			FROM_UNIXTIME(`log_time`, '%Y')AS `cache_year`,
			FROM_UNIXTIME(`log_time`, '%c')AS `cache_month`,
			FROM_UNIXTIME(`log_time`, '%e')AS `cache_date`,
			FROM_UNIXTIME(`log_time`, '%k')AS `cache_hour`,
			FROM_UNIXTIME(`log_time`, '%i')AS `cache_minutes`,
			`game_id`,
			`section_id`,
			`server_id`,
			`platform`,
			COUNT(`log_id`)AS `cache_count`
			FROM
			`log_account`
			WHERE
			`log_action` = 'ACCOUNT_LOGIN_SUCCESS'
			AND `game_id` = '{$row->game_id}'
			AND `section_id` = '{$row->account_server_section}'
			AND `server_id` = '{$row->account_server_id}'
			AND `log_time` > {$lastTimeStart}
			AND `log_time` <= {$lastTimeEnd}
			GROUP BY
			`cache_year`,
			`cache_month`,
			`cache_date`,
			`cache_hour`,
			`cache_minutes`,
			`platform`";
				
			$this->logdb->query($sql);
		}
	}
	
	public function login_per_date() {
		$currentTimeStamp = time();
		$currentDate = date('Y-m-d', $currentTimeStamp);
		$lastTimeStart = strtotime($currentDate . ' 00:00:00') - 86400;
		$lastTimeEnd = strtotime($currentDate . ' 23:59:59') - 86400;
		$date = date('Y-m-d', $lastTimeStart);
	
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row) {
			$sql = "INSERT INTO `agent_log_db`.`log_login_count_per_date` SELECT
			FROM_UNIXTIME(`log_time`, '%Y')AS `cache_year`,
			FROM_UNIXTIME(`log_time`, '%c')AS `cache_month`,
			FROM_UNIXTIME(`log_time`, '%e')AS `cache_date`,
			`game_id`,
			`section_id`,
			`server_id`,
			`platform`,
			COUNT(`log_id`)AS `cache_count`
			FROM
			`log_account`
			WHERE
			`log_action` = 'ACCOUNT_LOGIN_SUCCESS'
			AND `game_id` = '{$row->game_id}'
			AND `section_id` = '{$row->account_server_section}'
			AND `server_id` = '{$row->account_server_id}'
			AND `log_time` > {$lastTimeStart}
			AND `log_time` <= {$lastTimeEnd}
			GROUP BY
			`cache_year`,
			`cache_month`,
			`cache_date`,
			`platform`";
				
			$this->logdb->query($sql);
		}
	}
}
?>
