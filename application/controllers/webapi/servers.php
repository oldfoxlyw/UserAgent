<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	private $gameId = null;
	private $sectionId = null;
	private $authToken = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
		$this->authKey = $this->config->item('game_auth_key');
		$this->gameId = $this->config->item('battlenet_id');
		$this->sectionId = $this->config->item('game_section_id');
		$this->authToken = $this->authKey[$this->gameId]['auth_key'];
	}
	
	public function server_list($format = 'json') {
		$game_id = $this->input->post('game_id', TRUE);
		if(!empty($game_id)) {
			/*
			 * 检测参数合法性
			 */
			$check = array($game_id);
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
			$this->load->model('websrv/server', 'server');
			$parameter = array(
				'game_id'	=>	$game_id
			);
			$result = $this->server->getAllResult($parameter);
			
			$jsonData = Array(
				'message'	=>	'SERVER_LIST_SUCCESS',
				'result'	=>	json_encode($result)
			);
			echo $this->return_format->format($jsonData, $format);
		} else {
			$jsonData = Array(
				'message'	=>	'SERVER_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
				
			$logParameter = array(
				'log_action'	=>	'SERVER_LIST_ERROR_NO_PARAM',
				'account_guid'	=>	'',
				'account_name'	=>	''
			);
			$this->logs->write($logParameter);
		}
	}
}
?>