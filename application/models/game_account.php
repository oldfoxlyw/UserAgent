<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Game_account extends CI_Model {
	private $accountTable = 'game_account';
	private $accountdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
	}
	
	public function getTotal($parameter = null) {
		if($parameter != null)
		{
			if(!empty($parameter['account_guid'])) {
				$this->accountdb->where('account_guid', $parameter['account_guid']);
			}
			if(!empty($parameter['game_id'])) {
				$this->accountdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_id'])) {
				$this->accountdb->where('account_server_id', $parameter['account_server_id']);
			}
			if(!empty($parameter['account_server_section'])) {
				$this->accountdb->where('account_server_section', $parameter['account_server_section']);
			}
		}
		return $this->accountdb->count_all_results($this->accountTable);
	}
	
	public function getAllResult($parameter = null, $limit = 0, $offset = 0, $type = 'data') {
		if($parameter != null)
		{
			if(!empty($parameter['account_guid'])) {
				$this->accountdb->where('account_guid', $parameter['account_guid']);
			}
			if(!empty($parameter['game_id'])) {
				$this->accountdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_id'])) {
				$this->accountdb->where('account_server_id', $parameter['account_server_id']);
			}
			if(!empty($parameter['account_server_section'])) {
				$this->accountdb->where('account_server_section', $parameter['account_server_section']);
			}
		}
		if($limit==0 && $offset==0) {
			$query = $this->accountdb->get($this->accountTable);
		} else {
			$query = $this->accountdb->get($this->accountTable, $limit, $offset);
		}
		if($query->num_rows() > 0) {
			if($type=='data') {
				return $query->result();
			} elseif($type=='json') {
				
			}
		} else {
			return false;
		}
	}
	
	public function get($id) {
		if(!empty($id)) {
			$this->accountdb->where('account_id', $id);
			$query = $this->accountdb->get($this->accountTable);
			if($query->num_rows() > 0) {
				return $query->row();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function insert($row) {
		if(!empty($row)) {
			return $this->accountdb->insert($this->accountTable, $row);
		} else {
			return false;
		}
	}

	public function update($row, $id) {
		if(!empty($row)) {
			$this->accountdb->where('account_id', $id);
			return $this->accountdb->update($this->accountTable, $row);
		} else {
			return false;
		}
	}
	
	public function delete($id) {
		if(!empty($id)) {
			$this->accountdb->where('account_id', $id);
			return $this->accountdb->delete($this->accountTable);
		} else {
			return false;
		}
	}
}
?>