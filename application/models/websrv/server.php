<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Server extends CI_Model {
	private $accountTable = 'server_list';
	private $accountView = 'server_list_view';
	private $productdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->productdb = $this->load->database('productdb', true);
	}
	
	public function getTotal($parameter = null) {
		if($parameter != null) {
			if(!empty($parameter['game_id'])) {
				$this->productdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_section'])) {
				$this->productdb->where('account_server_section', $parameter['account_server_section']);
			}
			if(!empty($parameter['account_server_id'])) {
				$this->productdb->where('account_server_id', $parameter['account_server_id']);
			}
			if(!empty($parameter['version'])) {
				$this->productdb->where('version', $parameter['version']);
			}
		}
		return $this->productdb->count_all_results($this->accountTable);
	}
	
	public function getAllResult($parameter = null) {
		if($parameter != null) {
			if($parameter['use_cache_style'] == true) {
				$this->productdb->select("account_server_id, server_name, CONCAT(`server_ip`, ':', `server_port`) as `server_ip`, CONCAT(`server_message_ip`, ':', `server_message_port`) as `server_message_ip`, `server_game_ip`, `server_game_port`, `team_server`, `team_server_port`, `starwar_server`, `starwar_server_port`, server_max_player, account_count, server_language, server_recommend", FALSE);
			}
			if(!empty($parameter['game_id'])) {
				$this->productdb->where('game_id', $parameter['game_id']);
			}
			if(!empty($parameter['account_server_section']) || $parameter['account_server_section']==='0' || $parameter['account_server_section']===0) {
				$this->productdb->where('account_server_section', $parameter['account_server_section']);
			}
			if(!empty($parameter['account_server_id']) || $parameter['account_server_id']==='0' || $parameter['account_server_id']===0) {
				$this->productdb->where('account_server_id', $parameter['account_server_id']);
			}
			if($parameter['server_recommend']=='1' || $parameter['server_recommend']=='0') {
				$this->productdb->where('server_recommend', intval($parameter['server_recommend']));
			}
			if(!empty($parameter['server_mode']) && $parameter['server_mode']!='debug') {
				$this->productdb->where('server_mode', $parameter['server_mode']);
			}
			if(!empty($parameter['version'])) {
				$this->productdb->where('version', $parameter['version']);
			}
			if(!empty($parameter['order_by'])) {
				$this->productdb->order_by($parameter['order_by'], 'desc');
			}
		}
		$result = $this->productdb->get($this->accountTable);
//		exit($this->productdb->last_query());
		if($result->num_rows() > 0)
		{
			return $result->result();
		} else {
			return false;
		}
	}
}
?>
