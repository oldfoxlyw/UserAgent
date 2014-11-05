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
				'summary' => '冰火王座已经正式开放下载！

赶快前往360专区查看最新消息！冲级送电话卡活动已经开启啦！',
				'content' => '冰火王座已经正式开放下载！

赶快前往360专区查看最新消息！冲级送电话卡活动已经开启啦！',
				'post_time' => 0,
				'partner_key' => 'default,default_full'
		)
);

$config['game_version'] = 2;
$config['game_download_url'] = 'http://shouji.360tpcdn.com/141029/a12783984efd4ca22c2e4fafc2f5ec7d/com.digiarty.org_1.apk';
?>
