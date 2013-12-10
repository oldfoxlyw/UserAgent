<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** 每小时执行一次
 * @author johnny
 *
 */
class Request_online_count extends CI_Controller
{
	private $logcachedb = null;

	public function __construct()
	{
		parent::__construct ();
		$this->logcachedb = $this->load->database('log_cachedb', true);
	}
	
	public function send()
	{
		$this->load->model('websrv/server');
		$serverResult = $this->server->getAllResult();
		foreach($serverResult as $row)
		{
			$row->server_ip = json_decode($row->server_ip);
			$row->server_ip = $row->server_ip[0];
			$row->server_ip = $row->server_ip->ip . ':' . $row->server_ip->port;
			$url = 'http://' . $row->server_ip;
			$count = $this->get($url . '/get_online_count');
			
			$json = json_decode($count);
			if(!empty($json))
			{
				$time = time();
				$this->logcachedb->insert('log_online_count', array(
					'server_id'				=>	$row->account_server_id,
					'log_date'				=>	date('Y-m-d', $time),
					'log_hour'				=>	date('H', $time),
					'log_count'			=>	$json->online_count
				));
			}
		}
	}
	
	private function get($controller, $parameter = null) {
		if(!empty($controller)) {
			$postPath = $controller;
			$ch = curl_init();
			if(!empty($parameter))
			{
				$param = '?' . $this->getQueryString($parameter);
			}
			curl_setopt($ch, CURLOPT_URL, $postPath . $param);
			curl_setopt($ch, CURLOPT_POST, 1);
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