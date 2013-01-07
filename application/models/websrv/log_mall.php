<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log_mall extends CI_Model {
	private $accountTable = 'log_mall';
	private $logdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->logdb = $this->load->database('logdb', true);
	}
	
	public function insert($row) {
		if(!empty($row)) {
			return $this->logdb->insert($this->accountTable, $row);
		} else {
			return false;
		}
	}
}
?>