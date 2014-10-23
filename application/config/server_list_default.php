<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

//电信列表
$config ['game_server_list1'] = array (
		'message' => 'SERVER_LIST_SUCCESS',
		'activate' => 0,
		'server' => array (
				array (
						'id' => '2',
						'game_id' => 'B',
						'section_id' => '401',
						'account_server_id' => '401',
						'server_name' => 'test',
						'server_ip' => array (
								0 => array (
										'ip' => '85.195.100.234:6091' 
								)
						),
						'server_game_ip' => '85.195.100.236',
						'game_message_ip' => '10.11.12.15:8898',
						'const_server_ip' => '85.195.100.234:6091',
						'voice_server_ip' => '85.195.100.236:6088',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '',
						'server_sort' => '2',
						'server_recommend' => '1',
						'server_debug' => '0',
						'partner' => 'arab_default,arab_sdk',
						'version' => '',
						'server_status' => '1',
						'server_new' => '1',
						'special_ip' => '',
						'need_activate' => '0',
						'server_starttime' => '0',
						'server_game_port' => '8899' 
				)
		)
);

$config['game_announcement'] = array(
		'announce' => array (
				'id' => '1',
				'summary' => 'test',
				'content' => 'test'
		)
);
?>
