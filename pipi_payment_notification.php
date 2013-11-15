<?php
error_report(E_ALL);
$post = $_POST;

if(!empty($post))
{
	$postPath = 'http://112.124.37.58:8090/pipi_payment_notification';
	echo ip_address();
	exit($postPath);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $postPath);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		
	$monfd = curl_exec($ch);
		
	curl_close($ch);
}

function ip_address()
{
	if (!empty($_SERVER('REMOTE_ADDR')) && !empty($_SERVER('HTTP_CLIENT_IP')))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER('REMOTE_ADDR')))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	elseif (!empty($_SERVER('HTTP_CLIENT_IP')))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER('HTTP_X_FORWARDED_FOR')))
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	if ($ip == null)
	{
		$ip = '0.0.0.0';
		return $ip;
	}

	return $ip;
}