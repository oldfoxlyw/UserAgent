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
						'section_id' => '403',
						'account_server_id' => '403',
						'server_name' => '獨眼巨人（01）',
						'server_ip' => array (
								0 => array (
										'ip' => '218.213.235.125:8091' 
								)
						),
						'server_game_ip' => '218.213.235.121',
						'game_message_ip' => '10.11.12.3:9899',
						'const_server_ip' => '218.213.235.125:8091',
						'voice_server_ip' => '218.213.235.125:8088',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '中文',
						'server_sort' => '3',
						'server_recommend' => '1',
						'server_debug' => '0',
						'partner' => 'tw_default,tw_facebook',
						'version' => '',
						'server_status' => '1',
						'server_new' => '1',
						'special_ip' => '',
						'need_activate' => '0',
						'server_starttime' => '0',
						'server_game_port' => '9999' 
				),
				// array (
				// 		'id' => '1',
				// 		'game_id' => 'B',
				// 		'section_id' => '402',
				// 		'account_server_id' => '402',
				// 		'server_name' => '静月斥候（02）',
				// 		'server_ip' => array (
				// 				0 => array (
				// 						'ip' => '218.213.235.124:8091' 
				// 				)
				// 		),
				// 		'server_game_ip' => '218.213.235.120',
				// 		'game_message_ip' => '10.11.12.2:9899',
				// 		'const_server_ip' => '218.213.235.124:8091',
				// 		'voice_server_ip' => '218.213.235.124:8088',
				// 		'server_max_player' => '100000',
				// 		'account_count' => '0',
				// 		'server_language' => '中文',
				// 		'server_sort' => '2',
				// 		'server_recommend' => '0',
				// 		'server_debug' => '0',
				// 		'partner' => 'tw_default,tw_facebook',
				// 		'version' => '',
				// 		'server_status' => '1',
				// 		'server_new' => '1',
				// 		'special_ip' => '',
				// 		'need_activate' => '0',
				// 		'server_starttime' => '0',
				// 		'server_game_port' => '9999' 
				// ),
				// array (
				// 		'id' => '1',
				// 		'game_id' => 'B',
				// 		'section_id' => '401',
				// 		'account_server_id' => '401',
				// 		'server_name' => '冰霜女巫（01）',
				// 		'server_ip' => array (
				// 				0 => array (
				// 						'ip' => '218.213.235.122:8091' 
				// 				)
				// 		),
				// 		'server_game_ip' => '218.213.235.119',
				// 		'game_message_ip' => '10.11.12.1:9899',
				// 		'const_server_ip' => '218.213.235.122:8091',
				// 		'voice_server_ip' => '218.213.235.122:8088',
				// 		'server_max_player' => '100000',
				// 		'account_count' => '0',
				// 		'server_language' => '中文',
				// 		'server_sort' => '2',
				// 		'server_recommend' => '1',
				// 		'server_debug' => '0',
				// 		'partner' => 'tw_default,tw_facebook',
				// 		'version' => '',
				// 		'server_status' => '1',
				// 		'server_new' => '1',
				// 		'special_ip' => '',
				// 		'need_activate' => '0',
				// 		'server_starttime' => '0',
				// 		'server_game_port' => '9999' 
				// )
		)
);

$config['game_announcement'] = array(
		'announce' => array (
				'id' => '1',
				'summary' => '《冰與火之王》最新通告

超人氣手游《冰與火之王》正式入侵APPstore，開放《獨眼巨人》服務器，歡迎玩家熱情參與遊戲。

《冰與火之王》遊戲介紹

1.多人Boss副本，真實線上組隊，與好友一起開荒！,
2.PVP急速匹配，無障礙多人競技；爭搶榮譽套裝，重鑄神兵利器。
3.擊飛、震退、眩暈、流血、僵直、燃燒……拳拳到肉，招招華麗，超爽快打擊感，指尖上的血拼撕殺！
4.全屏特效、霸氣造型，五星隨從炫酷翻天！
5.攻、防、治癒、法術，用12星座陣法瞬間逆轉戰局！
6.最完善的煉金系統，藥水寶石輕鬆合成！
7.與工會戰友一起爭奪城堡，成為一城之主，坐擁萬千稅收，雇傭兵、守護神任你差遣！
8.強化解封100%成功，鑲嵌寶石隨意拆卸，最自由最爽快的遊戲設計！
9.斷線超速重連，無損戰鬥體驗！
10.自動/手動戰鬥隨心切換，想怎麼玩就怎麼玩！

部落的號角在呼喚，體內的獸血已沸騰!戰鬥吧，勇士!',
				'content' => '《冰與火之王》最新通告

超人氣手游《冰與火之王》正式入侵APPstore，開放《獨眼巨人》服務器，歡迎玩家熱情參與遊戲。

《冰與火之王》遊戲介紹

1.多人Boss副本，真實線上組隊，與好友一起開荒！,
2.PVP急速匹配，無障礙多人競技；爭搶榮譽套裝，重鑄神兵利器。
3.擊飛、震退、眩暈、流血、僵直、燃燒……拳拳到肉，招招華麗，超爽快打擊感，指尖上的血拼撕殺！
4.全屏特效、霸氣造型，五星隨從炫酷翻天！
5.攻、防、治癒、法術，用12星座陣法瞬間逆轉戰局！
6.最完善的煉金系統，藥水寶石輕鬆合成！
7.與工會戰友一起爭奪城堡，成為一城之主，坐擁萬千稅收，雇傭兵、守護神任你差遣！
8.強化解封100%成功，鑲嵌寶石隨意拆卸，最自由最爽快的遊戲設計！
9.斷線超速重連，無損戰鬥體驗！
10.自動/手動戰鬥隨心切換，想怎麼玩就怎麼玩！

部落的號角在呼喚，體內的獸血已沸騰!戰鬥吧，勇士!',
				'post_time' => '1394121601',
				'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
		)
);
?>
