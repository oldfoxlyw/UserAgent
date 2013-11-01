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
		$partner	=	$this->input->post('partner', TRUE);
		$mode		=	$this->input->post('mode', TRUE);
		$lang		=	$this->input->post('language', TRUE);

		$parameter = array(
			'use_cache_style'		=>	true,
			'order_by'					=>	'server_sort'
		);
		
		if($partner===FALSE || empty($partner))
		{
			$partner = 'default';
		}
		else
		{
			$parameter['partner'] = $partner;
		}
		
		if($mode===FALSE || empty($mode))
		{
			$parameter['server_debug'] = 0;
		}
		elseif($mode=='debug')
		{
			$parameter['server_debug'] = 1;
		}
		elseif($mode=='all')
		{
			$parameter['server_mode'] = $mode;
		}
		else
		{
			$parameter['server_debug'] = 0;
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
		
		$this->load->model('websrv/announcement');
		$announce = $this->announcement->getAllResult(null, 1);
		$announce = empty($announce) ? '' : $announce[0];
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'server'				=>	$result,
			'announce'		=>	$announce
		);
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
