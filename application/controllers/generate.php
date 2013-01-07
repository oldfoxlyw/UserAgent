<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generate extends CI_Controller {
	private $root_path = null;
	
	public function index() {
		$this->load->helper('security');
		$this->load->library('Guid');
		echo do_hash($this->guid->toString());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */