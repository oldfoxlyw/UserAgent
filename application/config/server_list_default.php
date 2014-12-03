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
						'section_id' => '803',
						'account_server_id' => '803',
						'server_name' => 'Athena',
						'server_ip' => array (
								0 => array (
										'ip' => '184.173.231.228:8091' 
								)
						),
						'server_game_ip' => '184.173.231.229',
						'game_message_ip' => '10.48.104.186:9899',
						'const_server_ip' => '184.173.231.228:8091',
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
				)
		)
);

$config['game_announcement'] = array(
		'announce' => array (
				'id' => '1',
				'summary' => "Welcome to the world of Warthrone! 

We've prepared plenty of events and gift packs, so don't forget to check out the events and package menus in the game. 

If you have any problems or suggestions for the game please contact customer support at: warthronesupport@fedeen.com. 

Good luck and enjoy your adventure.",
				'content' => "Welcome to the world of Warthrone! 

We've prepared plenty of events and gift packs, so don't forget to check out the events and package menus in the game. 

If you have any problems or suggestions for the game please contact customer support at: warthronesupport@fedeen.com. 

Good luck and enjoy your adventure."
		)
);
?>
