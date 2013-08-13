<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Announcement extends CI_Model {
	private $accountTable = 'game_announcement';
	private $productdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->productdb = $this->load->database('productdb', true);
	}
	
	public function getTotal($parameter = null) {
		return $this->productdb->count_all_results($this->accountTable);
	}
	
	public function getAllResult($parameter = null, $limit = 0) {
		$this->productdb->order_by('post_time', 'desc');
		if(!empty($limit))
		{
			$result = $this->productdb->get($this->accountTable, $limit);
		}
		else
		{
			$result = $this->productdb->get($this->accountTable);
		}
		if($result->num_rows() > 0)
		{
			return $result->result();
		} else {
			return false;
		}
	}
}
?>
