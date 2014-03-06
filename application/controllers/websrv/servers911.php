<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers911 extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('websrv/status', 'status');
	}
	
	public function server_list($format = 'json') {
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		
		$partner	=	$this->input->get_post('partner', TRUE);
		$mode		=	$this->input->get_post('mode', TRUE);
		$lang		=	$this->input->get_post('language', TRUE);
		$ver		=	$this->input->get_post('client_version', TRUE);
		
// 		if($partner != 'default' && $partner != 'default_full' && $mode != 'debug')
// 		{
// 			$jsonData = Array(
// 					'errors'			=>	'《冰火王座》精英封测已于2014年1月15日圆满结束，请耐心等待公测的到来！'
// 			);
// 			echo $this->return_format->format($jsonData, $format);
// 			exit();
// 		}
		
		$parameter = array(
			'order_by'			=>	'server_sort'
		);
		
		if($partner===FALSE || empty($partner))
		{
			$partner = 'default';
		}
		else if($partner == 'default_full')
		{
			$this->get_temp_hd_list();
			exit();
		}
		else if($partner != 'default')
		{
			$this->get_sdk_debug_list();
			exit();
// 			$jsonData = Array(
// 					'errors'			=>	'《冰火王座》精英封测已于2014年1月15日圆满结束，请前往App Store下载最新客户端。'
// 			);
// 			echo $this->return_format->format($jsonData, $format);
// 			exit();
		}
		else
		{
			$parameter['partner'] = $partner;
		}
		
		if(!empty($ver) && $ver == '1.1')
		{
			$this->get_temp_version_list();
			exit();
		}
		
		if($mode===FALSE || empty($mode))
		{
			$parameter['server_debug'] = 0;
		}
		elseif($mode=='debug')
		{
// 			$parameter['server_debug'] = 1;
			$parameter['server_mode'] = 'all';
		}
		elseif($mode=='all')
		{
			$jsonData = Array(
					'errors'			=>	'《冰火王座》精英封测已于2014年1月15日圆满结束，请前往App Store下载最新客户端。'
			);
			echo $this->return_format->format($jsonData, $format);
			exit();
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
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
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
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	$partner
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, $format);
	}
	
	private function get_temp_hd_list()
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	'110'
		);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		
		$lang = 'zh-cn';

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
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
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
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	'default_full'
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
	
	private function get_temp_version_list()
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	'109'
		);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		
		$lang = 'zh-cn';

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
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
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
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	'default_full'
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
	
	private function get_sdk_debug_list()
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	'109'
		);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		
		$lang = 'zh-cn';

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
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
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
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	'default_full'
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
}
?>