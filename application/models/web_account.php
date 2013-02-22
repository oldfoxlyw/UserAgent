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
	
	public function validate($userName, $userPass) {
		if(!empty($userName) && !empty($userPass)) {
			$this->load->helper('security');
			$userPass = $this->encrypt_pass($userPass);
			$this->accountdb->where('account_name', trim($userName));
			$this->accountdb->where('account_pass', $userPass);
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
	
	public function validate_duplicate($userName, $userPass, $useEncrypt = true) {
		$this->load->helper('security');
		$this->accountdb->where('account_name', trim($userName));
		if($useEncrypt) {
			$userPass = $this->encrypt_pass($userPass);
		}
		$this->accountdb->where('account_pass', $userPass);
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
			/*
			$this->load->library('encrypt');
			$encrypt_key = $this->web_product->get_encrypt_key($pid);
			$encrypt_key = do_hash(do_hash($encrypt_key, 'md5') . $encrypt_key, 'md5');
			$secret_key = $this->encrypt->encode($parameter['pass'], $encrypt_key);
			*/
			
			$parameter['pass'] = $this->encrypt_pass($parameter['pass']);
			$insertArray = array(
				'account_name'			=>	$parameter['name'],
				'account_pass'			=>	$parameter['pass'],
				'account_email'			=>	$parameter['email'],
				'account_country'		=>	$parameter['country'],
				'account_firstname'		=>	$parameter['firstname'],
				'account_lastname'		=>	$parameter['lastname'],
				'account_pass_question'	=>	$parameter['question'],
				'account_pass_answer'	=>	$parameter['answer'],
				'account_regtime'		=>	time()
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
		if(!empty($parameter['game_id'])) {
			$this->accountdb->where('game_id', $parameter['game_id']);
		}
		if(!empty($parameter['server_id'])) {
			$this->accountdb->where('server_id', $parameter['server_id']);
		}
		if(!empty($parameter['server_section'])) {
			$this->accountdb->where('server_section', $parameter['server_section']);
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
	
	public function db() {
		return $this->accountdb;
	}
}
?>
