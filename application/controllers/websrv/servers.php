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
	
	public function server_list($format = 'json')
	{
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
		
		$productdb = $this->load->database('productdb', TRUE);
		$sql = "SELECT `server_id` FROM `server_balance_check` WHERE `next_active` = 1";
		$next = $productdb->query($sql)->row();
		$next = intval($next->server_id);

		$this->load->helper('array');
		for($i = 0; $i<count($jsonData['server']); $i++)
		{
			$jsonData['server'][$i]['server_recommend'] = 0;
			$ipArray = $jsonData['server'][$i]['server_ip'];
			$ip = random_element($ipArray);
			$jsonData['server'][$i]['server_ip'] = $ip['ip'];
		}
		$jsonData['server'][$next]['server_recommend'] = 1;
		
		//3\4
// 		if($next >= 1)
// 		{
// 			$nextServer = 0;
// 		}
// 		else
// 		{
// 			$nextServer = $next + 1;
// 		}
		//3\4\5
// 		if($next >= 2)
// 		{
// 			$nextServer = 0;
// 		}
// 		else
// 		{
// 			$nextServer = $next + 1;
// 		}
		//3\4\5\6
		if($next >= 3)
		{
			$nextServer = 0;
		}
		else
		{
			$nextServer = $next + 1;
		}
		//3\4\5\6\7
// 		if($next >= 4)
// 		{
// 			$nextServer = 0;
// 		}
// 		else
// 		{
// 			$nextServer = $next + 1;
// 		}
		//3\4\5\6\7\8
// 		if($next >= 5)
// 		{
// 			$nextServer = 0;
// 		}
// 		else
// 		{
// 			$nextServer = $next + 1;
// 		}

		$sql = "UPDATE `server_balance_check` SET `next_active` = 1 WHERE `server_id`={$nextServer}";
		$productdb->query($sql);
		$sql = "UPDATE `server_balance_check` SET `next_active` = 0 WHERE `server_id`={$next}";
		$productdb->query($sql);
		
		$announcement = $this->config->item('game_announcement');
		$jsonData = array_merge($jsonData, $announcement);
		
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