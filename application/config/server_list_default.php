<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

//电信列表
$config ['game_server_list1'] = array (
		'message' => 'SERVER_LIST_SUCCESS',
		'activate' => 0,
		'server' => array (
				array (
						'id' => '1',
						'game_id' => 'B',
						'section_id' => '101',
						'account_server_id' => '101',
						'server_name' => 'DK2外网测试',
						'server_ip' => array (
								0 => array (
										'ip' => '42.121.82.229:8091' 
								)
						),
						'server_game_ip' => '42.121.82.229',
						'game_message_ip' => '10.200.186.133:9899',
						'const_server_ip' => '42.121.82.229:8091',
						'voice_server_ip' => '42.121.82.229:8088',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '中文',
						'server_sort' => '12',
						'server_recommend' => '1',
						'server_debug' => '0',
						'partner' => 'default,default_full,91,17173,pp,Downjoy,zq,uc',
						'version' => '',
						'server_status' => '1',
						'server_new' => '1',
						'special_ip' => '',
						'need_activate' => '0',
						'server_starttime' => '0',
						'server_game_port' => '9999' 
				)
		)
);

$config['game_announcement'] = array(
		'announce' => array (
				'id' => '1',
				'summary' => '',
				'content' => '',
				'post_time' => '1394121601',
				'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
		)
);
?>
