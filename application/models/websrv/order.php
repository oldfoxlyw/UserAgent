<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Model {
	private $accountTable = 'funds_order';
	private $fundsdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->fundsdb = $this->load->database('fundsdb', true);
	}
	
	public function insert($row) {
		if(!empty($row)) {
			return $this->fundsdb->insert($this->accountTable, $row);
		} else {
			return false;
		}
	}
	
	public function get($id) {
		if(!empty($id)) {
			$this->fundsdb->where('checksum', $id);
			$result = $this->fundsdb->get($this->accountTable);
			if($result->num_rows() > 0) {
				return $result->result();
			} else {
				return false;
			}
		} else {
			return fales;
		}
	}
	
	public function update($id, $parameter) {
		if(!empty($id))
		{
			$this->fundsdb->where('checksum', $id);
			$this->fundsdb->update($this->accountTable, $parameter);
		}
	}
	
	public function addCount($id) {
		if(!empty($id)) {
			$this->fundsdb->where('checksum', $id);
			$this->fundsdb->set('check_count', 'check_count+1', false);
			return $this->fundsdb->update($this->accountTable);
		} else {
			return false;
		}
	}
}
?>