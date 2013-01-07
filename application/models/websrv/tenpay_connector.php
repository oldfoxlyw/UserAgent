<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tenpay_connector extends CI_Model {
	private $id;
	private $key;
	private $accountTable = 'recharge_log';
	private $comdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->id = $this->config->item('tenpay_mhc_id');
		$this->key = $this->config->item('tenpay_mhc_key');
		$this->productdb = $this->load->database('comdb', true);
		
		$this->load->helper('signature');
	}
	
	public function startPayment($parameter)
	{
		$this->get('https://gw.tenpay.com/gateway/pay.htm', $parameter);
	}
	
	private function post($url, $parameter = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		if(!empty($parameter))
		{
			$sign = generateSignature(generateQueryString($parameter), $this->key);
			
			foreach($parameter as $key=>$value)
			{
				$parameter[$key] = urlencode($value);
			}
			$parameter['sign'] = $sign;
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$monfd = curl_exec($ch);
		curl_close($ch);
		
		return $monfd;
	}
	
	private function get($url, $parameter = null, $backMode = false)
	{
		if(!empty($parameter))
		{
			$sign = generateSignature(generateQueryString($parameter), $this->key);
			
			foreach($parameter as $key=>$value)
			{
				$parameter[$key] = urlencode($value);
			}
			$parameter['sign'] = $sign;
		}
		
		if($backMode)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url . '?' . generateQueryString($parameter));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$monfd = curl_exec($ch);
			curl_close($ch);
			
			return $monfd;
		}
		else
		{
			redirect($url . '?' . generateQueryString($parameter));
		}
	}
}
?>
