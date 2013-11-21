<?php

class Rep extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model('return_format');
	}
	
	public function post($format = 'json')
	{
		$this->load->model('mrep');
		
		$serverId = $this->input->post('server_id', TRUE);
		$guid = $this->input->post('player_id', TRUE);
		$type = $this->input->post('type', TRUE);
		$time = $this->input->post('time', TRUE);
		$profession = $this->input->post('profession', TRUE);
		$nickname = $this->input->post('nickname', TRUE);
		
		$parameter = array(
				'server_id'		=>	$serverId,
				'player_id'		=>	$guid,
				'type'			=>	$type,
				'time'			=>	$time,
				'profession'	=>	$profession,
				'nickname'		=>	$nickname
		);
		$this->mrep->create($parameter);
		
		$jsonData = Array(
				'success'	=>	true
		);
		echo $this->return_format->format($jsonData, $format);
	}
}

?>