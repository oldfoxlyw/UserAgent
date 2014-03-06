<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers1 extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('websrv/status', 'status');
	}
	
	public function server_list($format = 'json') {
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$result = '{"message":"SERVER_LIST_SUCCESS","activate":0,"server":[{"id":"9","game_id":"B","section_id":"109","account_server_id":"105","server_name":"\u7687\u5bb6\u5b88\u536b(05)","server_ip":"58.68.251.47:8091","server_game_ip":"58.68.251.48","game_message_ip":"10.68.237.95:9899","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"8","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"9999"},{"id":"8","game_id":"B","section_id":"108","account_server_id":"104","server_name":"\u5730\u72f1\u5486\u54ee(04)","server_ip":"58.68.251.45:8091","server_game_ip":"58.68.251.46","game_message_ip":"10.68.237.93:9899","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"7","server_recommend":"1","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"9999"},{"id":"7","game_id":"B","section_id":"107","account_server_id":"103","server_name":"\u5723\u6b4c\u9a91\u58eb(03)","server_ip":"58.68.251.43:8091","server_game_ip":"58.68.251.44","game_message_ip":"10.68.237.91:9899","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"6","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"9999"},{"id":"4","game_id":"B","section_id":"104","account_server_id":"102","server_name":"\u6218\u4e89\u5e73\u539f(02)","server_ip":"122.13.131.76:8091","server_game_ip":"122.13.131.62","game_message_ip":"10.18.234.62:8788","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"5","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"8888"},{"id":"3","game_id":"B","section_id":"103","account_server_id":"101","server_name":"\u66ae\u5149\u4e4b\u7ffc(01)","server_ip":"122.13.131.57:8091","server_game_ip":"122.13.131.54","game_message_ip":"10.18.234.53:8788","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"4","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"8888"}],"announce":{"id":"1","summary":"\u3000\u300a\u51b0\u706b\u738b\u5ea7\u300biOS\u6b63\u7248\u4e8e2014\u5e743\u67086\u65e521:00\u5f00\u542fiOS\u6b63\u7248\u9650\u514d\u4e0b\u8f7d\u540e\uff0c\u5927\u91cf\u73a9\u5bb6\u70ed\u60c5\u6d8c\u5165\u6e38\u620f\uff0c\u73b0\u6709\u4e24\u7ec4\u670d\u52a1\u5668\u8fc5\u901f\u8fdb\u5165\u5230\u7206\u6ee1\u72b6\u6001\u3002\u5b98\u65b9\u6b63\u5168\u529b\u90e8\u7f72\uff0c\u529b\u4e89\u7ed9\u5927\u5bb6\u63d0\u4f9b\u7a33\u5b9a\u6d41\u7545\u7684\u6e38\u620f\u4f53\u9a8c\u3002\u5e76\u5b9a\u4e8e2014\u5e743\u67086\u65e521:20\u5f00\u542fApp\u65b0\u670d3\u533a\u3010\u5723\u6b4c\u9a91\u58eb\u3011\uff0c\u8bda\u9080\u5404\u4f4d\u65b0\u8001\u73a9\u5bb6\u7684\u52a0\u5165!\u5bf9\u4e8e\u670d\u52a1\u5668\u6781\u5ea6\u706b\u7206\u5bfc\u81f4\u767b\u9646\u56f0\u96be\u7684\u95ee\u9898\uff0c\u6b63\u5728\u7d27\u6025\u5904\u7406\u4e2d\uff0c\u6e38\u620f\u5c06\u9010\u6b65\u589e\u52a0\u65b0\u670d\u7f13\u89e3\u4eba\u6570\u706b\u7206\u7684\u95ee\u9898\u3002\u6b64\u5916\u5c06\u7edf\u4e00\u4e8e3\u67087\u65e5\u5168\u670d\u53d1\u653e200\u7eff\u94bb\u4f5c\u4e3a\u8865\u507f\uff01\u611f\u8c22\u5927\u5bb6\u7684\u70ed\u60c5\u548c\u652f\u6301\uff01","content":"\u3000\u300a\u51b0\u706b\u738b\u5ea7\u300biOS\u6b63\u7248\u4e8e2014\u5e743\u67086\u65e521:00\u5f00\u542fiOS\u6b63\u7248\u9650\u514d\u4e0b\u8f7d\u540e\uff0c\u5927\u91cf\u73a9\u5bb6\u70ed\u60c5\u6d8c\u5165\u6e38\u620f\uff0c\u73b0\u6709\u4e24\u7ec4\u670d\u52a1\u5668\u8fc5\u901f\u8fdb\u5165\u5230\u7206\u6ee1\u72b6\u6001\u3002\u5b98\u65b9\u6b63\u5168\u529b\u90e8\u7f72\uff0c\u529b\u4e89\u7ed9\u5927\u5bb6\u63d0\u4f9b\u7a33\u5b9a\u6d41\u7545\u7684\u6e38\u620f\u4f53\u9a8c\u3002\u5e76\u5b9a\u4e8e2014\u5e743\u67086\u65e521:20\u5f00\u542fApp\u65b0\u670d3\u533a\u3010\u5723\u6b4c\u9a91\u58eb\u3011\uff0c\u8bda\u9080\u5404\u4f4d\u65b0\u8001\u73a9\u5bb6\u7684\u52a0\u5165!\u5bf9\u4e8e\u670d\u52a1\u5668\u6781\u5ea6\u706b\u7206\u5bfc\u81f4\u767b\u9646\u56f0\u96be\u7684\u95ee\u9898\uff0c\u6b63\u5728\u7d27\u6025\u5904\u7406\u4e2d\uff0c\u6e38\u620f\u5c06\u9010\u6b65\u589e\u52a0\u65b0\u670d\u7f13\u89e3\u4eba\u6570\u706b\u7206\u7684\u95ee\u9898\u3002\u6b64\u5916\u5c06\u7edf\u4e00\u4e8e3\u67087\u65e5\u5168\u670d\u53d1\u653e200\u7eff\u94bb\u4f5c\u4e3a\u8865\u507f\uff01\u611f\u8c22\u5927\u5bb6\u7684\u70ed\u60c5\u548c\u652f\u6301\uff01\r\n\u3000\u3000\r\n\u9650\u65f6\u514d\u8d39\u65f6\u95f4\uff1a3\u67086\u65e521:00-3\u67089\u65e523:59\r\n\u3000\u3000\u3000\u3000\u3000\u3000\u3000\u3000\r\n\u5bf9\u4e8e3\u67086\u65e521:00\u524d\u4e0b\u8f7d\u6e38\u620f\u7684\u73a9\u5bb6\uff0c\u6211\u4eec\u5c06\u7edf\u4e00\u53d1\u653e\u6bcf\u4eba500\u7eff\u94bb\u7684\u5956\u52b1\u3002\uff08\u4e8e3\u67086\u65e521:00\u524d\u7cfb\u7edf\u5411\u5168\u670d\u73a9\u5bb6\u53d1\u653e\uff09\r\n\r\n\u65b0\u589e\u793c\u5305\uff1a\u5927\u5e45\u5ea6\u63d0\u5347\u767b\u9646\u5956\u52b1\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u4e3a\u4e86\u56de\u9988\u73a9\u5bb6\u5bf9\u6e38\u620f\u7684\u70ed\u60c5\uff0c\u4ee5\u53ca\u5bf9\u6e38\u620f\u63d0\u51fa\u7684\u5404\u79cd\u5efa\u8bae\u548c\u5e2e\u52a9\uff0c\u6240\u4ee5\u5927\u5e45\u5ea6\u63d0\u5347\u6bcf\u65e5\u767b\u9646\u5956\u52b1\u5e45\u5ea6\uff0c\u4ee5\u8868\u793a\u6211\u4eec\u5bf9\u73a9\u5bb6\u7531\u8877\u7684\u611f\u8c22\uff0c\u4ee5\u540e\u8fd8\u4f1a\u9010\u6b65\u63d0\u5347\u5956\u52b1\u7684\u6570\u91cf\uff0c\u8bf7\u5e7f\u5927\u73a9\u5bb6\u7ee7\u7eed\u652f\u6301\u51b0\u706b\u738b\u5ea7\uff01\r\n\r\n\u8fd1\u671f\u6d3b\u52a8\u4e0e\u793c\u5305\u5185\u5bb9\uff1a\r\n\u65b0\u589e\u793c\u5305\uff1a30\u7ea7\u52c7\u58eb\u793c\u5305\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u7b49\u7ea7\u5230\u8fbe30\u7ea7\u65e2\u53ef\u514d\u8d39\u9886\u53d6\u7684\u793c\u5305\uff0c\u5305\u542b800\u7eff\u94bb\u7b49\u9ad8\u7ea7\u5956\u52b1\uff0c\u5feb\u901f\u6b66\u88c5\u81ea\u5df1\u5427\uff01 \r\n\r\n\u65b0\u589e\u793c\u5305\uff1a45\u7ea7\u6218\u795e\u793c\u5305\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u7b49\u7ea7\u5230\u8fbe45\u7ea7\u65e2\u53ef\u514d\u8d39\u9886\u53d6\u7684\u793c\u5305\uff0c\u5305\u542b3000\u7eff\u94bb\uff0c\u56db\u661f\u5b9d\u77f3\u7b49\u8d85\u8c6a\u534e\u7ea7\u5956\u52b1\uff01\r\n\r\n\u65b0\u589e\u793c\u5305\uff1a10\u7ea7\uff0c20\u7ea7\u514d\u8d39\u9886\u53d6\u6280\u80fd\u70b9\u793c\u5305\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u4e3a\u4e86\u964d\u4f4e\u73a9\u5bb6\u95ef\u5173\u7684\u96be\u5ea6\uff0c\u7279\u6b64\u63a8\u51fa\u514d\u8d39\u6280\u80fd\u70b9\u793c\u5305\uff0c\u5f53\u73a9\u5bb6\u7b49\u7ea7\u5230\u8fbe10\u7ea7\u548c20\u7ea7\u65f6\uff0c\u53ef\u5206\u522b\u9886\u53d6\u5305\u542b\u6d77\u91cf\u6280\u80fd\u70b9\u7684\u793c\u5305\uff0c\u70b9\u51fb\u4f7f\u7528\u540e\u53ef\u5927\u5e45\u5ea6\u589e\u52a0\u89d2\u8272\u7684\u6280\u80fd\u70b9\u3002\r\n\r\n\u65b0\u589e\u6d3b\u52a8\uff1a\u6700\u5f3a\u516c\u4f1a\u6392\u540d\u8d5b\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u7cfb\u7edf\u90ae\u4ef6\u53d1\u9001\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6bcf\u4e24\u5468\u8bc4\u9009\u4e00\u6b21\u6700\u5f3a\u516c\u4f1a\uff0c\u5c06\u6839\u636e\u516c\u4f1a\u5360\u9886\u7684\u57ce\u5821\u6570\u91cf\u3001\u516c\u4f1a\u6210\u5458\u6570\u4ee5\u53ca\u516c\u4f1a\u6210\u5458\u5e73\u5747\u7b49\u7ea7\uff0c\u603b\u8363\u8a89\u5ea6\u8fdb\u884c\u6392\u540d\u3002\u8bc4\u9009\u51fa\u4e24\u5468\u5185\u670d\u52a1\u5668\u4e2d\u7684\u6700\u5f3a\u516c\u4f1a\uff0c\u5e76\u53d1\u653e\u4e0d\u540c\u91d1\u989d\u7684\u7eff\u94bb\u53ca\u91d1\u5e01\u5956\u52b1\uff0c\u6240\u6709\u516c\u4f1a\u6210\u5458\u7686\u53ef\u83b7\u5f97\u6d77\u91cf\u798f\u5229\u3002\r\n\r\n\u65b0\u589e\u6d3b\u52a8\uff1a\u51b0\u706b\u6218\u573a\u4e89\u9738\u8d5b\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u7cfb\u7edf\u90ae\u4ef6\u53d1\u9001\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u51b0\u706b\u6218\u573a\u4e89\u9738\u8d5b\uff0c\u6bcf\u5468PVP\u6392\u884c\u7248\u5956\u52b1\u9001\u4e0d\u505c\uff0c\u7fa4\u96c4\u9010\u9e7f\uff0c\u8c01\u4e0e\u4e89\u950b\uff1f\u6211\u4eec\u62ed\u76ee\u4ee5\u5f85\uff01\u6bcf\u5468\u4e94\u665a\u4e0a23\uff1a59\u5206\uff0c\u8363\u8a89\u6392\u884c\u699c\u4e2d20\u540d\u73a9\u5bb6\u90fd\u53ef\u4ee5\u83b7\u5f97\u5927\u91cf\u7eff\u94bb\u5956\u52b1\uff0c\u8fd8\u6709\u73cd\u8d35\u7684\u5f3a\u529b\u8363\u8a89\u88c5\u5907\u7b49\u4f60\u6765\u62ff\uff01\r\n\r\n\u7279\u6b8a\u6d3b\u52a8\uff1a\u5fae\u4fe1\u7ea2\u5305\u9886\u4e0d\u505c\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6218\u4e89\u5e73\u539f\uff08\u65b0\u670d\uff09\uff0c\u66ae\u5149\u4e4b\u7ffc\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u8bf7\u67e5\u770b\u5b98\u65b9\u6d3b\u52a8\u8bf4\u660e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u52a0\u5165\u51b0\u706b\u5fae\u4fe1\u7ea2\u5305\u7fa4\uff0c\u6bcf\u65e5\uff0c\u6bcf\u5468\uff0c\u6bcf\u6708\u7686\u53ef\u9886\u53d6\u7ea2\u5305\uff0c\u5148\u52a0\u5165\u5148\u5f97\u3002\u6bcf\u6708\u7fa4\u4e0a\u9650100\u4eba\u3002\r\n\r\n\u6d3b\u52a8\u4e00\uff1a\u9996\u5145\/\u6b21\u5145\u5927\u8d60\u9001\u6d3b\u52a8\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u5145\u503c\u540e\u7acb\u5373\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u9996\u5145\uff0c\u6b21\u5145\u7eff\u94bb\uff0c\u4e70\u591a\u5c11\uff0c\u9001\u591a\u5c11\uff0c\u8fd8\u80fd\u83b7\u5f97VIP\u7b49\u7ea7\uff0c\u989d\u5916\u518d\u8d60\u9001\u6570\u4e07\u91d1\u5e01\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u4e8c\uff1a\u7d2f\u8ba1\u5145\u503c\u9001\u5927\u793c\u6d3b\u52a8\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u7d2f\u8ba1\u5145\u503c\u8fbe\u5230\u6307\u5b9a\u7eff\u94bb\u6570\u989d\u5373\u53ef\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u7d2f\u8ba1\u5145\u503c\u7eff\u94bb\u6570\u91cf\u8fbe\u5230500,1000\uff08\u9001\u94e0\u7532\u5934\u9886\uff09,3000\uff08\u9001\u5bd2\u51b0\u6218\u58eb\uff09,6000,10000,30000\uff08\u9001\u5f3a\u529b\u968f\u4ece\u827e\u5fb7\u52a0\uff09\u65f6\uff0c\u4f1a\u5206\u522b\u7ed9\u4e88\u4e0d\u540c\u7684\u5956\u52b1\uff0c\u5176\u4e2d\u5305\u542b\u5404\u79cd\u9ad8\u9636\u968f\u4ece\uff0c\u5927\u91cf\u7eff\u94bb\uff0c\u9876\u7ea7\u5b9d\u77f3\uff0c\u7a00\u6709\u9053\u5177\u7b49\u6e38\u620f\u7269\u54c1\u3002\r\n\r\n\u6d3b\u52a8\u4e09\uff1a10\u7ea7\u51b2\u7ea7\u5927\u8d5b\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u6d3b\u52a8\u6761\u4ef6\uff1a10\u7ea7\u4ee5\u4e0b\u73a9\u5bb6\u53ef\u53c2\u52a0\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u51b2\u7ea7\u6210\u529f\u540e\u624b\u52a8\u9886\u5956\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u6d3b\u52a8\u540e1\u5c0f\u65f6\u5185\u5347\u7ea7\u523010\u7ea7\uff0c\u5373\u53ef\u9886\u53d6\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u56db\uff1a23\u7ea7\u51b2\u7ea7\u5927\u8d5b\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u6d3b\u52a8\u6761\u4ef6\uff1a10\u7ea7\u4ee5\u4e0a\uff0c23\u7ea7\u4ee5\u4e0b\u73a9\u5bb6\u53ef\u53c2\u52a0\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u51b2\u7ea7\u6210\u529f\u540e\u624b\u52a8\u9886\u5956\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u6d3b\u52a8\u540e,48\u5c0f\u65f6\u5185\u5347\u7ea7\u523023\u7ea7\uff0c\u5373\u53ef\u9886\u53d6\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u4e94\uff1a\u5f00\u670d\u9650\u65f6\u62a2\u7ea2\u5305 \r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u4e0a\u534812\u70b9-\u4e0b\u53482\u70b9\uff0c\u4e0b\u53487\u70b9-9\u70b9\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u76f4\u63a5\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u5728\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u70b9\u51fb\u62a2\u7ea2\u5305\u65e2\u53ef\u83b7\u5f97\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u516d\uff1a\u5348\u591c\u72c2\u6b22\u62a2\u5b9d\u77f3 \r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u665a\u4e0a11\u70b955\u5206-12\u70b905\u5206\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u76f4\u63a5\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u5728\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u70b9\u51fb\u62a2\u5b9d\u77f3\u5c31\u6709\u53ef\u80fd\u968f\u673a\u83b7\u5f971\u9636\u5b9d\u77f3\u3002\r\n\r\n\u6d3b\u52a8\u4e03\uff1a\u9ec4\u91d1\u56fd\u5ea6\u7684\u4ea4\u6613 \r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u5151\u6362\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u53ef\u4ee5\u4f7f\u7528\u7eff\u94bb\u5151\u6362\u8d85\u591a\u6570\u91cf\u7684\u91d1\u5e01\uff01\r\n\r\n\u6d3b\u52a8\u516b\uff1a\u4e09\u9636\u5b9d\u77f3\u6253\u6298\u51fa\u552e\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u8d2d\u4e70\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u53ef\u8d2d\u4e70\u5927\u5e45\u5ea6\u964d\u4ef7\u7684\u4e09\u9636\u5b9d\u77f3\u5305\uff08\u5185\u542b60\u9897\u4e09\u9636\u5b9d\u77f3\uff09\r\n\r\n\u6d3b\u52a8\u4e5d\uff1a\u795e\u8840\u00b7\u7f8e\u675c\u838e\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u4e0a\u534811\u70b9\u5230\u4e2d\u53481\u70b9\uff0c\u4e0b\u53488\u70b9\u523012\u70b9\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u6218\u6597\u80dc\u5229\u540e\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u7f8e\u675c\u838e\u83b7\u5f97\u5f3a\u608d\u529b\u91cf\u5377\u571f\u91cd\u6765\uff0c\u51fb\u8d25\u5979\u6709\u673a\u7387\u83b7\u5f97\u795e\u79d8\u7684\u9ed1\u6697\u5723\u5668\uff01\r\n\r\n\u6d3b\u52a8\u5341\uff1a\u5987\u5973\u8282\u767b\u9646\u9001\u5927\u793c\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a3\u67088\u65e5\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u76f4\u63a5\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u5728\u6d3b\u52a8\u754c\u9762\u9886\u53d6\u9ad8\u989d\u8282\u65e5\u767b\u9646\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u5341\u4e00\uff1a\u7231\u795e\u8bd5\u70bc\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a3\u670814\u65e5-3\u670824\u65e5\uff08\u4e0a\u534810\u70b9\u5230\u4e2d\u53481\u70b9\uff0c\u4e0b\u53487\u70b9\u523012\u70b9\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u6218\u6597\u80dc\u5229\u540e\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u51fb\u8d25\u7231\u795e\uff0c\u8d62\u5f97\u8bd5\u70bc\u7684\u6210\u529f\uff0c\u5e76\u6709\u673a\u7387\u83b7\u5f97\u7edd\u7248\u7231\u795e\u795d\u798f\u6212\u6307\u3002\r\n\r\n\r\n\u6bcf\u65e5\u6d3b\u52a8\u5185\u5bb9\uff1a \r\n\r\n\u6d3b\u52a8\u4e00\uff1a\u6bcf\u65e5\u6311\u6218\u4e16\u754cBOSS\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u6bcf\u5929\u4e0a\u534812\u70b9\uff0c\u4e0b\u53483\u70b9\uff0c\u665a\u4e0a7\u70b9\uff08\u6d3b\u52a8\u6301\u7eed1\u5c0f\u65f6\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u90ae\u7bb1\u81ea\u52a8\u53d1\u653e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u51fb\u6740\u4e16\u754cBOSS\u5373\u5f97\u4e30\u539a\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u4e8c\uff1a\u6bcf\u65e5\u57ce\u4e3b\u64c2\u53f0\u8d5b\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u90ae\u7bb1\u81ea\u52a8\u53d1\u653e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u64c2\u53f0PK\uff0c\u4e89\u593a\u60a0\u4e45\u795e\u5668\u3002\r\n\r\n\u6d3b\u52a8\u4e09\uff1a\u6bcf\u65e5\u516c\u4f1a\u738b\u57ce\u4e89\u593a\u6218\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u6bcf\u5929\u665a\u4e0a8\u70b9\u5230\u665a\u4e0a9\u70b9\u3002\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u90ae\u7bb1\u81ea\u52a8\u53d1\u653e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u4e89\u593a\u738b\u57ce\u5f52\u5c5e\u6743\uff0c\u72c2\u62a2\u80dc\u5229\u5927\u793c\u5305\u3002\r\n\r\n\u6d3b\u52a8\u56db\uff1a\u6bcf\u65e5\u72e9\u730e\u9ec4\u91d1\u517d\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u4e2d\u534812\u70b9\u5230\u4e0b\u53481\u70b9\u3002\r\n\u9886\u5956\u65b9\u5f0f\uff1aBOSS\u6389\u843d\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u51fb\u8d25\u9ec4\u91d1\u795e\u517d\uff0c\u593a\u53d6\u4ef7\u503c\u4e0d\u83f2\u7684\u795e\u517d\u89d2\u3002\r\n\r\n\u5173\u6ce8\u51b0\u706b\u738b\u5ea7\u5fae\u4fe1\u516c\u4f17\u8d26\u53f7\uff08\u51b0V\u706b\u738b\u5ea7\uff09\u3001\u5b98\u65b9\u7f51\u7ad9(bhwz.zqgame.com)\uff0c\u52a0\u5165\u51b0\u706bAPP\u5b98\u65b91\u7fa4\uff1a38820749\uff0c\u51b0\u706bAPP\u5b98\u65b92\u7fa4\uff1a344293160\uff0c\u9886\u53d6\u8d85\u503c\u6e38\u620f\u793c\u54c1\u5305\uff0c\u5c3d\u4eab\u66f4\u591a\u7cbe\u5f69\u6d3b\u52a8\u5185\u5bb9\uff01\u5982\u679c\u5728\u6e38\u620f\u4e2d\u9047\u5230\u4efb\u4f55\u95ee\u9898\u6216\u56f0\u96be\uff0c\u53ef\u4ee5\u901a\u8fc7\u4ee5\u4e0b\u65b9\u5f0f\u8054\u7cfb\u6211\u4eec\uff1a\r\n\u5ba2\u670d\u7535\u8bdd\uff1a0755-86160520\r\n\u65b0\u6d6a\/\u817e\u8baf\u5fae\u8584\uff1a\u51b0\u706b\u738b\u5ea7OL\r\n\u5ba2\u670dqq\uff1a2642054966\r\n\u5ba2\u670d\u90ae\u7bb1\uff1asykf@zqgame.com","post_time":"1388393088","partner_key":"default,default_full,91,17173,pp,Downjoy,zq,uc"}}';
		}
		else //183.60.255.55
		{
			$result = '{"message":"SERVER_LIST_SUCCESS","activate":0,"server":[{"id":"9","game_id":"B","section_id":"109","account_server_id":"105","server_name":"\u7687\u5bb6\u5b88\u536b(05)","server_ip":"58.68.251.47:8091","server_game_ip":"58.68.251.48","game_message_ip":"10.68.237.95:9899","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"8","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"9999"},{"id":"8","game_id":"B","section_id":"108","account_server_id":"104","server_name":"\u5730\u72f1\u5486\u54ee(04)","server_ip":"58.68.251.45:8091","server_game_ip":"58.68.251.46","game_message_ip":"10.68.237.93:9899","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"7","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"9999"},{"id":"7","game_id":"B","section_id":"107","account_server_id":"103","server_name":"\u5723\u6b4c\u9a91\u58eb(03)","server_ip":"58.68.251.43:8091","server_game_ip":"58.68.251.44","game_message_ip":"10.68.237.91:9899","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"6","server_recommend":"1","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"9999"},{"id":"4","game_id":"B","section_id":"104","account_server_id":"102","server_name":"\u6218\u4e89\u5e73\u539f(02)","server_ip":"183.60.255.76:8091","server_game_ip":"183.60.255.62","game_message_ip":"10.18.234.62:8788","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"5","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"8888"},{"id":"3","game_id":"B","section_id":"103","account_server_id":"101","server_name":"\u66ae\u5149\u4e4b\u7ffc(01)","server_ip":"183.60.255.57:8091","server_game_ip":"183.60.255.54","game_message_ip":"10.18.234.53:8788","server_max_player":"100000","account_count":"0","server_language":"\u4e2d\u6587","server_sort":"4","server_recommend":"0","server_debug":"0","partner":"default,default_full,91,17173,pp,Downjoy,zq,uc","version":"","server_status":"1","server_new":"1","special_ip":"","need_activate":"0","server_starttime":"0","server_game_port":"8888"}],"announce":{"id":"1","summary":"\u3000\u300a\u51b0\u706b\u738b\u5ea7\u300biOS\u6b63\u7248\u4e8e2014\u5e743\u67086\u65e521:00\u5f00\u542fiOS\u6b63\u7248\u9650\u514d\u4e0b\u8f7d\u540e\uff0c\u5927\u91cf\u73a9\u5bb6\u70ed\u60c5\u6d8c\u5165\u6e38\u620f\uff0c\u73b0\u6709\u4e24\u7ec4\u670d\u52a1\u5668\u8fc5\u901f\u8fdb\u5165\u5230\u7206\u6ee1\u72b6\u6001\u3002\u5b98\u65b9\u6b63\u5168\u529b\u90e8\u7f72\uff0c\u529b\u4e89\u7ed9\u5927\u5bb6\u63d0\u4f9b\u7a33\u5b9a\u6d41\u7545\u7684\u6e38\u620f\u4f53\u9a8c\u3002\u5e76\u5b9a\u4e8e2014\u5e743\u67086\u65e521:20\u5f00\u542fApp\u65b0\u670d3\u533a\u3010\u5723\u6b4c\u9a91\u58eb\u3011\uff0c\u8bda\u9080\u5404\u4f4d\u65b0\u8001\u73a9\u5bb6\u7684\u52a0\u5165!\u5bf9\u4e8e\u670d\u52a1\u5668\u6781\u5ea6\u706b\u7206\u5bfc\u81f4\u767b\u9646\u56f0\u96be\u7684\u95ee\u9898\uff0c\u6b63\u5728\u7d27\u6025\u5904\u7406\u4e2d\uff0c\u6e38\u620f\u5c06\u9010\u6b65\u589e\u52a0\u65b0\u670d\u7f13\u89e3\u4eba\u6570\u706b\u7206\u7684\u95ee\u9898\u3002\u6b64\u5916\u5c06\u7edf\u4e00\u4e8e3\u67087\u65e5\u5168\u670d\u53d1\u653e200\u7eff\u94bb\u4f5c\u4e3a\u8865\u507f\uff01\u611f\u8c22\u5927\u5bb6\u7684\u70ed\u60c5\u548c\u652f\u6301\uff01","content":"\u3000\u300a\u51b0\u706b\u738b\u5ea7\u300biOS\u6b63\u7248\u4e8e2014\u5e743\u67086\u65e521:00\u5f00\u542fiOS\u6b63\u7248\u9650\u514d\u4e0b\u8f7d\u540e\uff0c\u5927\u91cf\u73a9\u5bb6\u70ed\u60c5\u6d8c\u5165\u6e38\u620f\uff0c\u73b0\u6709\u4e24\u7ec4\u670d\u52a1\u5668\u8fc5\u901f\u8fdb\u5165\u5230\u7206\u6ee1\u72b6\u6001\u3002\u5b98\u65b9\u6b63\u5168\u529b\u90e8\u7f72\uff0c\u529b\u4e89\u7ed9\u5927\u5bb6\u63d0\u4f9b\u7a33\u5b9a\u6d41\u7545\u7684\u6e38\u620f\u4f53\u9a8c\u3002\u5e76\u5b9a\u4e8e2014\u5e743\u67086\u65e521:20\u5f00\u542fApp\u65b0\u670d3\u533a\u3010\u5723\u6b4c\u9a91\u58eb\u3011\uff0c\u8bda\u9080\u5404\u4f4d\u65b0\u8001\u73a9\u5bb6\u7684\u52a0\u5165!\u5bf9\u4e8e\u670d\u52a1\u5668\u6781\u5ea6\u706b\u7206\u5bfc\u81f4\u767b\u9646\u56f0\u96be\u7684\u95ee\u9898\uff0c\u6b63\u5728\u7d27\u6025\u5904\u7406\u4e2d\uff0c\u6e38\u620f\u5c06\u9010\u6b65\u589e\u52a0\u65b0\u670d\u7f13\u89e3\u4eba\u6570\u706b\u7206\u7684\u95ee\u9898\u3002\u6b64\u5916\u5c06\u7edf\u4e00\u4e8e3\u67087\u65e5\u5168\u670d\u53d1\u653e200\u7eff\u94bb\u4f5c\u4e3a\u8865\u507f\uff01\u611f\u8c22\u5927\u5bb6\u7684\u70ed\u60c5\u548c\u652f\u6301\uff01\r\n\u3000\u3000\r\n\u9650\u65f6\u514d\u8d39\u65f6\u95f4\uff1a3\u67086\u65e521:00-3\u67089\u65e523:59\r\n\u3000\u3000\u3000\u3000\u3000\u3000\u3000\u3000\r\n\u5bf9\u4e8e3\u67086\u65e521:00\u524d\u4e0b\u8f7d\u6e38\u620f\u7684\u73a9\u5bb6\uff0c\u6211\u4eec\u5c06\u7edf\u4e00\u53d1\u653e\u6bcf\u4eba500\u7eff\u94bb\u7684\u5956\u52b1\u3002\uff08\u4e8e3\u67086\u65e521:00\u524d\u7cfb\u7edf\u5411\u5168\u670d\u73a9\u5bb6\u53d1\u653e\uff09\r\n\r\n\u65b0\u589e\u793c\u5305\uff1a\u5927\u5e45\u5ea6\u63d0\u5347\u767b\u9646\u5956\u52b1\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u4e3a\u4e86\u56de\u9988\u73a9\u5bb6\u5bf9\u6e38\u620f\u7684\u70ed\u60c5\uff0c\u4ee5\u53ca\u5bf9\u6e38\u620f\u63d0\u51fa\u7684\u5404\u79cd\u5efa\u8bae\u548c\u5e2e\u52a9\uff0c\u6240\u4ee5\u5927\u5e45\u5ea6\u63d0\u5347\u6bcf\u65e5\u767b\u9646\u5956\u52b1\u5e45\u5ea6\uff0c\u4ee5\u8868\u793a\u6211\u4eec\u5bf9\u73a9\u5bb6\u7531\u8877\u7684\u611f\u8c22\uff0c\u4ee5\u540e\u8fd8\u4f1a\u9010\u6b65\u63d0\u5347\u5956\u52b1\u7684\u6570\u91cf\uff0c\u8bf7\u5e7f\u5927\u73a9\u5bb6\u7ee7\u7eed\u652f\u6301\u51b0\u706b\u738b\u5ea7\uff01\r\n\r\n\u8fd1\u671f\u6d3b\u52a8\u4e0e\u793c\u5305\u5185\u5bb9\uff1a\r\n\u65b0\u589e\u793c\u5305\uff1a30\u7ea7\u52c7\u58eb\u793c\u5305\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u7b49\u7ea7\u5230\u8fbe30\u7ea7\u65e2\u53ef\u514d\u8d39\u9886\u53d6\u7684\u793c\u5305\uff0c\u5305\u542b800\u7eff\u94bb\u7b49\u9ad8\u7ea7\u5956\u52b1\uff0c\u5feb\u901f\u6b66\u88c5\u81ea\u5df1\u5427\uff01 \r\n\r\n\u65b0\u589e\u793c\u5305\uff1a45\u7ea7\u6218\u795e\u793c\u5305\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u7b49\u7ea7\u5230\u8fbe45\u7ea7\u65e2\u53ef\u514d\u8d39\u9886\u53d6\u7684\u793c\u5305\uff0c\u5305\u542b3000\u7eff\u94bb\uff0c\u56db\u661f\u5b9d\u77f3\u7b49\u8d85\u8c6a\u534e\u7ea7\u5956\u52b1\uff01\r\n\r\n\u65b0\u589e\u793c\u5305\uff1a10\u7ea7\uff0c20\u7ea7\u514d\u8d39\u9886\u53d6\u6280\u80fd\u70b9\u793c\u5305\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u793c\u5305\u754c\u9762\u53ef\u9886\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u4e3a\u4e86\u964d\u4f4e\u73a9\u5bb6\u95ef\u5173\u7684\u96be\u5ea6\uff0c\u7279\u6b64\u63a8\u51fa\u514d\u8d39\u6280\u80fd\u70b9\u793c\u5305\uff0c\u5f53\u73a9\u5bb6\u7b49\u7ea7\u5230\u8fbe10\u7ea7\u548c20\u7ea7\u65f6\uff0c\u53ef\u5206\u522b\u9886\u53d6\u5305\u542b\u6d77\u91cf\u6280\u80fd\u70b9\u7684\u793c\u5305\uff0c\u70b9\u51fb\u4f7f\u7528\u540e\u53ef\u5927\u5e45\u5ea6\u589e\u52a0\u89d2\u8272\u7684\u6280\u80fd\u70b9\u3002\r\n\r\n\u65b0\u589e\u6d3b\u52a8\uff1a\u6700\u5f3a\u516c\u4f1a\u6392\u540d\u8d5b\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u7cfb\u7edf\u90ae\u4ef6\u53d1\u9001\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6bcf\u4e24\u5468\u8bc4\u9009\u4e00\u6b21\u6700\u5f3a\u516c\u4f1a\uff0c\u5c06\u6839\u636e\u516c\u4f1a\u5360\u9886\u7684\u57ce\u5821\u6570\u91cf\u3001\u516c\u4f1a\u6210\u5458\u6570\u4ee5\u53ca\u516c\u4f1a\u6210\u5458\u5e73\u5747\u7b49\u7ea7\uff0c\u603b\u8363\u8a89\u5ea6\u8fdb\u884c\u6392\u540d\u3002\u8bc4\u9009\u51fa\u4e24\u5468\u5185\u670d\u52a1\u5668\u4e2d\u7684\u6700\u5f3a\u516c\u4f1a\uff0c\u5e76\u53d1\u653e\u4e0d\u540c\u91d1\u989d\u7684\u7eff\u94bb\u53ca\u91d1\u5e01\u5956\u52b1\uff0c\u6240\u6709\u516c\u4f1a\u6210\u5458\u7686\u53ef\u83b7\u5f97\u6d77\u91cf\u798f\u5229\u3002\r\n\r\n\u65b0\u589e\u6d3b\u52a8\uff1a\u51b0\u706b\u6218\u573a\u4e89\u9738\u8d5b\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6240\u6709\u670d\u52a1\u5668\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a2014.3.7\uff08\u6c38\u4e45\u5f00\u542f\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u7cfb\u7edf\u90ae\u4ef6\u53d1\u9001\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u51b0\u706b\u6218\u573a\u4e89\u9738\u8d5b\uff0c\u6bcf\u5468PVP\u6392\u884c\u7248\u5956\u52b1\u9001\u4e0d\u505c\uff0c\u7fa4\u96c4\u9010\u9e7f\uff0c\u8c01\u4e0e\u4e89\u950b\uff1f\u6211\u4eec\u62ed\u76ee\u4ee5\u5f85\uff01\u6bcf\u5468\u4e94\u665a\u4e0a23\uff1a59\u5206\uff0c\u8363\u8a89\u6392\u884c\u699c\u4e2d20\u540d\u73a9\u5bb6\u90fd\u53ef\u4ee5\u83b7\u5f97\u5927\u91cf\u7eff\u94bb\u5956\u52b1\uff0c\u8fd8\u6709\u73cd\u8d35\u7684\u5f3a\u529b\u8363\u8a89\u88c5\u5907\u7b49\u4f60\u6765\u62ff\uff01\r\n\r\n\u7279\u6b8a\u6d3b\u52a8\uff1a\u5fae\u4fe1\u7ea2\u5305\u9886\u4e0d\u505c\r\n\u6d3b\u52a8\u6240\u5c5e\u670d\u52a1\u5668\uff1a\u6218\u4e89\u5e73\u539f\uff08\u65b0\u670d\uff09\uff0c\u66ae\u5149\u4e4b\u7ffc\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u8bf7\u67e5\u770b\u5b98\u65b9\u6d3b\u52a8\u8bf4\u660e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u52a0\u5165\u51b0\u706b\u5fae\u4fe1\u7ea2\u5305\u7fa4\uff0c\u6bcf\u65e5\uff0c\u6bcf\u5468\uff0c\u6bcf\u6708\u7686\u53ef\u9886\u53d6\u7ea2\u5305\uff0c\u5148\u52a0\u5165\u5148\u5f97\u3002\u6bcf\u6708\u7fa4\u4e0a\u9650100\u4eba\u3002\r\n\r\n\u6d3b\u52a8\u4e00\uff1a\u9996\u5145\/\u6b21\u5145\u5927\u8d60\u9001\u6d3b\u52a8\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u5145\u503c\u540e\u7acb\u5373\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u9996\u5145\uff0c\u6b21\u5145\u7eff\u94bb\uff0c\u4e70\u591a\u5c11\uff0c\u9001\u591a\u5c11\uff0c\u8fd8\u80fd\u83b7\u5f97VIP\u7b49\u7ea7\uff0c\u989d\u5916\u518d\u8d60\u9001\u6570\u4e07\u91d1\u5e01\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u4e8c\uff1a\u7d2f\u8ba1\u5145\u503c\u9001\u5927\u793c\u6d3b\u52a8\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u7d2f\u8ba1\u5145\u503c\u8fbe\u5230\u6307\u5b9a\u7eff\u94bb\u6570\u989d\u5373\u53ef\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u7d2f\u8ba1\u5145\u503c\u7eff\u94bb\u6570\u91cf\u8fbe\u5230500,1000\uff08\u9001\u94e0\u7532\u5934\u9886\uff09,3000\uff08\u9001\u5bd2\u51b0\u6218\u58eb\uff09,6000,10000,30000\uff08\u9001\u5f3a\u529b\u968f\u4ece\u827e\u5fb7\u52a0\uff09\u65f6\uff0c\u4f1a\u5206\u522b\u7ed9\u4e88\u4e0d\u540c\u7684\u5956\u52b1\uff0c\u5176\u4e2d\u5305\u542b\u5404\u79cd\u9ad8\u9636\u968f\u4ece\uff0c\u5927\u91cf\u7eff\u94bb\uff0c\u9876\u7ea7\u5b9d\u77f3\uff0c\u7a00\u6709\u9053\u5177\u7b49\u6e38\u620f\u7269\u54c1\u3002\r\n\r\n\u6d3b\u52a8\u4e09\uff1a10\u7ea7\u51b2\u7ea7\u5927\u8d5b\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u6d3b\u52a8\u6761\u4ef6\uff1a10\u7ea7\u4ee5\u4e0b\u73a9\u5bb6\u53ef\u53c2\u52a0\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u51b2\u7ea7\u6210\u529f\u540e\u624b\u52a8\u9886\u5956\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u6d3b\u52a8\u540e1\u5c0f\u65f6\u5185\u5347\u7ea7\u523010\u7ea7\uff0c\u5373\u53ef\u9886\u53d6\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u56db\uff1a23\u7ea7\u51b2\u7ea7\u5927\u8d5b\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u6d3b\u52a8\u6761\u4ef6\uff1a10\u7ea7\u4ee5\u4e0a\uff0c23\u7ea7\u4ee5\u4e0b\u73a9\u5bb6\u53ef\u53c2\u52a0\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u51b2\u7ea7\u6210\u529f\u540e\u624b\u52a8\u9886\u5956\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u6d3b\u52a8\u540e,48\u5c0f\u65f6\u5185\u5347\u7ea7\u523023\u7ea7\uff0c\u5373\u53ef\u9886\u53d6\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u4e94\uff1a\u5f00\u670d\u9650\u65f6\u62a2\u7ea2\u5305 \r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u4e0a\u534812\u70b9-\u4e0b\u53482\u70b9\uff0c\u4e0b\u53487\u70b9-9\u70b9\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u76f4\u63a5\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u5728\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u70b9\u51fb\u62a2\u7ea2\u5305\u65e2\u53ef\u83b7\u5f97\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u516d\uff1a\u5348\u591c\u72c2\u6b22\u62a2\u5b9d\u77f3 \r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u665a\u4e0a11\u70b955\u5206-12\u70b905\u5206\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u76f4\u63a5\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u73a9\u5bb6\u5728\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u70b9\u51fb\u62a2\u5b9d\u77f3\u5c31\u6709\u53ef\u80fd\u968f\u673a\u83b7\u5f971\u9636\u5b9d\u77f3\u3002\r\n\r\n\u6d3b\u52a8\u4e03\uff1a\u9ec4\u91d1\u56fd\u5ea6\u7684\u4ea4\u6613 \r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u5151\u6362\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u53ef\u4ee5\u4f7f\u7528\u7eff\u94bb\u5151\u6362\u8d85\u591a\u6570\u91cf\u7684\u91d1\u5e01\uff01\r\n\r\n\u6d3b\u52a8\u516b\uff1a\u4e09\u9636\u5b9d\u77f3\u6253\u6298\u51fa\u552e\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u8d2d\u4e70\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u53ef\u8d2d\u4e70\u5927\u5e45\u5ea6\u964d\u4ef7\u7684\u4e09\u9636\u5b9d\u77f3\u5305\uff08\u5185\u542b60\u9897\u4e09\u9636\u5b9d\u77f3\uff09\r\n\r\n\u6d3b\u52a8\u4e5d\uff1a\u795e\u8840\u00b7\u7f8e\u675c\u838e\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u4e0a\u534811\u70b9\u5230\u4e2d\u53481\u70b9\uff0c\u4e0b\u53488\u70b9\u523012\u70b9\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u6218\u6597\u80dc\u5229\u540e\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u7f8e\u675c\u838e\u83b7\u5f97\u5f3a\u608d\u529b\u91cf\u5377\u571f\u91cd\u6765\uff0c\u51fb\u8d25\u5979\u6709\u673a\u7387\u83b7\u5f97\u795e\u79d8\u7684\u9ed1\u6697\u5723\u5668\uff01\r\n\r\n\u6d3b\u52a8\u5341\uff1a\u5987\u5973\u8282\u767b\u9646\u9001\u5927\u793c\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a3\u67088\u65e5\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u76f4\u63a5\u9886\u53d6\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u6d3b\u52a8\u5f00\u59cb\u540e\uff0c\u5728\u6d3b\u52a8\u754c\u9762\u9886\u53d6\u9ad8\u989d\u8282\u65e5\u767b\u9646\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u5341\u4e00\uff1a\u7231\u795e\u8bd5\u70bc\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a3\u670814\u65e5-3\u670824\u65e5\uff08\u4e0a\u534810\u70b9\u5230\u4e2d\u53481\u70b9\uff0c\u4e0b\u53487\u70b9\u523012\u70b9\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u6218\u6597\u80dc\u5229\u540e\u83b7\u5f97\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u51fb\u8d25\u7231\u795e\uff0c\u8d62\u5f97\u8bd5\u70bc\u7684\u6210\u529f\uff0c\u5e76\u6709\u673a\u7387\u83b7\u5f97\u7edd\u7248\u7231\u795e\u795d\u798f\u6212\u6307\u3002\r\n\r\n\r\n\u6bcf\u65e5\u6d3b\u52a8\u5185\u5bb9\uff1a \r\n\r\n\u6d3b\u52a8\u4e00\uff1a\u6bcf\u65e5\u6311\u6218\u4e16\u754cBOSS\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u6bcf\u5929\u4e0a\u534812\u70b9\uff0c\u4e0b\u53483\u70b9\uff0c\u665a\u4e0a7\u70b9\uff08\u6d3b\u52a8\u6301\u7eed1\u5c0f\u65f6\uff09\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u90ae\u7bb1\u81ea\u52a8\u53d1\u653e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u51fb\u6740\u4e16\u754cBOSS\u5373\u5f97\u4e30\u539a\u5956\u52b1\u3002\r\n\r\n\u6d3b\u52a8\u4e8c\uff1a\u6bcf\u65e5\u57ce\u4e3b\u64c2\u53f0\u8d5b\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u5168\u5929\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u90ae\u7bb1\u81ea\u52a8\u53d1\u653e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u53c2\u4e0e\u64c2\u53f0PK\uff0c\u4e89\u593a\u60a0\u4e45\u795e\u5668\u3002\r\n\r\n\u6d3b\u52a8\u4e09\uff1a\u6bcf\u65e5\u516c\u4f1a\u738b\u57ce\u4e89\u593a\u6218\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u6bcf\u5929\u665a\u4e0a8\u70b9\u5230\u665a\u4e0a9\u70b9\u3002\r\n\u9886\u5956\u65b9\u5f0f\uff1a\u90ae\u7bb1\u81ea\u52a8\u53d1\u653e\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u4e89\u593a\u738b\u57ce\u5f52\u5c5e\u6743\uff0c\u72c2\u62a2\u80dc\u5229\u5927\u793c\u5305\u3002\r\n\r\n\u6d3b\u52a8\u56db\uff1a\u6bcf\u65e5\u72e9\u730e\u9ec4\u91d1\u517d\r\n\u6d3b\u52a8\u5f00\u542f\u65f6\u95f4\uff1a\u4e2d\u534812\u70b9\u5230\u4e0b\u53481\u70b9\u3002\r\n\u9886\u5956\u65b9\u5f0f\uff1aBOSS\u6389\u843d\r\n\u6d3b\u52a8\u89c4\u5219\uff1a\u51fb\u8d25\u9ec4\u91d1\u795e\u517d\uff0c\u593a\u53d6\u4ef7\u503c\u4e0d\u83f2\u7684\u795e\u517d\u89d2\u3002\r\n\r\n\u5173\u6ce8\u51b0\u706b\u738b\u5ea7\u5fae\u4fe1\u516c\u4f17\u8d26\u53f7\uff08\u51b0V\u706b\u738b\u5ea7\uff09\u3001\u5b98\u65b9\u7f51\u7ad9(bhwz.zqgame.com)\uff0c\u52a0\u5165\u51b0\u706bAPP\u5b98\u65b91\u7fa4\uff1a38820749\uff0c\u51b0\u706bAPP\u5b98\u65b92\u7fa4\uff1a344293160\uff0c\u9886\u53d6\u8d85\u503c\u6e38\u620f\u793c\u54c1\u5305\uff0c\u5c3d\u4eab\u66f4\u591a\u7cbe\u5f69\u6d3b\u52a8\u5185\u5bb9\uff01\u5982\u679c\u5728\u6e38\u620f\u4e2d\u9047\u5230\u4efb\u4f55\u95ee\u9898\u6216\u56f0\u96be\uff0c\u53ef\u4ee5\u901a\u8fc7\u4ee5\u4e0b\u65b9\u5f0f\u8054\u7cfb\u6211\u4eec\uff1a\r\n\u5ba2\u670d\u7535\u8bdd\uff1a0755-86160520\r\n\u65b0\u6d6a\/\u817e\u8baf\u5fae\u8584\uff1a\u51b0\u706b\u738b\u5ea7OL\r\n\u5ba2\u670dqq\uff1a2642054966\r\n\u5ba2\u670d\u90ae\u7bb1\uff1asykf@zqgame.com","post_time":"1388393088","partner_key":"default,default_full,91,17173,pp,Downjoy,zq,uc"}}';
		}
		
		$partner	=	$this->input->get_post('partner', TRUE);
		$mode		=	$this->input->get_post('mode', TRUE);
		$lang		=	$this->input->get_post('language', TRUE);
		$ver		=	$this->input->get_post('client_version', TRUE);
		
