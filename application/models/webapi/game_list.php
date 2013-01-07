<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Game_list extends CI_Model {
	private $accountTable = 'game_account_view';
	private $productTable = 'game_product';
	private $accountdb = null;
	private $productdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
		$this->productdb = $this->load->database('productdb', true);
	}
	
	public function getTotal($parameter = null) {
		if($parameter != null)
		{
			if(!empty($parameter['account_guid'])) {
				$this->accountdb->where('GUID', $parameter['account_guid']);
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
			if(!empty($parameter['account_guid'])) {
				$this->accountdb->where('GUID', $parameter['account_guid']);
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
	
	public function get($guid) {
		if(!empty($guid)) {
			$this->accountdb->where('GUID', $guid);
			$query = $this->accountdb->get($this->accountTable);
			if($query->num_rows() > 0) {
				return $query->result();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getGame($gameId) {
		if(!empty($gameId)) {
			$this->productdb->where('game_id', $gameId);
			$query = $this->productdb->get($this->productTable);
			if($query->num_rows() > 0) {
				return $query->row();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>