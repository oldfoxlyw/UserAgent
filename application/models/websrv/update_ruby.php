<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_ruby extends CI_Model {
	private $accountTable = 'log_daily_statistics';
	private $logcachedb = null;
	
	public function __construct() {
		parent::__construct();
		$this->logcachedb = $this->load->database('log_cachedb', true);
	}
	
	public function insert($row) {
		if(!empty($row)) {
			return $this->logcachedb->insert($this->accountTable, $row);
		} else {
			return false;
		}
	}
}
?>
