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
		$this->comdb = $this->load->database('comdb', true);
		
		$this->load->helper('signature');
	}
	
	public function startPayment($parameter)
	{
// 		echo PHP_INT_MAX;
// 		exit();
		$this->comdb->insert($this->accountTable, $parameter);
		$orderId = $this->comdb->insert_id();
		
		exit(strval($orderId));
		$tenpayParam = array(
			'body'			=>	$parameter['item_info'],
			'subject'			=>	$parameter['item_name'],
			'return_url'		=>	'http://42.121.82.226:8081/',
			'notify_url'		=>	'http://localhost/UserAgent/websrv/payment/notifyCallback',
			'partner'		=>	$this->id,
			'out_trade_no'=>	strval($orderId),
			'total_fee'		=>	$parameter['recharge_amount'],
			'fee_type'		=>	1,
			'spbill_create_ip'=>$this->input->ip_address()
		);
		$this->get('https://gw.tenpay.com/gateway/pay.htm', $tenpayParam);
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
