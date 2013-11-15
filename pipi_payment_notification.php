<?php
$post = $_POST;

if(!empty($post))
{
	$postPath = 'http://112.124.37.58:8090/pipi_payment_notification';
	
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
else
{
	echo 'ip = ' . ip_address();
}

function ip_address()
{
	if ($_SERVER('REMOTE_ADDR') AND $_SERVER('HTTP_CLIENT_IP'))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif ($_SERVER('REMOTE_ADDR'))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	elseif ($_SERVER('HTTP_CLIENT_IP'))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif ($_SERVER('HTTP_X_FORWARDED_FOR'))
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	if ($ip === FALSE)
	{
		$ip = '0.0.0.0';
		return $ip;
	}

	if (strpos($ip, ',') !== FALSE)
	{
		$x = explode(',', $ip);
		$ip = trim(end($x));
	}

	return $ip;
}