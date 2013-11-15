<?php
$post = $_POST;

if(!empty($post))
{
	$postPath = 'http://112.124.37.58:8090/pipi_payment_notification';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $postPath);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$ip = $this->input->ip_address();
	$header = array(
			'CLIENT-IP:' . $ip,
			'X-FORWARDED-FOR:' . $ip,
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		
	if($this->enableSSL) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	}
		
	$monfd = curl_exec($ch);
		
	curl_close($ch);
}