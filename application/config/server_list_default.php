<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

//电信列表
$config ['game_server_list'] = array (
		'message' => 'SERVER_LIST_SUCCESS',
		'activate' => 0,
		'server' => array (
				array (
						'id' => '1',
						'game_id' => 'B',
						'section_id' => '401',
						'account_server_id' => '401',
						'server_name' => '红月之森(s19)',
						'server_ip' => array (
								0 => array (
										'ip' => '58.68.251.50:6091' 
								)
						),
						'server_game_ip' => array(
								0 => array (
										'ip' 	=> 	'58.68.251.50',
										'port' 	=>	'9988'
								)
						),
						'game_message_ip' => '10.68.237.97:9977',
						'const_server_ip' => '58.68.251.50:6091',
						'voice_server_ip' => '58.68.251.50:8099',
						'cross_server_ip' => '58.68.251.50:6091',
						'legion_message_ip' => '10.68.237.97:1101',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '中文',
						'server_sort' => '12',
						'server_recommend' => '1',
						'server_debug' => '0',
						'partner' => 'default,default_full',
						'version' => '',
						'server_status' => '1',
						'server_new' => '1',
						'special_ip' => '',
						'need_activate' => '0',
						'server_starttime' => '0'
				),
				// array (
				// 		'id' => '2',
				// 		'game_id' => 'B',
				// 		'section_id' => '402',
				// 		'account_server_id' => '402',
				// 		'server_name' => '恶火狼王',
				// 		'server_ip' => array (
				// 				0 => array (
				// 						'ip' => '58.68.251.52:6091' 
				// 				)
				// 		),
				// 		'server_game_ip' => array(
				// 				0 => array (
				// 						'ip' 	=> 	'58.68.251.52',
				// 						'port' 	=>	'9988'
				// 				)
				// 		),
				// 		'game_message_ip' => '10.68.237.99:9977',
				// 		'const_server_ip' => '58.68.251.52:6091',
				// 		'voice_server_ip' => '58.68.251.52:8099',
				// 		'cross_server_ip' => '58.68.251.52:6091',
				// 		'legion_message_ip' => '10.68.237.99:1101',
				// 		'server_max_player' => '100000',
				// 		'account_count' => '0',
				// 		'server_language' => '中文',
				// 		'server_sort' => '10',
				// 		'server_recommend' => '0',
				// 		'server_debug' => '0',
				// 		'partner' => 'default,default_full,91,17173,pp,Downjoy,zq,uc',
				// 		'version' => '',
				// 		'server_status' => '1',
				// 		'server_new' => '1',
				// 		'special_ip' => '',
				// 		'need_activate' => '0',
				// 		'server_starttime' => '0'
				// )
		)
);

$config['game_announcement'] = array(
		'announce' => array (
				'id' => '1',
				'summary' => '《冰与火之王》最新通告

超人气手游《冰与火之王》，已经开放下载，欢迎玩家热情参与游戏。

2014．8．5日推出4个全新挑战活动！赶快进入游戏看看吧！

新活动1：夏日福袋送清凉
相关说明：活动期间，玩家每天都可以领到夏日福袋，里面会有金币，宝石，药水等奖励。

新活动2：众神炼狱六层突破赛
相关说明：活动期间内，只要玩家成功突破炼狱第六层（精英），就能获得勇者福袋，里面会有绿钻和各种高级奖品奖励！

新活动3：邪龙巢穴（普通）
相关说明：活动期间，玩家可以选择和好朋友一起组队挑战藏有龙族秘宝的邪龙巢穴，击败里面的邪龙守卫，就有机会获得各种物品的高级奖励。

新活动4：上古邪龙刚多（精英）
相关说明：活动期间，玩家可以选择和好朋友一起组队挑战超强的上古邪龙刚多，成功击败它就能获得藏有超高级奖励的至尊邪龙秘宝。',
				'content' => '《冰与火之王》最新通告

超人气手游《冰与火之王》，已经开放下载，欢迎玩家热情参与游戏。

2014．8．5日推出4个全新挑战活动！赶快进入游戏看看吧！

新活动1：夏日福袋送清凉
相关说明：活动期间，玩家每天都可以领到夏日福袋，里面会有金币，宝石，药水等奖励。

新活动2：众神炼狱六层突破赛
相关说明：活动期间内，只要玩家成功突破炼狱第六层（精英），就能获得勇者福袋，里面会有绿钻和各种高级奖品奖励！

新活动3：邪龙巢穴（普通）
相关说明：活动期间，玩家可以选择和好朋友一起组队挑战藏有龙族秘宝的邪龙巢穴，击败里面的邪龙守卫，就有机会获得各种物品的高级奖励。

新活动4：上古邪龙刚多（精英）
相关说明：活动期间，玩家可以选择和好朋友一起组队挑战超强的上古邪龙刚多，成功击败它就能获得藏有超高级奖励的至尊邪龙秘宝。

《冰与火之王》游戏介绍

1.多人Boss副本，真实在线组队，与好友一起开荒！,
2.PVP急速匹配，无障碍多人竞技；争抢荣誉套装，重铸神兵利器。
3.击飞、震退、眩晕、流血、僵直、燃烧……拳拳到肉，招招华丽，超爽快打击感，指尖上的血拼撕杀！
4.全屏特效、霸气造型，五星随从炫酷翻天！
5.攻、防、治愈、法术，用12星座阵法瞬间逆转战局！
6.最完善的炼金系统，药水宝石轻松合成！
7.与工会战友一起争夺城堡，成为一城之主，坐拥万千税收，雇佣兵、守护神任你差遣！
8.强化解封100%成功，镶嵌宝石随意拆卸，最自由最爽快的游戏设计！
9.断线超速重连，无损战斗体验！
10.自动/手动战斗随心切换，想怎么玩就怎么玩！

部落的号角在呼唤，体内的兽血已沸腾!战斗吧，勇士!',
				'post_time' => '1394121601',
				'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
		)
);
?>
