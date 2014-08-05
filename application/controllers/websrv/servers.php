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
		if($mode == 'pub' && ($partner == 'tw_default' || $partner == 'tw_facebook') && $ver == '1.0')
		{
			$this->load->config('server_list_default');
			$jsonData = $this->config->item('game_server_list1');

			$type = 'appstore';
		}
		elseif($mode == 'pub' && ($partner == 'tw_default' || $partner == 'tw_facebook') && $ver == '1.1')
		{
			$this->load->config('server_list_default');
			$jsonData = $this->config->item('game_server_list1');

			$type = 'appstore';
		}
		elseif($mode == 'beta')
		{
			$jsonData = array(
				'errors'	=>	'测试已结束，清前往AppStore下载正式版客户端'
			);
		}
		else
		{
			$jsonData = array();
		}

		/*
		$productdb = $this->load->database('productdb', TRUE);
		$sql = "SELECT `next`, `count`, `max_count` FROM `server_balance_check` WHERE `next_active` = 1 AND `type`='{$type}'";
		$next = $productdb->query($sql)->row();
		$maxCount = intval($next->max_count);
		$count = intval($next->count);
		$next = intval($next->next);
		*/

		$next = 0;
		$this->load->helper('array');
		for($i = 0; $i<count($jsonData['server']); $i++)
		{
			$jsonData['server'][$i]['server_recommend'] = 0;
			$ipArray = $jsonData['server'][$i]['server_ip'];
			$ip = random_element($ipArray);
			$jsonData['server'][$i]['server_ip'] = $ip['ip'];
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
					'summary' => '《冰與火之王》最新通告

超人氣手游《冰與火之王》正式入侵APPstore，並且已經開放免費下載，現已開放《獨眼巨人》、《静月斥候》兩個伺服器供玩家遊玩。，歡迎玩家熱情參與遊戲。

2014．8．5日推出4個全新挑战活動！趕快進入遊戲看看吧！

新活動1：夏日福袋送清涼
相關說明：活動期間，玩家每天都可以領到夏日福袋，裡面會有金幣，寶石，藥水等獎勵。

新活動2：眾神煉獄六層突破賽
相關說明：活動期間內，只要玩家成功突破煉獄第六層（精英），就能獲得勇者福袋，裡面會有綠鑽和各種高級獎品獎勵！',
					'content' => '《冰與火之王》最新通告

超人氣手游《冰與火之王》正式入侵APPstore，並且已經開放免費下載，現已開放《獨眼巨人》、《静月斥候》兩個伺服器供玩家遊玩。，歡迎玩家熱情參與遊戲。

2014．8．5日推出4個全新挑战活動！趕快進入遊戲看看吧！

新活動1：夏日福袋送清涼
相關說明：活動期間，玩家每天都可以領到夏日福袋，裡面會有金幣，寶石，藥水等獎勵。

新活動2：眾神煉獄六層突破賽
相關說明：活動期間內，只要玩家成功突破煉獄第六層（精英），就能獲得勇者福袋，裡面會有綠鑽和各種高級獎品獎勵！

新活動3：邪龍巢穴（普通）
相關說明：活動期間，玩家可以選擇和好朋友一起組隊挑戰藏有龍族秘寶的邪龍巢穴，擊敗裡面的邪龍守衛，就有機會獲得各種物品的高级獎勵。

新活動4：上古邪龍剛多（精英）
相關說明：活動期間，玩家可以選擇和好朋友一起組隊挑戰超強的上古邪龍剛多，成功擊敗它就能獲得藏有超高級獎勵的至尊邪龍秘寶。

《冰與火之王》遊戲介紹

1.多人Boss副本，真實線上組隊，與好友一起開荒！,
2.PVP急速匹配，無障礙多人競技；爭搶榮譽套裝，重鑄神兵利器。
3.擊飛、震退、眩暈、流血、僵直、燃燒……拳拳到肉，招招華麗，超爽快打擊感，指尖上的血拼撕殺！
4.全屏特效、霸氣造型，五星隨從炫酷翻天！
5.攻、防、治癒、法術，用12星座陣法瞬間逆轉戰局！
6.最完善的煉金系統，藥水寶石輕鬆合成！
7.與工會戰友一起爭奪城堡，成為一城之主，坐擁萬千稅收，雇傭兵、守護神任你差遣！
8.強化解封100%成功，鑲嵌寶石隨意拆卸，最自由最爽快的遊戲設計！
9.斷線超速重連，無損戰鬥體驗！
10.自動/手動戰鬥隨心切換，想怎麼玩就怎麼玩！

部落的號角在呼喚，體內的獸血已沸騰!戰鬥吧，勇士!',
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