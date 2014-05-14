<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers_new extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->load->model('return_format');
	}
	
	public function server_list($format = 'json')
	{
		$partner	=	$this->input->get_post('partner', TRUE);
		$mode		=	$this->input->get_post('mode', TRUE);
		$lang		=	$this->input->get_post('language', TRUE);
		$ver		=	$this->input->get_post('client_version', TRUE);

		if($mode == 'pub')
		$this->load->config('game_server_list');
		
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			//3\4\5\6\7\8
			$jsonData = $this->config->item('game_server_list2');
		}
		else //183.60.255.55
		{
			//3\4\5\6\7\8
			$jsonData = $this->config->item('game_server_list1');
		}
	}
}