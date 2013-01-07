<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Game_product extends CI_Model {
	private $accountTable = 'game_product';
	private $productdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->productdb = $this->load->database('productdb', true);
	}
	
	public function getTotal($parameter = null) {
		return $this->productdb->count_all_results($this->accountTable);
	}
	
	public function getAllResult($parameter = null, $limit = 0, $offset = 0, $type = 'data') {
		if(!empty($parameter['except_game_id'])) {
			$this->productdb->where_not_in('game_id', $parameter['except_game_id']);
		}
		if($limit==0 && $offset==0) {
			$query = $this->productdb->get($this->accountTable);
		} else {
			$query = $this->productdb->get($this->accountTable, $limit, $offset);
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
			$this->productdb->where('game_id', $id);
			$query = $this->productdb->get($this->accountTable);
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
		if(is_numeric($row)) {
			return $this->productdb->insert($this->accountTable, $row);
		} else {
			return false;
		}
	}

	public function update($row, $id) {
		if(!empty($row)) {
			$this->productdb->where('game_id', $id);
			return $this->productdb->update($this->accountTable, $row);
		} else {
			return false;
		}
	}
	
	public function delete($id) {
		if(!empty($id)) {
			$this->productdb->where('game_id', $id);
			return $this->productdb->delete($this->accountTable);
		} else {
			return false;
		}
	}
}
?>