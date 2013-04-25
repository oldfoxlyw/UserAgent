<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('websrv/status', 'status');
	}

	public function server_list($format = 'json') {
		$partner	=	$this->input->get_post('partner', TRUE);
		$mode		=	$this->input->get_post('mode', TRUE);
		$lang		=	$this->input->get_post('language', TRUE);

		// $status = $this->status->read();
		// if($status->server_status != '1')
		// {
		// 	$jsonData = array(
		// 		'message'	=>	'SERVER_CLOSED',
		// 		'text'		=>	$status->message
		// 	);
		// 	echo $this->return_format->format($jsonData, $format);
		// 	exit();
		// }
		
		if($partner===FALSE || empty($partner)) {
			$partner = '';
		}
		
		if($mode===FALSE || empty($mode)) {
			$mode = 'normal';
		}
		
		switch($lang) {
			case 'CN':
				$lang = 'zh-cn';
				break;
			case 'EN':
				$lang = 'english';
				break;
			default:
				$lang = 'zh-cn';
		}

		//不使用缓存
		$this->load->model('websrv/server', 'server');
		$parameter = array(
			'use_cache_style'			=>	true,
			'server_mode'				=>	$mode,
			'order_by'					=>	'server_sort'
		);
		$result = $this->server->getAllResult($parameter);

		$this->lang->load('server_list', $lang);
		$this->load->helper('language');
		foreach($result as $value) {
			$serverName = lang('server_list_' . $value->server_name);
			if(!empty($serverName)) {
				$value->server_name = $serverName;
			}
			$value->server_language = lang('server_list_language_' . $value->server_language);
		}
		
		$jsonData = Array(
			'message'	=>	'SERVER_LIST_SUCCESS',
			'server'	=>	$result
		);
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
