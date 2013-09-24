<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mpartner extends CI_Model {
	private $accountTable = 'scc_partner';
	private $webdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->webdb = $this->load->database('webdb', true);
	}
	
	public function getTotal($parameter = null) {
		return $this->webdb->count_all_results($this->accountTable);
	}
	
	public function getAllResult($parameter = null) {
		$result = $this->webdb->get($this->accountTable);
		if($result->num_rows() > 0)
		{
			return $result->result();
		} else {
			return false;
		}
	}
}
?>
