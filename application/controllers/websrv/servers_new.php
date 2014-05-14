<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers_new extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->load->model('return_format');
	}
	
	public function server_list($format = 'json')
	{

	}
}