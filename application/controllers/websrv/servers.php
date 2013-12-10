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

		$parameter = array(
			'order_by'			=>	'server_sort'
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

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);

		$ip = $this->input->ip_address();
		$specialIp = $this->config->item('special_ip');
		
		if($specialIp)
		{
			$parameter = array(
					'special_ip'	=>	$ip
			);
			$specialResult = $this->server->getAllResult($parameter);
			if(!empty($specialResult))
			{
				$result = array_merge($result, $specialResult);
			}
		}

		$this->lang->load('server_list', $lang);
		$this->load->helper('language');
		$this->load->helper('array');
		if(!empty($result))
		{
			for($i=0; $i<count($result); $i++)
			{
				$serverName = lang('server_list_' . $result[$i]->server_name);
				if(!empty($serverName)) {
					$result[$i]->server_name = $serverName;
				}
				$result[$i]->server_language = lang('server_list_language_' . $result[$i]->server_language);
				
				$result[$i]->server_ip = json_decode($result[$i]->server_ip);
				if(count($result[$i]->server_ip) > 0)
				{
					$result[$i]->server_ip = random_element($result[$i]->server_ip);
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip[0];
				}
				$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
	
				$result[$i]->server_game_ip = json_decode($result[$i]->server_game_ip);
				if(count($result[$i]->server_game_ip) > 0)
				{
					$result[$i]->server_game_ip = random_element($result[$i]->server_game_ip);
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip[0];
				}
				$result[$i]->server_game_port = $result[$i]->server_game_ip->port;
				$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('websrv/announcement');
		$announce = $this->announcement->getAllResult(null, 1);
		$announce = empty($announce) ? '' : $announce[0];
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
