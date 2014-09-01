<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
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
		// }
		if($mode == 'pub' && ($partner == 'default' || $partner == 'default_full') && $ver == '1.0')
		{
			$this->get_sdk_debug_list('93');
			exit();
		}
		else
		{
			$jsonData = array();
		}

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
			$jsonData['server'][$i]['server_ip'] = $ip['ip'];

			$ipArray = $jsonData['server'][$i]['server_game_ip'];
			if(is_array($ipArray))
			{
				$ip = random_element($ipArray);
				$ip = $ip['ip'];
				$ipArray = explode(':', $ip);
				$jsonData['server'][$i]['server_game_ip'] = $ipArray[0];
				$jsonData['server'][$i]['server_game_port'] = $ipArray[1];
			}
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
	
	private function get_sdk_debug_list($id = '97')
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		$partner	=	$this->input->get_post('partner', TRUE);
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	$id
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
			}
		}
		else
		{
			$result = array();
		}
		
		if($partner == 'default' || $partner == 'default_full')
		{
			$announcement = $this->config->item('game_announcement');
		}
		else
		{
			$announcement = array(
				'announce' => array (
					'id' => '1',
					'summary' => '《审判王座》最新通告

超人气手游《审判王座》正式入侵APPstore，并且已经开放免费下载，欢迎玩家热情参与游戏。

奖励丰厚的全新的挑战活动等待着你！赶快进入游戏看看吧！

新活动1：掠夺半人马黄金（单人挑战副本）
相关说明：玩家可每天掠夺半人马黄金护送队手中的大量黄金，每块黄金价值数万金币！

新活动2：巨型宝石怪（单人挑战副本）
相关说明：玩家击败体型巨大的宝石怪，能获得出十分珍贵的各类宝石。

新活动3：黑耀之巢（单人挑战副本）
相关说明：玩家可每日前往黑耀之巢，击败烈焰护卫们，夺取里面的宝物。',
					'content' => '《审判王座》最新通告

超人气手游《审判王座》正式入侵APPstore，并且已经开放免费下载，欢迎玩家热情参与游戏。

奖励丰厚的全新的挑战活动等待着你！赶快进入游戏看看吧！

新活动1：掠夺半人马黄金（单人挑战副本）
相关说明：玩家可每天掠夺半人马黄金护送队手中的大量黄金，每块黄金价值数万金币！

新活动2：巨型宝石怪（单人挑战副本）
相关说明：玩家击败体型巨大的宝石怪，能获得出十分珍贵的各类宝石。

新活动3：黑耀之巢（单人挑战副本）
相关说明：玩家可每日前往黑耀之巢，击败烈焰护卫们，夺取里面的宝物。

新活动4：烈焰核心（单人挑战副本）
相关说明：烈焰核心位于黑耀之巢的深处，在里面将直面火焰魔神的挑战！

新活动5：邪龙王刚铎（组队挑战副本）
相关说明：集结你最强的小伙伴们，拿起最强力的武器来武装自己，您将面对远古邪龙之王刚铎，挑战它并击败他，就能获得非常珍贵的邪龙秘宝！

《审判王座》游戏介绍

【兵来将挡，水来土掩，星座阵法与微操作让竞技更具策略性】 
【称霸世界，强者为王，紧张刺激的公会攻防战】 
【危机四伏，智慧考验，勇气与谋略并存的众神炼狱强者试炼】 
【多人BOSS，急速PVP匹配，战斗语音系统让你与妹子边聊天边征战】 
【全屏特效，霸气造型，五星随从叼炸天】',
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