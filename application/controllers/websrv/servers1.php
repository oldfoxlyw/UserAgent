<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers1 extends CI_Controller {
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

		// if($partner == 'arab_default' || $partner == 'arab_sdk')
		// {
		// 	$this->get_sdk_debug_list('96');
		// 	exit();
		// }
		// elseif($partner == 'test_default')
		// {
		// 	$this->load->config('server_list_sdk');
		// 	$jsonData = $this->config->item('game_server_list');
		// }rtner == 'arab_default' || $partner == 'arab_sdk')
		
		// $this->load->config('server_list_default');
		// $jsonData = $this->config->item('game_server_list1');
		
		$this->get_sdk_debug_list();
		exit();

		/*
		$productdb = $this->load->database('productdb', TRUE);
		$sql = "SELECT `server_id`, `count`, `max_count` FROM `server_balance_check` WHERE `next_active` = 1 AND `type`='{$type}'";
		$next = $productdb->query($sql)->row();
		$maxCount = intval($next->max_count);
		$count = intval($next->count);
		$next = intval($next->server_id);
		*/

		$next = 0;
		$this->load->helper('array');
		for($i = 0; $i<count($jsonData['server']); $i++)
		{
			$jsonData['server'][$i]['server_recommend'] = 0;
			$ipArray = $jsonData['server'][$i]['server_ip'];
			$ip = random_element($ipArray);
			$gameIpArray = $jsonData['server'][$i]['server_game_ip'];
			$gameIp = random_element($gameIpArray);
			$jsonData['server'][$i]['server_game_ip'] = $gameIp['ip'];
			$jsonData['server'][$i]['server_game_port'] = $gameIp['port'];
		}
		if(is_array($jsonData['server']))
		{
			$jsonData['server'][$next]['server_recommend'] = 1;
		}
		/*
		if($count >= $maxCount)
		{
			if($next >= 1)
			{
				$nextServer = 0;
			}
			else
			{
				$nextServer = $next + 1;
			}
			$sql = "UPDATE `server_balance_check` SET `next_active` = 1 WHERE `next`={$nextServer}";
			$productdb->query($sql);
			$sql = "UPDATE `server_balance_check` SET `count` = 1, `next_active` = 0 WHERE `next`={$next}";
			$productdb->query($sql);
		}
		else
		{
			$sql = "UPDATE `server_balance_check` SET `count` = `count` + 1 WHERE `next`={$next}";
			$productdb->query($sql);
		}
		*/

		$announcement = $this->config->item('game_announcement');
		$jsonData = array_merge($jsonData, $announcement);
		
		echo $this->return_format->format($jsonData, $format);
	}
	
	private function get_sdk_debug_list($id = '')
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		$ipFlag = 'ip';
		$parameter = array();
		if(!empty($id))
		{
			$parameter = array(
					'account_server_id'		=>	$id
			);
		}
		$parameter['order_by'] = 'server_recommend';

		$partner = $this->input->get_post('partner', TRUE);
		if(!empty($partner))
		{
			$parameter['partner'] = $partner;
		}

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		$productdb = $this->load->database('productdb', true);
		exit($productdb->last_query());
		
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

				$result[$i]->const_server_ip = json_decode($result[$i]->const_server_ip);
				if(count($result[$i]->const_server_ip) > 0)
				{
					$result[$i]->const_server_ip = random_element($result[$i]->const_server_ip);
				}
				else
				{
					$result[$i]->const_server_ip = $result[$i]->const_server_ip[0];
				}
				if(empty($result[$i]->const_server_ip->$ipFlag))
				{
					$result[$i]->const_server_ip = $result[$i]->const_server_ip->ip . ':' . $result[$i]->const_server_ip->port;
				}
				else
				{
					$result[$i]->const_server_ip = $result[$i]->const_server_ip->$ipFlag . ':' . $result[$i]->const_server_ip->port;
				}

				$result[$i]->voice_server_ip = json_decode($result[$i]->voice_server_ip);
				if(count($result[$i]->voice_server_ip) > 0)
				{
					$result[$i]->voice_server_ip = random_element($result[$i]->voice_server_ip);
				}
				else
				{
					$result[$i]->voice_server_ip = $result[$i]->voice_server_ip[0];
				}
				if(empty($result[$i]->voice_server_ip->$ipFlag))
				{
					$result[$i]->voice_server_ip = $result[$i]->voice_server_ip->ip . ':' . $result[$i]->voice_server_ip->port;
				}
				else
				{
					$result[$i]->voice_server_ip = $result[$i]->voice_server_ip->$ipFlag . ':' . $result[$i]->voice_server_ip->port;
				}

				$result[$i]->cross_server_ip = json_decode($result[$i]->cross_server_ip);
				if(count($result[$i]->cross_server_ip) > 0)
				{
					$result[$i]->cross_server_ip = random_element($result[$i]->cross_server_ip);
				}
				else
				{
					$result[$i]->cross_server_ip = $result[$i]->cross_server_ip[0];
				}
				if(empty($result[$i]->cross_server_ip->$ipFlag))
				{
					$result[$i]->cross_server_ip = $result[$i]->cross_server_ip->ip . ':' . $result[$i]->cross_server_ip->port;
				}
				else
				{
					$result[$i]->cross_server_ip = $result[$i]->cross_server_ip->$ipFlag . ':' . $result[$i]->cross_server_ip->port;
				}

				$result[$i]->legion_message_ip = json_decode($result[$i]->legion_message_ip);
				if(count($result[$i]->legion_message_ip) > 0)
				{
					$result[$i]->legion_message_ip = random_element($result[$i]->legion_message_ip);
				}
				else
				{
					$result[$i]->legion_message_ip = $result[$i]->legion_message_ip[0];
				}
				if(empty($result[$i]->legion_message_ip->$ipFlag))
				{
					$result[$i]->legion_message_ip = $result[$i]->legion_message_ip->ip . ':' . $result[$i]->legion_message_ip->port;
				}
				else
				{
					$result[$i]->legion_message_ip = $result[$i]->legion_message_ip->$ipFlag . ':' . $result[$i]->legion_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		if($partner == 'default' || $partner == 'default_full')
		{
			//$announcement = $this->config->item('game_announcement');
		}
		else
		{
			$announcement = array(
				'announce' => array (
					'id' => '1',
					'summary' => '',
					'content' => '',
					'post_time' => '1394121601',
					'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
				)
			);
		}

		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result
		);
		$jsonData = array_merge($jsonData, $announcement);

		echo $this->return_format->format($jsonData, 'json');
	}

	public function announcement()
	{
		$server_id = $this->input->get_post('server_id', TRUE);
		$partner = $this->input->get_post('partner', TRUE);

		if(empty($server_id) || empty($partner))
		{
			$raw_post_data = file_get_contents('php://input', 'r');
			$inputParam = json_decode($raw_post_data);
			
			$server_id = $inputParam->server_id;
			$partner = $inputParam->partner;
			$code = $inputParam->code;
		}

		$db = $this->load->database('productdb', TRUE);

		$sql = "SELECT * FROM `game_announcement` WHERE `partner_key` LIKE '%{$partner}%' AND (`server_id` = '{$server_id}' OR `server_id` = 'all')";
		$announce = $db->query($sql)->result();

		$announce = empty($announce) ? '' : $announce[0];
		
		$jsonData = Array(
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
}