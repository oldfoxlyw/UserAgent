<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Games extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	private $gameId = null;
	private $sectionId = null;
	private $authToken = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('web_account');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
		$this->authKey = $this->config->item('game_auth_key');
		$this->gameId = $this->config->item('battlenet_id');
		$this->sectionId = $this->config->item('game_section_id');
		$this->authToken = $this->authKey[$this->gameId]['auth_key'];
	}
	
	public function game_list($format = 'json') {
		$randGuid = $this->input->post('cache', TRUE);
		$exceptGame = $this->input->post('except', TRUE);
		if(!empty($randGuid)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($randGuid, $exceptGame);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	'',
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			
			$this->load->model('game_product');
			$parameter = array(
				'except_game_id'	=>	json_decode($exceptGame)
			);
			$result = $this->game_product->getAllResult($parameter);
			
			$jsonData = Array(
				'message'		=>	'GAMES_GAMELIST_SUCCESS',
				'result'		=>	json_encode($result)
			);
			echo $this->return_format->format($jsonData, $format);
		} else {
			$jsonData = Array(
				'message'	=>	'GAMES_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'GAMES_GAMELIST_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function add_game($format = 'json') {
		$guid = $this->input->post('guid', TRUE);
		$game_id = $this->input->post('game_id', TRUE);
		
		if(!empty($guid) & !empty($game_id)) {
			$this->load->model('websrv/account_added_game', 'account_game');
			$gameAdded = $this->account_game->get($guid, $game_id);
			if($gameAdded===FALSE) {
				$parameter = array(
					'GUID'		=>	$guid,
					'game_id'	=>	$game_id
				);
				$this->account_game->insert($parameter);
			}
			$jsonData = Array(
				'message'	=>	'GAMES_ADDGAME_SUCCESS'
			);
			echo $this->return_format->format($jsonData, $format);
		} else {
			$jsonData = Array(
				'message'	=>	'GAMES_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'GAMES_ADDGAME_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
	
	public function game_manage($format = 'json') {
		$guid = $this->input->post('guid', TRUE);
		$game_id = $this->input->post('game_id', TRUE);
		if(!empty($guid) && !empty($game_id)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($guid, $game_id);
			//$this->load->helper('security');
			//exit(do_hash(implode('|||', $check) . '|||' . $this->authToken));
			if(!$this->param_check->check($check, $this->authToken)) {
				$jsonData = Array(
					'message'	=>	'PARAM_INVALID'
				);
				echo $this->return_format->format($jsonData, $format);
				$logParameter = array(
					'log_action'	=>	'PARAM_INVALID',
					'account_guid'	=>	'',
					'account_name'	=>	''
				);
				$this->logs->write($logParameter);
				exit();
			}
			/*
			 * 检查完毕
			 */
			$this->load->model('webapi/game_list', 'game_list');
			$gameDetail = $this->game_list->getGame($game_id);
			
			$this->load->model('websrv/server', 'server');
			$parameter = array(
				'game_id'	=>	$game_id
			);
			$serverResult = $this->server->getAllResult($parameter);
			$serverList = array();
			foreach($serverResult as $server) {
				$serverList[$server->account_server_section][$server->account_server_id] = $server;
			}
			
			$this->load->model('game_account');
			$parameter = array(
				'account_guid'		=>	$guid,
				'game_id'			=>	$game_id
			);
			$webAccountResult = $this->web_account->get($guid);
			$gameAccountResult = $this->game_account->getAllResult($parameter);
			$parameter = array(
				'game_detail'	=>	$gameDetail,
				'web_account'	=>	$webAccountResult,
				'game_account'	=>	$gameAccountResult
			);
			$jsonData = Array(
				'message'		=>	'GAMES_GAMEDETAIL_SUCCESS',
				'result'		=>	json_encode($parameter),
				'server_list'	=>	json_encode($serverList)
			);
			echo $this->return_format->format($jsonData, $format);
		} else {
			$jsonData = Array(
				'message'	=>	'GAMES_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'GAMES_GAMEDETAIL_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
}
?>