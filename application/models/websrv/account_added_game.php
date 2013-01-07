<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_added_game extends CI_Model {
	private $accountTable = 'account_added_game';
	private $accountdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
	}
	
	public function getTotal($parameter = null) {
		if($parameter != null)
		{
			if(!empty($parameter['guid'])) {
				$this->accountdb->where('GUID', $parameter['guid']);
			}
			if(!empty($parameter['game_id'])) {
				$this->accountdb->where('game_id', $parameter['game_id']);
			}
		}
		return $this->accountdb->count_all_results($this->accountTable);
	}
	
	public function getAllResult($parameter = null, $limit = 0, $offset = 0, $type = 'data') {
		if($parameter != null)
		{
			if(!empty($parameter['guid'])) {
				$this->accountdb->where('GUID', $parameter['guid']);
			}
			if(!empty($parameter['game_id'])) {
				$this->accountdb->where('game_id', $parameter['game_id']);
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
	
	public function get($guid, $gameId) {
		if(!empty($guid) && !empty($gameId)) {
			$this->accountdb->where('GUID', $guid);
			$this->accountdb->where('game_id', $gameId);
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