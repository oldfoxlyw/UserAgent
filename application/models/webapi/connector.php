<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Connector extends CI_Model {
	private $enableSSL = false;
	private $maxTry = 3;
	
	public function __construct() {
		parent::__construct();

	}

	public function get($controller, $parameter, $parsePath = true) {
		if(!empty($controller)) {
			$postPath = $controller;
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $postPath . '?' . $this->getQueryString($parameter));
// 			$ip = $this->input->ip_address();
// 			$header = array(
// 				'CLIENT-IP:' . $ip,
// 				'X-FORWARDED-FOR:' . $ip,
// 			);
// 			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			// curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			
			if($this->enableSSL) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			}
				
			$monfd = curl_exec($ch);
				
			curl_close($ch);

			if(empty($monfd))
			{
				if($this->maxTry > 0)
				{
					$this->maxTry--;
					$monfd = $this->get($controller, $parameter, $parsePath);
					return $monfd;
				}
				else
				{
					return false;
				}
			}
				
			return $monfd;
		} else {
			return false;
		}
	}
	
	public function post($controller, $parameter, $parsePath = true, $header = 0) {
		if(!empty($controller)) {
			$postPath = $controller;
			
// 			$parameter['code'] = $this->hash($parameter);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $postPath);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
			$ip = $this->input->ip_address();
// 			$header = array(
// 				'CLIENT-IP:' . $ip,
// 				'X-FORWARDED-FOR:' . $ip,
// 			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			
			if($this->enableSSL) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			}
			
			$monfd = curl_exec($ch);
			
			curl_close($ch);
			
			return $monfd;
		} else {
			return false;
		}
	}
	
	public function getQueryString($parameter)
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