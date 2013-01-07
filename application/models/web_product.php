<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Web_product extends CI_Model {
	private $productTable = 'web_product';
	private $accountdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->accountdb = $this->load->database('accountdb', true);
	}
	
	public function get_encrypt_key($product_id) {
		if(is_numeric($product_id)) {
			$this->accountdb->where('product_id', $product_id);
			$result = $this->accountdb->get($this->productTable);
			if($result->num_rows() > 0) {
				$row = $result->row();
				return $row->product_encrypt_key;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>