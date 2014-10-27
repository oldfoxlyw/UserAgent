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
						'account_server_id' => '601',
						'server_name' => '熔火堡垒',
						'server_ip' => array (
								0 => array (
										'ip' => '115.159.1.45:8091' 
								)
						),
						'server_game_ip' => array(
								0 => array (
										'ip' 	=> 	'115.159.3.100',
										'port' 	=>	'9899'
								)
						),
						'game_message_ip' => '10.247.27.130:9977',
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
				'summary' => '暂无公告',
				'content' => '暂无公告',
				'post_time' => 0,
				'partner_key' => 'default,default_full'
		)
);
?>
