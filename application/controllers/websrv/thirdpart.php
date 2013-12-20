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
		
		$serverId = $this->input->post('server_id', TRUE);
		$post = $this->input->post();
		
		if(!empty($serverId))
		{
			$parameter = array(
					'account_server_id'		=>	$serverId
			);
			$serverResult = $this->server->getAllResult($parameter);
			$serverResult = $serverResult[0];
			if(!empty($serverResult))
			{
				
			}
			$postPath = 'http://112.124.37.58:8090/pipi_payment_notification';
			$data = $this->connector->post($postPath, $post);
			echo $data;
		}
	}
}

?>