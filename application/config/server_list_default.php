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
				'summary' => '《冰與火之王》最新通告

超人氣手游《冰與火之王》正式入侵APPstore，並且已經開放免費下載，現已開放《獨眼巨人》、《静月斥候》兩個伺服器供玩家遊玩。，歡迎玩家熱情參與遊戲。

2014．7．11日推出11個超級給力活動！趕快進入遊戲看看吧！

新活動1：五星隨從掉率大幅提高
相關說明：活動期間，玩家抽取隨從時，獲得五星頂級隨從的機率大大增加！活動結束后，五星隨從獲取機率將返回初始，現在機會難得，趕快來試試手氣吧！',
				'content' => '《冰與火之王》最新通告

超人氣手游《冰與火之王》正式入侵APPstore，並且已經開放免費下載，現已開放《獨眼巨人》、《静月斥候》兩個伺服器供玩家遊玩。，歡迎玩家熱情參與遊戲。

2014．7．11日推出11個超級給力活動！趕快進入遊戲看看吧！

新活動1：五星隨從掉率大幅提高
相關說明：活動期間，玩家抽取隨從時，獲得五星頂級隨從的機率大大增加！活動結束后，五星隨從獲取機率將返回初始，現在機會難得，趕快來試試手氣吧！

新活動2：8綠鑽博豪獎
相關說明：活動期間，玩家只需要花費8綠鑽，就可以購買一個超級幸運寶箱，裡面能開出各種豪華獎勵，包括五星隨從、四星隨從、大量綠鑽、金幣、高級藥水、海量隨從強化道具。

新活動3：充值感恩返綠鑽
相關說明：從開服到7月13日期間，只要充值成為VIP3以上的玩家，都可以獲得感恩回饋大福袋。

新活動4：戰爭女神雅典娜
相關說明：戰爭女神雅典娜是數個頂級五星隱藏隨從之一，只有極其少數的玩家才有能獲得，現在為慶祝《冰與火之王》正式免費，所以只需要付出超低的價格就能購買！購買再送一把上古神器，橙色傳奇武器『女神的寬恕』，擁有高突破次數，超高屬性的法系至尊極品隨從武器！

新活動5：PVP每日明星
相關說明：根据每日战场胜利次数来挑选每日PVP明星。

新活動6：黃金旅團的交易券
相關說明：可以只花1金幣購買與黃金旅團的交易機會，可以開啟與黃金旅團的交易活動，這些交易活動又便宜又划算！每次花費1金幣購買後，還會贈送好運福袋，能隨機開出金幣和遊戲道具！

新活動7：黃金旅團的交易
相關說明：只需要花費39綠鑽就可以購買海量金幣，同時還將贈送隨機寶石袋。·完成次此交易後，會開啟下一個超大優惠活動！

新活動8：黃金旅團的饋贈
所在位置：『活動』界面中
相關說明：只需要花69綠鑽即可購買由隨機種類的三階寶石、高級經驗藥水、隨從強化福袋組成的三合一超值好康包。

新活動9：超值寶石出售
相關說明：二階寶石三種各1個，只需要花費1個綠鑽！買了還送5000金幣！參與該活動後開啟更優惠的寶石出售活動。

新活動10：超值三階寶石出售
相關說明：三階寶石隨機1個，只需要花費39個綠鑽！再贈送高級金幣、經驗藥水各兩瓶。參與該活動後開啟更優惠的寶石出售活動。

新活動11：超值四階寶石出售
相關說明：四階寶石隨機1個，只需要花費269個綠鑽！再贈送金幣100000枚、數種高級隨從培養道具。

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
