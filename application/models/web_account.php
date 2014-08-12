<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Web_account extends CI_Model {
	private $accountTable = 'web_account';
	private $accountdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
		$this->load->model('web_product');
		$this->load->library('Guid');
	}
	
	public function validate($userName, $userPass, $serverId) {
		if(!empty($userName) && !empty($userPass) && !empty($serverId)) {
			$this->load->helper('security');
			$userPass = $this->encrypt_pass($userPass);
			$this->accountdb->where('account_name', trim($userName));
			$this->accountdb->where('account_pass', $userPass);
			$this->accountdb->where('server_id', $serverId);
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
	
	public function validate_duplicate($userName, $userPass, $serverId, $useEncrypt = true) {
		$this->load->helper('security');
		$this->accountdb->where('account_name', trim($userName));
		if($useEncrypt) {
			$userPass = $this->encrypt_pass($userPass);
		}
		$this->accountdb->where('account_pass', $userPass);
		$this->accountdb->where('server_id', $serverId);
		$query = $this->accountdb->get($this->accountTable);
		if($query->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function encrypt_pass($pass) {
		$this->load->helper('security');
		return strtoupper(do_hash(do_hash($pass, 'md5') . do_hash($pass), 'md5'));
	}
	
	public function register($parameter) {
		if(!empty($parameter['name']) &&
		!empty($parameter['pass'])) {
			$this->load->helper('security');
			$parameter['pass'] = $this->encrypt_pass($parameter['pass']);
			$parameter['status'] = ($parameter['status'] == 0 || $parameter['status'] == 1) ? $parameter['status'] : 1;
			$parameter['partner'] = empty($parameter['partner']) ? 'default' : $parameter['partner'];
			$parameter['device_id'] = empty($parameter['device_id']) ? '' : $parameter['device_id'];
			$time = time();
			$insertArray = array(
				'account_name'				=>	$parameter['name'],
				'account_pass'				=>	$parameter['pass'],
				'server_id'					=>	$parameter['server_id'],
				'account_email'				=>	$parameter['email'],
				'account_pass_question'		=>	$parameter['question'],
				'account_pass_answer'		=>	$parameter['answer'],
				'account_regtime'			=>	$time,
				'account_lastlogin'			=>	$time,
				'account_status'			=>	$parameter['status'],
				'partner_key'				=>	$parameter['partner'],
				'device_id'					=>	$parameter['device_id']
			);
			if($this->accountdb->insert($this->accountTable, $insertArray)) {
				return $this->accountdb->insert_id();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function get($id) {
		if(!empty($id)) {
			$this->accountdb->where('GUID', $id);
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
	
	public function getAllResult($parameter = null, $limit = 0, $offset = 0) {
		if(!empty($parameter['account_name']) && !empty($parameter['account_pass'])) {
			$this->accountdb->where('account_name', $parameter['account_name']);
			$this->accountdb->where('account_pass', $parameter['account_pass']);
		}
		if(!empty($parameter['server_id'])) {
			$this->accountdb->where('server_id', $parameter['server_id']);
		}
		$query = $this->accountdb->get($this->accountTable);
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function update($row, $guid) {
		if(!empty($row)) {
			$this->accountdb->where('GUID', $guid);
			return $this->accountdb->update($this->accountTable, $row);
		} else {
			return false;
		}
	}
	
	public function read($parameter = null, $extension = null, $limit = 0, $offset = 0)
	{
		if(!empty($parameter))
		{
			foreach($parameter as $key=>$value)
			{
				$this->accountdb->where($key, $value);
			}
		}
		if(!empty($extension))
		{
			if(!empty($extension['select']))
			{
				$this->accountdb->select($extension['select']);
			}
			if(!empty($extension['order_by']))
			{
				$this->accountdb->order_by($extension['order_by'][0], $extension['order_by'][1]);
			}
			if(!empty($extension['where_in']))
			{
				$this->accountdb->where_in($extension['where_in'][0], $extension['where_in'][1]);
			}
		}
		if($limit==0 && $offset==0) {
			$query = $this->accountdb->get($this->accountTable);
		} else {
			$query = $this->accountdb->get($this->accountTable, $limit, $offset);
		}
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function create($row)
	{
		if(!empty($row))
		{
			if($this->accountdb->insert($this->accountTable, $row))
			{
				return $this->accountdb->insert_id();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function db() {
		return $this->accountdb;
	}
}
?>
