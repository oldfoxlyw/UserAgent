<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thirdpart extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function pipi()
	{
		$this->load->model('webapi/connector');
		$this->load->model('websrv/server');
		
		$serverId = $this->input->post('zone', TRUE);
		$post = $this->input->post();
		
		if(!empty($serverId))
		{
			$serverId = chr(intval($serverId));
			$parameter = array(
					'account_server_id'		=>	$serverId
			);
			$serverResult = $this->server->getAllResult($parameter);
			$serverResult = $serverResult[0];
			if(!empty($serverResult))
			{
				$serverResult->server_ip = json_decode($serverResult->server_ip);
				$serverResult->server_ip = $serverResult->server_ip[0];
				$postPath = 'http://' . $serverResult->server_ip->ip . ':' . $serverResult->server_ip->port . '/pipi_payment_notification';
				$data = $this->connector->post($postPath, $post);
				echo $data;
			}
		}
	}
}

?>