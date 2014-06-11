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
						'server_name' => '独眼巨人（01）',
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
				'summary' => '《冰與火之王》繁體1.0新內容預覽

1.增加副本組隊介面以及Boss戰場介面之語音聊天功能，玩家可以按住喇叭按鈕進行錄音，鬆開按鈕後即可發送錄音給其他玩家。
2.等級上限提升至50級
3.50級沙漠副本正式開放，玩家可以通過“精靈-達納蘇斯”主城正中的穿界門進入該副本，開始全新挑戰。
4.新增各職業50級榮譽套裝（橙色裝備）
5.新增50級裝備、隨從裝備圖紙
6.煉金中新增5階寶石的合成選項，玩家可以通過融合8個4階寶石來煉製5階寶石。
7.新增隨從融合，強化玩法
8.全新的5星隨從強勢登場！狂暴角魔·海利翁，冰霜女巫·安婕莉拉將在商店中販售，原有女武神與炎魔隨從將暫停出售，但玩家仍有機會透過綠鑽抽取到它們。
9.新增6階附魔卷軸，並在眾神煉獄中掉落。
10.修復會長無法退出公會以及解散公會
11.添加反作弊機制
12.修復隨從等級的bug，現在隨從等級不能高於主角等級
13.添加含附件郵件以及角色刪除的兩次確認


部落的號角在呼喚，體內的獸血已沸騰!戰鬥吧，勇士!',
				'content' => '《冰與火之王》繁體1.0新內容預覽

1.增加副本組隊介面以及Boss戰場介面之語音聊天功能，玩家可以按住喇叭按鈕進行錄音，鬆開按鈕後即可發送錄音給其他玩家。
2.等級上限提升至50級
3.50級沙漠副本正式開放，玩家可以通過“精靈-達納蘇斯”主城正中的穿界門進入該副本，開始全新挑戰。
4.新增各職業50級榮譽套裝（橙色裝備）
5.新增50級裝備、隨從裝備圖紙
6.煉金中新增5階寶石的合成選項，玩家可以通過融合8個4階寶石來煉製5階寶石。
7.新增隨從融合，強化玩法
8.全新的5星隨從強勢登場！狂暴角魔·海利翁，冰霜女巫·安婕莉拉將在商店中販售，原有女武神與炎魔隨從將暫停出售，但玩家仍有機會透過綠鑽抽取到它們。
9.新增6階附魔卷軸，並在眾神煉獄中掉落。
10.修復會長無法退出公會以及解散公會
11.添加反作弊機制
12.修復隨從等級的bug，現在隨從等級不能高於主角等級
13.添加含附件郵件以及角色刪除的兩次確認


部落的號角在呼喚，體內的獸血已沸騰!戰鬥吧，勇士!',
				'post_time' => '1394121601',
				'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
		)
);
?>
