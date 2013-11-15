<?php
class Router extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function pipi_payment_notification()
	{
		$post = $_POST;
		
		$this->load->model('webapi/connector');
		
		$this->connector->post('http://112.124.37.58:8090/pipi_payment_notification', $post, false);
	}
}

?>