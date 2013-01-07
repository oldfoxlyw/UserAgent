<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funds extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	private $gameId = null;
	private $sectionId = null;
	private $authToken = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('web_account');
		$this->load->model('return_format');
		$this->load->model('param_check');
		$this->authKey = $this->config->item('game_auth_key');
		$this->gameId = $this->config->item('battlenet_id');
		$this->sectionId = $this->config->item('game_section_id');
		$this->authToken = $this->authKey[$this->gameId]['auth_key'];
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
}
?>