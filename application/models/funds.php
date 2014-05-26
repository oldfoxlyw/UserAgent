<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Funds extends CI_Model {
	private $fundsCheckInOut = 'funds_checkinout';
	private $fundsdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->fundsdb = $this->load->database('fundsdb', true);
	}
	
	public function getTotal($parameter = null) {
		if($parameter != null)
		{
			if(!empty($parameter['account_guid'])) {
				$this->fundsdb->where('account_guid', $parameter['account_guid']);
			}
			if(!empty($parameter['account_id'])) {
				$this->fundsdb->where('account_id', $parameter['account_id']);
			}
			if(!empty($parameter['game_id'])) {
				$this->fundsdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['server_id'])) {
				$this->fundsdb->where('server_id', $parameter['server_id']);
			}
			if(!empty($parameter['section_id'])) {
				$this->fundsdb->where('section_id', $parameter['section_id']);
			}
			if(!empty($parameter['funds_flow_dir'])) {
				$this->fundsdb->where('funds_flow_dir', $parameter['funds_flow_dir']);
			}
		}
		return $this->fundsdb->count_all_results($this->fundsCheckInOut);
	}
	
	public function getAllResult($parameter = null, $limit = 0, $offset = 0, $type = 'data') {
		if($parameter != null)
		{
			if(!empty($parameter['account_guid'])) {
				$this->fundsdb->where('account_guid', $parameter['account_guid']);
			}
			if(!empty($parameter['account_id'])) {
				$this->fundsdb->where('account_id', $parameter['account_id']);
			}
			if(!empty($parameter['game_id'])) {
				$this->fundsdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['server_id'])) {
				$this->fundsdb->where('server_id', $parameter['server_id']);
			}
			if(!empty($parameter['section_id'])) {
				$this->fundsdb->where('server_section', $parameter['section_id']);
			}
			if(!empty($parameter['funds_flow_dir'])) {
				$this->fundsdb->where('funds_flow_dir', $parameter['funds_flow_dir']);
			}
		}
		if($limit==0 && $offset==0) {
			$query = $this->fundsdb->get($this->fundsCheckInOut);
		} else {
			$query = $this->fundsdb->get($this->fundsCheckInOut, $limit, $offset);
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
			$this->fundsdb->where('funds_id', $id);
			$query = $this->fundsdb->get($this->fundsCheckInOut);
			if($query->num_rows() > 0) {
				return $query->row();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getByOrder($id) {
		if(!empty($id)) {
			$this->fundsdb->where('order_id', $id);
			$query = $this->fundsdb->get($this->fundsCheckInOut);
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
			$this->fundsdb->insert($this->fundsCheckInOut, $row);
			return $this->fundsdb->insert_id();
		} else {
			return false;
		}
	}

	public function update($row, $id) {
		if(!empty($row)) {
			$this->fundsdb->where('funds_id', $id);
			return $this->fundsdb->update($this->fundsCheckInOut, $row);
		} else {
			return false;
		}
	}
	
	public function delete($id) {
		if(!empty($id)) {
			$this->fundsdb->where('funds_id', $id);
			return $this->fundsdb->delete($this->fundsCheckInOut);
		} else {
			return false;
		}
	}
}
?>