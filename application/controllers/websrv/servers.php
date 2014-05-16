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

		if($partner == 'arab_default' || $partner == 'arab_sdk')
		{
			$this->get_sdk_debug_list('96');
			exit();
		}
		// elseif($partner == 'test_default')
		// {
		// 	$this->load->config('server_list_sdk');
		// 	$jsonData = $this->config->item('game_server_list');
		// }
		elseif($mode == 'pub' && ($partner == 'default' || $partner == 'default_full') && $ver != '1.2')
		{
			$this->load->config('server_list_default');
			
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

			$type = 'appstore';
		}
		elseif($mode == 'pub' && $partner != 'default' && $partner != 'default_full' && $ver == '1.2')
		{
			$this->load->config('server_list_sdk');
			$jsonData = $this->config->item('game_server_list');

			$type = 'sdk';
		}
		elseif(!empty($ver) && $ver == '1.2' && $mode == 'pub' && ($partner == 'default' || $partner == 'default_full'))
		{
			$this->get_sdk_debug_list('99');
			exit();
		}
		else
		{
			$jsonData = array();
		}

		// $productdb = $this->load->database('productdb', TRUE);
		// $sql = "SELECT `server_id`, `count`, `max_count` FROM `server_balance_check` WHERE `next_active` = 1 AND `type`='{$type}'";
		// $next = $productdb->query($sql)->row();
		// $maxCount = intval($next->max_count);
		// $count = intval($next->count);
		// $next = intval($next->server_id);

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

		// if($count >= $maxCount)
		// {
		// 	if($next >= 1)
		// 	{
		// 		$nextServer = 0;
		// 	}
		// 	else
		// 	{
		// 		$nextServer = $next + 1;
		// 	}
		// 	$sql = "UPDATE `server_balance_check` SET `next_active` = 1 WHERE `next`={$nextServer}";
		// 	$productdb->query($sql);
		// 	$sql = "UPDATE `server_balance_check` SET `count` = 1, `next_active` = 0 WHERE `next`={$next}";
		// 	$productdb->query($sql);
		// }
		// else
		// {
		// 	$sql = "UPDATE `server_balance_check` SET `count` = `count` + 1 WHERE `next`={$next}";
		// 	$productdb->query($sql);
		// }

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
					'summary' => '《冰火王座》版本号：1.2
越狱上架，活动不停

1.充值即送四星随从，以及配套VIP随从装备
2.新增等级礼包，升级绿钻送不停
3.多重活动，领取灵魂精华，随从华丽提升

                                                  1.2全新版本内容预览

1.副本组队界面以及Boss战场界面增加语音聊天功能，玩家可以按住喇叭按钮进行录音，松开按钮后录音将会发送给其他玩家。
2.等级上限提升至50级
3.50级沙漠副本正式开放，玩家可以通过“精灵-达纳苏斯”主城正中的穿界门进入该副本，开始新的挑战。
4.新增各职业50级荣誉套装（橙色装备）
5.新增50级装备，随从装备图纸
6.炼金中新增5阶宝石的合成选项，玩家可以通过融合8个4阶宝石来炼制5阶宝石。
7.新增随从融合，强化玩法
8.全新的5星随从强势登场！狂暴角魔·海利翁，冰霜女巫·安婕莉拉将在商店中出售，原有女武神与炎魔随从暂停出售，但玩家仍有机  会通过绿钻抽取到它们。
9.新增6阶附魔卷轴，并在众神炼狱中掉落。
10.修复会长无法退出工会以及解散工会
11.添加反作弊机制
12.修复随从等级的bug，现在随从等级不能高于主角等级
13.添加含附件邮件以及角色删除的二次确认


                                     部落的号角在呼唤，体内的兽血已沸腾!战斗吧，勇士!',
					'content' => '《冰火王座》版本号：1.2
越狱上架，活动不停

1.充值即送四星随从，以及配套VIP随从装备
2.新增等级礼包，升级绿钻送不停
3.多重活动，领取灵魂精华，随从华丽提升

                                                  1.2全新版本内容预览

1.副本组队界面以及Boss战场界面增加语音聊天功能，玩家可以按住喇叭按钮进行录音，松开按钮后录音将会发送给其他玩家。
2.等级上限提升至50级
3.50级沙漠副本正式开放，玩家可以通过“精灵-达纳苏斯”主城正中的穿界门进入该副本，开始新的挑战。
4.新增各职业50级荣誉套装（橙色装备）
5.新增50级装备，随从装备图纸
6.炼金中新增5阶宝石的合成选项，玩家可以通过融合8个4阶宝石来炼制5阶宝石。
7.新增随从融合，强化玩法
8.全新的5星随从强势登场！狂暴角魔·海利翁，冰霜女巫·安婕莉拉将在商店中出售，原有女武神与炎魔随从暂停出售，但玩家仍有机  会通过绿钻抽取到它们。
9.新增6阶附魔卷轴，并在众神炼狱中掉落。
10.修复会长无法退出工会以及解散工会
11.添加反作弊机制
12.修复随从等级的bug，现在随从等级不能高于主角等级
13.添加含附件邮件以及角色删除的二次确认


部落的号角在呼唤，体内的兽血已沸腾!战斗吧，勇士!',
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