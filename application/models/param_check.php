<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Param_check extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function check($parameter, $authkey) {
		$this->load->helper('security');
		$checkCode = $this->input->get_post('code', TRUE);
		$paramString = implode('|||', $parameter) . '|||' . $authkey;
		$paramString = do_hash($paramString);
		return $checkCode == $paramString;
	}
}
?>