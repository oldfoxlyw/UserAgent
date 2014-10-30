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
						'section_id' => '601',
						'server_id' => '601',
						'server_name' => '熔火堡垒',
						'server_ip' => array (
								0 => array (
										'ip' => '115.159.1.45:8091' 
								)
						),
						'server_game_ip' => array(
								0 => array (
										'ip' 	=> 	'115.159.3.100',
										'port' 	=>	'9999'
								)
						),
						'game_message_ip' => '10.247.27.130:9899',
						'const_server_ip' => '115.159.1.45:8091',
						'voice_server_ip' => '115.159.1.45:8099',
						'cross_server_ip' => '115.159.1.45:8091',
						'legion_message_ip' => '10.247.27.130:1101',
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
				// 		'section_id' => '602',
				// 		'server_id' => '602',
				// 		'server_name' => '水晶溪谷',
				// 		'server_ip' => array (
				// 				0 => array (
				// 						'ip' => '115.159.3.100:8091' 
				// 				)
				// 		),
				// 		'server_game_ip' => array(
				// 				0 => array (
				// 						'ip' 	=> 	'115.159.1.45',
				// 						'port' 	=>	'9999'
				// 				)
				// 		),
				// 		'game_message_ip' => '10.247.41.87:9899',
				// 		'const_server_ip' => '115.159.3.100:8091',
				// 		'voice_server_ip' => '115.159.3.100:8099',
				// 		'cross_server_ip' => '115.159.3.100:8091',
				// 		'legion_message_ip' => '10.247.41.87:1101',
				// 		'server_max_player' => '100000',
				// 		'account_count' => '0',
				// 		'server_language' => '中文',
				// 		'server_sort' => '10',
				// 		'server_recommend' => '0',
				// 		'server_debug' => '0',
				// 		'partner' => 'default,default_full',
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
				'summary' => '《冰火王座》最新通告

超人气手游《冰火王座》预计将在2014年10月30日上午10点正式开启不删档测试，请玩家拭目以待！

关注360冰火王座专区：随时获得限量豪华礼包与最新开服信息等各种劲爆福利！第一波豪华礼包已准备就绪，请前往360冰火王座专区领取

360专区地址：http://bbs.mgamer.cn/forum-2616-1.html

《冰火王座》游戏介绍

1.多人Boss副本，真实在线组队，与好友一起开荒！,
2.PVP急速匹配，无障碍多人竞技；争抢荣誉套装，重铸神兵利器。
3.击飞、震退、眩晕、流血、僵直、燃烧……拳拳到肉，招招华丽，超爽快打击感，指尖上的血拼撕杀！
4.全屏特效、霸气造型，五星随从炫酷翻天！
5.攻、防、治愈、法术，用12星座阵法瞬间逆转战局！
6.最完善的炼金系统，药水宝石轻松合成！
7.强化解封100%成功，镶嵌宝石随意拆卸，最自由最爽快的游戏设计！
8.断线超速重连，无损战斗体验！
9.自动/手动战斗随心切换，想怎么玩就怎么玩！',
				'content' => '《冰火王座》最新通告

超人气手游《冰火王座》预计将在2014年10月30日上午10点正式开启不删档测试，请玩家拭目以待！

关注360冰火王座专区：随时获得限量豪华礼包与最新开服信息等各种劲爆福利！第一波豪华礼包已准备就绪，请前往360冰火王座专区领取

360专区地址：http://bbs.mgamer.cn/forum-2616-1.html

《冰火王座》游戏介绍

1.多人Boss副本，真实在线组队，与好友一起开荒！,
2.PVP急速匹配，无障碍多人竞技；争抢荣誉套装，重铸神兵利器。
3.击飞、震退、眩晕、流血、僵直、燃烧……拳拳到肉，招招华丽，超爽快打击感，指尖上的血拼撕杀！
4.全屏特效、霸气造型，五星随从炫酷翻天！
5.攻、防、治愈、法术，用12星座阵法瞬间逆转战局！
6.最完善的炼金系统，药水宝石轻松合成！
7.强化解封100%成功，镶嵌宝石随意拆卸，最自由最爽快的游戏设计！
8.断线超速重连，无损战斗体验！
9.自动/手动战斗随心切换，想怎么玩就怎么玩！',
				'post_time' => 0,
				'partner_key' => 'default,default_full'
		)
);
?>
