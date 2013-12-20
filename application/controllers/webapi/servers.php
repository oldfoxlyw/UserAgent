<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('return_format');
	}
	
	public function server_list($format = 'json') {
		$serverId = $this->input->post('server_id', TRUE);
		
		if(!empty($serverId))
		{
			$this->load->model('websrv/server', 'server');
			$this->server->select('game_message_ip');
			$result = $this->server->getAllResult(array(
					'account_server_id'		=>	$serverId
			));
			
			if(!empty($result))
			{
				$this->load->helper('array');
				$result = $result[0];
				
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
}
?>