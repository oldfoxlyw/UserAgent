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
		$guid = $this->input->post('player_id', TRUE);
		$time = $this->input->post('time', TRUE);
		
		$jsonData = Array(
				'success'	=>	true
		);
		echo $this->return_format->format($jsonData, $format);
	}
}

?>