<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request_online_count extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function send()
	{
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row)
		{
			$url = 'http://' . $row->server_ip . ':' . $row->server_port;
			$count = $this->get('get_online_count');
			echo $count . '<br>';
		}
	}
	
	private function get($controller, $parameter = null) {
		if(!empty($controller)) {
			$postPath = $controller;
	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $postPath . '?' . $this->getQueryString($parameter));
			$ip = $this->input->ip_address();
			$header = array(
				'CLIENT-IP:' . $ip,
				'X-FORWARDED-FOR:' . $ip,
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	
			$monfd = curl_exec($ch);
	
			curl_close($ch);
	
			return $monfd;
		} else {
			return false;
		}
	}
	
	private function getQueryString($parameter)
	{
		if(!empty($parameter) && is_array($parameter))
		{
			$temp = array();
			foreach($parameter as $key => $value)
			{
				array_push($temp, "{$key}={$value}");
			}
			return implode('&', $temp);
		}
	}
}

?>