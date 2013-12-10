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
		$serverId	=	$this->input->post('server_id', TRUE);

		$this->load->model('websrv/server', 'server');
		$result = $this->server->getAllResult(array(
				'account_server_id'		=>	$serverId
		));
		
		if(!empty($result))
		{
			$result = $result[0];
			
			$serverName = lang('server_list_' . $result->server_name);
			if(!empty($serverName)) {
				$result->server_name = $serverName;
			}
			$result->server_language = lang('server_list_language_' . $result->server_language);
			
			$result->server_ip = json_decode($result->server_ip);
			if(count($result->server_ip) > 0)
			{
				$result->server_ip = random_element($result->server_ip);
			}
			else
			{
				$result->server_ip = $result->server_ip[0];
			}
			$result->server_ip = $result->server_ip->ip . ':' . $result->server_ip->port;

			$result->server_game_ip = json_decode($result->server_game_ip);
			if(count($result->server_game_ip) > 0)
			{
				$result->server_game_ip = random_element($result->server_game_ip);
			}
			else
			{
				$result->server_game_ip = $result->server_game_ip[0];
			}
			$result->server_game_port = $result->server_game_ip->port;
			$result->server_game_ip = $result->server_game_ip->ip;
			
			$result->game_message_ip = json_decode($result->game_message_ip);
			if(count($result->game_message_ip) > 0)
			{
				$result->game_message_ip = random_element($result->game_message_ip);
			}
			else
			{
				$result->game_message_ip = $result->game_message_ip[0];
			}
			$result->game_message_ip = $result->game_message_ip->ip . ':' . $result->game_message_ip->port;
		}
		else
		{
			$result = array();
		}
		echo $this->return_format->format($result, $format);
	}
}
?>