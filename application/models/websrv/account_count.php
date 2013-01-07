<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_count extends CI_Model {
	private $accountTable = 'server_list';
	private $productdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->productdb = $this->load->database('productdb', true);
	}
	
	public function update($row, $parameter) {
		if($parameter != null) {
			if(!empty($parameter['game_id'])) {
				$this->productdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_id'])) {
				$this->productdb->where('account_server_id', $parameter['account_server_id']);
			}
			if(!empty($parameter['account_server_section'])) {
				$this->productdb->where('account_server_section', $parameter['account_server_section']);
			}
			return $this->productdb->update($this->accountTable, $row);
		} else {
			return FALSE;
		}
	}
	
	public function getTotal($parameter = null) {
		if($parameter != null) {
			if(!empty($parameter['game_id'])) {
				$this->productdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_id'])) {
				$this->productdb->where('account_server_id', $parameter['account_server_id']);
			}
			if(!empty($parameter['account_server_section'])) {
				$this->productdb->where('account_server_section', $parameter['account_server_section']);
			}
		}
		$result = $this->productdb->get($this->accountTable);
		if($result != FALSE)
		{
			$row = $result->row();
			return intval($row->account_count);
		} else {
			return 0;
		}
	}
	
	public function getNextAvailableId($parameter = null) {
		if($parameter != null) {
			if(!empty($parameter['game_id'])) {
				$this->productdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_id']) || $parameter['account_server_id']==='0' || $parameter['account_server_id']===0) {
				$this->productdb->where('account_server_id', $parameter['account_server_id']);
			}
			if(!empty($parameter['account_server_section']) || $parameter['account_server_section']==='0' || $parameter['account_server_section']===0) {
				$this->productdb->where('account_server_section', $parameter['account_server_section']);
			}
		}
		$result = $this->productdb->get($this->accountTable);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$value = intval($row->account_count) + 1;
			$param = array(
				'account_count'	=>	$value
			);
			$this->update($param, $parameter);
			return $value;
		} else {
			return -1;
		}
	}
}
?>