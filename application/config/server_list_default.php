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
						'section_id' => '801',
						'account_server_id' => '801',
						'server_name' => '测试服us01',
						'server_ip' => array (
								0 => array (
										'ip' => '184.173.231.226:8091' 
								)
						),
						'server_game_ip' => '184.173.231.227',
						'game_message_ip' => '10.48.104.182:9899',
						'const_server_ip' => '184.173.231.226:8091',
						'voice_server_ip' => '184.173.231.226:8099',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '',
						'server_sort' => '2',
						'server_recommend' => '1',
						'server_debug' => '0',
						'partner' => 'en_default,en_arc',
						'version' => '',
						'server_status' => '1',
						'server_new' => '1',
						'special_ip' => '',
						'need_activate' => '0',
						'server_starttime' => '0',
						'server_game_port' => '9999' 
				),
				array (
						'id' => '3',
						'game_id' => 'B',
						'section_id' => '802',
						'account_server_id' => '802',
						'server_name' => '审核服us02',
						'server_ip' => array (
								0 => array (
										'ip' => '184.173.231.227:8091' 
								)
						),
						'server_game_ip' => '184.173.231.228',
						'game_message_ip' => '10.48.104.184:9899',
						'const_server_ip' => '184.173.231.227:8091',
						'voice_server_ip' => '184.173.231.227:8099',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '',
						'server_sort' => '1',
						'server_recommend' => '0',
						'server_debug' => '0',
						'partner' => 'en_default,en_arc',
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
				'summary' => "Notice:As it is impossible to know exactly how long our server maintenance will take, we’d like to forewarn all of you that the server may be down for slightly more or slightly less than 2 hours, this is just an estimated time.

Sincerely,
The Warthrone Team",
				'content' => "Notice:As it is impossible to know exactly how long our server maintenance will take, we’d like to forewarn all of you that the server may be down for slightly more or slightly less than 2 hours, this is just an estimated time.

Sincerely,
The Warthrone Team"
		)
);
?>
