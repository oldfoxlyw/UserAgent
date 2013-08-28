<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request_online_count extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function send()
	{
// 		$this->load->model('websrv/server');
// 		$serverResult = $this->server->getAllResult();
// 		foreach($serverResult as $row)
// 		{
// 			$url = 'http://' . $row->server_ip . ':' . $row->server_port;
// 			$count = $this->get($url . '/get_online_count');
// 			echo $count . '<br>';
// 		}
		$return = $this->get('http://192.168.2.230:8090/get_online_count');
		echo $return;
	}
	
	private function get($controller, $parameter = null) {
		if(!empty($controller)) {
			$postPath = $controller;
			echo $postPath . '<br>';
	
			$ch = curl_init();
			
			if(!empty($parameter))
			{
				$param = '?' . $this->getQueryString($parameter);
			}
			curl_setopt($ch, CURLOPT_URL, $postPath . $param);
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