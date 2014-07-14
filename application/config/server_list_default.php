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
						'section_id' => '402',
						'account_server_id' => '402',
						'server_name' => '靜月斥候（02）',
						'server_ip' => array (
								0 => array (
										'ip' => '218.213.235.124:8091' 
								)
						),
						'server_game_ip' => '218.213.235.120',
						'game_message_ip' => '10.11.12.2:9899',
						'const_server_ip' => '218.213.235.124:8091',
						'voice_server_ip' => '218.213.235.124:8088',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '中文',
						'server_sort' => '2',
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
						'server_recommend' => '0',
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
				'summary' => '1.1更新內容大綱
 
新增內容：
1.新增『1.1版本上線送禮福袋』，更新遊戲到1.1版本即可領取該福袋。
2.新增『隨從強化日常福袋』，玩家每日可免費領取該福袋。
3.新增『高級隨從強化福袋』，玩家可在活動界面購買。
4.新增5星隱藏隨從

遊戲內容調整：
1.大幅度提升隨從的抽取機率，4星、5星和隱藏隨從抽取概率翻倍。
2.調整『試試手氣』的綠鑽價格，將價格調整為20綠鑽，同時提升『試試手氣』中抽取高級隨從的幾率。
3.調整部分關卡難度，降低部分BOSS的基本屬性，使之更容易被擊潰。
4.調整部分技能傷害和觸發機制，提升戰士和盜賊的基本技能屬性。',
				'content' => '1.1更新內容大綱
 
新增內容：
1.新增『1.1版本上線送禮福袋』，更新遊戲到1.1版本即可領取該福袋。
2.新增『隨從強化日常福袋』，玩家每日可免費領取該福袋。
3.新增『高級隨從強化福袋』，玩家可在活動界面購買。
4.新增5星隱藏隨從

遊戲內容調整：
1.大幅度提升隨從的抽取機率，4星、5星和隱藏隨從抽取概率翻倍。
2.調整『試試手氣』的綠鑽價格，將價格調整為20綠鑽，同時提升『試試手氣』中抽取高級隨從的幾率。
3.調整部分關卡難度，降低部分BOSS的基本屬性，使之更容易被擊潰。
4.調整部分技能傷害和觸發機制，提升戰士和盜賊的基本技能屬性。

功能优化與BUG修復：
1.更換新的ICON圖示。
2.修復聊天時輸入法切換造成遊戲閃退問題。
3.修復組隊打BOSS時候如有隊友斷線會造成遊戲崩閃退問題。
4.修改部分顯示錯誤和文字BUG。
5.大幅優化新手體驗，幫助新手玩家更容易融入遊戲世界。
6.優化介面開啟速度、遊戲讀取速度以及關卡讀取速度，讓玩家獲得更好的遊戲體驗。
7.優化網路渠道連結伺服器時造成延遲的問題。
8.優化遊戲數據帶寬，使下載遊戲和更新遊戲的速度變得更快。',
				'post_time' => '1394121601',
				'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
		)
);
?>
