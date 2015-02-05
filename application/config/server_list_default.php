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
						'server_status' => '0',
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
				'summary' => "Dear Player:
We will be updating to a new version of Warthrone shortly in order to optimize our game servers performance. In order to update the game, our server will be shut down for an estimated two hours of maintenance on February 4th, from 21:00-23:00 (CST). While the server is down you will not be able to log into the game. Please help us to spread the message to all players, and we thank you for your cooperation and understanding.  

Notice:
As it is impossible to know exactly how long our server maintenance will take, we’d like to forewarn all of you that the server may be down for slightly more or slightly less than 2 hours, this is just an estimated time.

Sincerely,
The Warthrone Team",
				'content' => "Dear Player:
We will be updating to a new version of Warthrone shortly in order to optimize our game servers performance. In order to update the game, our server will be shut down for an estimated two hours of maintenance on February 4th, from 21:00-23:00 (CST). While the server is down you will not be able to log into the game. Please help us to spread the message to all players, and we thank you for your cooperation and understanding.  

Notice:
As it is impossible to know exactly how long our server maintenance will take, we’d like to forewarn all of you that the server may be down for slightly more or slightly less than 2 hours, this is just an estimated time.

Sincerely,
The Warthrone Team"
		)
);
?>
