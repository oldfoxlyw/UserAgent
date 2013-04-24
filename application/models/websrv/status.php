<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Model {
	private $accountTable = 'server_status';
	private $productdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->productdb = $this->load->database('productdb', true);
	}

	public function read()
	{
		// $result = $this->productdb->get($this->accountTable);
		// if($result !== FALSE)
		// {
		// 	return $result[0];
		// }
		// else
		// {
		// 	return FALSE;
		// }
	}
}
?>
