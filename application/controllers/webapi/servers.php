<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('return_format');
	}
	
	public function server_list($format = 'json') {
		$serverId = $this->input->get_post('server_id', TRUE);
		
		if(!empty($serverId))
		{
			$this->load->model('webapi/mserver');
			$result = $this->mserver->read(array(
					'account_server_id'		=>	$serverId
			), array(
					'select'	=>	'game_message_ip'
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
				exit('{}');
			}
			echo $this->return_format->format($result, $format);
		}
	}

	/*
	给爱立德公司调用的专用接口
	*/
	public function get_list($format = 'json')
	{
		$this->load->model('webapi/mserver');

		$sql = "SELECT `account_server_id` AS `serv_id`, `server_name` AS `serv_name` FROM `server_list` WHERE `server_status` != 9";
		$result = $this->mserver->query($sql);

		$jsonData = array(
			'err_code'		=>	0,
			'serv'			=>	$result
		);

		echo $this->return_format->format($jsonData, $format);
	}
}
?>