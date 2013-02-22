<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Password extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
	}
	
	public function check($format = 'json') {
		$pass = $this->input->get('pass');
		
		$this->load->helper('security');
		echo strtoupper(do_hash(do_hash($pass, 'md5') . do_hash($pass), 'md5'));
	}
}
?>