// 		if($partner != 'default' && $partner != 'default_full' && $mode != 'debug')
// 		{
// 			$jsonData = Array(
// 					'errors'			=>	'《冰火王座》精英封测已于2014年1月15日圆满结束，请耐心等待公测的到来！'
// 			);
// 			echo $this->return_format->format($jsonData, $format);
// 			exit();
// 		}
		
		$parameter = array(
			'order_by'			=>	'server_sort'
		);
		
		if($partner===FALSE || empty($partner))
		{
			$partner = 'default';
		}
		else if($partner == 'default_full')
		{
			$this->get_temp_hd_list();
			exit();
		}
		else if($partner != 'default')
		{
			$this->get_sdk_debug_list();
			exit();
// 			$jsonData = Array(
// 					'errors'			=>	'《冰火王座》精英封测已于2014年1月15日圆满结束，请前往App Store下载最新客户端。'
// 			);
// 			echo $this->return_format->format($jsonData, $format);
// 			exit();
		}
		else
		{
			$parameter['partner'] = $partner;
		}
		
		if(!empty($ver) && $ver == '1.1')
		{
			$this->get_temp_version_list();
			exit();
		}
		
		if($mode===FALSE || empty($mode))
		{
			$parameter['server_debug'] = 0;
		}
		elseif($mode=='debug')
		{
// 			$parameter['server_debug'] = 1;
			$parameter['server_mode'] = 'all';
		}
		elseif($mode=='all')
		{
			$jsonData = Array(
					'errors'			=>	'《冰火王座》精英封测已于2014年1月15日圆满结束，请前往App Store下载最新客户端。'
			);
			echo $this->return_format->format($jsonData, $format);
			exit();
		}
		else
		{
			$parameter['server_debug'] = 0;
		}
		
		$productdb = $this->load->database('productdb', TRUE);
		$sql = "SELECT `server_id` FROM `server_balance_check` WHERE `next_active` = 1";
		$next = $productdb->query($sql)->row();
		$next = intval($next->server_id);
		
		$jsonData = json_decode($result);
		for($i = 0; $i<count($jsonData->server); $i++)
		{
			$jsonData->server[$i]->server_recommend = 0;
		}
		$jsonData->server[$next]->server_recommend = 1;
		
		$nextServer = $next + 1;
		$sql = "UPDATE `server_balance_check` SET `next_active` = 1 WHERE `server_id`={$nextServer}";
		$productdb->query($sql);
		$sql = "UPDATE `server_balance_check` SET `next_active` = 0 WHERE `server_id`={$next}";
		$productdb->query($sql);
		
		echo $this->return_format->format($jsonData, $format);
	}
	
	private function get_temp_hd_list()
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	'110'
		);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		
		$lang = 'zh-cn';

		$this->lang->load('server_list', $lang);
		$this->load->helper('language');
		$this->load->helper('array');
		if(!empty($result))
		{
			for($i=0; $i<count($result); $i++)
			{
				$serverName = lang('server_list_' . $result[$i]->server_name);
				if(!empty($serverName)) {
					$result[$i]->server_name = $serverName;
				}
				$result[$i]->server_language = lang('server_list_language_' . $result[$i]->server_language);
				
				$result[$i]->server_ip = json_decode($result[$i]->server_ip);
				if(count($result[$i]->server_ip) > 0)
				{
					$result[$i]->server_ip = random_element($result[$i]->server_ip);
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip[0];
				}
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
				$result[$i]->server_game_ip = json_decode($result[$i]->server_game_ip);
				if(count($result[$i]->server_game_ip) > 0)
				{
					$result[$i]->server_game_ip = random_element($result[$i]->server_game_ip);
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip[0];
				}
				$result[$i]->server_game_port = $result[$i]->server_game_ip->port;
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	'default_full'
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
	
	private function get_temp_version_list()
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	'109'
		);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		
		$lang = 'zh-cn';

		$this->lang->load('server_list', $lang);
		$this->load->helper('language');
		$this->load->helper('array');
		if(!empty($result))
		{
			for($i=0; $i<count($result); $i++)
			{
				$serverName = lang('server_list_' . $result[$i]->server_name);
				if(!empty($serverName)) {
					$result[$i]->server_name = $serverName;
				}
				$result[$i]->server_language = lang('server_list_language_' . $result[$i]->server_language);
				
				$result[$i]->server_ip = json_decode($result[$i]->server_ip);
				if(count($result[$i]->server_ip) > 0)
				{
					$result[$i]->server_ip = random_element($result[$i]->server_ip);
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip[0];
				}
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
				$result[$i]->server_game_ip = json_decode($result[$i]->server_game_ip);
				if(count($result[$i]->server_game_ip) > 0)
				{
					$result[$i]->server_game_ip = random_element($result[$i]->server_game_ip);
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip[0];
				}
				$result[$i]->server_game_port = $result[$i]->server_game_ip->port;
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	'default_full'
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
	
	private function get_sdk_debug_list()
	{
		$serverIp	=	$this->input->server('SERVER_ADDR');
		if($serverIp == '122.13.131.55')
		{
			$ipFlag = 'ip2';
		}
		else //183.60.255.55
		{
			$ipFlag = 'ip';
		}
		$parameter = array(
				'account_server_id'		=>	'109'
		);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult($parameter);
		
		$lang = 'zh-cn';

		$this->lang->load('server_list', $lang);
		$this->load->helper('language');
		$this->load->helper('array');
		if(!empty($result))
		{
			for($i=0; $i<count($result); $i++)
			{
				$serverName = lang('server_list_' . $result[$i]->server_name);
				if(!empty($serverName)) {
					$result[$i]->server_name = $serverName;
				}
				$result[$i]->server_language = lang('server_list_language_' . $result[$i]->server_language);
				
				$result[$i]->server_ip = json_decode($result[$i]->server_ip);
				if(count($result[$i]->server_ip) > 0)
				{
					$result[$i]->server_ip = random_element($result[$i]->server_ip);
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip[0];
				}
				if(empty($result[$i]->server_ip->$ipFlag))
				{
					$result[$i]->server_ip = $result[$i]->server_ip->ip . ':' . $result[$i]->server_ip->port;
				}
				else
				{
					$result[$i]->server_ip = $result[$i]->server_ip->$ipFlag . ':' . $result[$i]->server_ip->port;
				}
	
				$result[$i]->server_game_ip = json_decode($result[$i]->server_game_ip);
				if(count($result[$i]->server_game_ip) > 0)
				{
					$result[$i]->server_game_ip = random_element($result[$i]->server_game_ip);
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip[0];
				}
				$result[$i]->server_game_port = $result[$i]->server_game_ip->port;
				if(empty($result[$i]->server_game_ip->$ipFlag))
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->ip;
				}
				else
				{
					$result[$i]->server_game_ip = $result[$i]->server_game_ip->$ipFlag;
				}
				
				$result[$i]->game_message_ip = json_decode($result[$i]->game_message_ip);
				if(count($result[$i]->game_message_ip) > 0)
				{
					$result[$i]->game_message_ip = random_element($result[$i]->game_message_ip);
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip[0];
				}
				if(empty($result[$i]->game_message_ip->$ipFlag))
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->ip . ':' . $result[$i]->game_message_ip->port;
				}
				else
				{
					$result[$i]->game_message_ip = $result[$i]->game_message_ip->$ipFlag . ':' . $result[$i]->game_message_ip->port;
				}
			}
		}
		else
		{
			$result = array();
		}
		
		$this->load->model('mannouncement');
		$parameter = array(
				'partner_key'	=>	'default_full'
		);
		$extension = array(
				'order_by'	=>	array('post_time', 'desc')
		);
		$announce = $this->mannouncement->read($parameter, $extension, 1, 0);
		$announce = empty($announce) ? '' : $announce[0];
		
// 		if($partner == 'default' || $partner == 'default_full')
// 		{
// 			$activate = 0;
// 		}
// 		else
// 		{
// 			$activate = 1;
// 		}
		$activate = 0;
		
		$jsonData = Array(
			'message'			=>	'SERVER_LIST_SUCCESS',
			'activate'			=>	$activate,
			'server'			=>	$result,
			'announce'			=>	$announce
		);
		echo $this->return_format->format($jsonData, 'json');
	}
}
?>
