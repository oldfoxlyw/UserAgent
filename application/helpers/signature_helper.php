<?php
function generateQueryString($parameter)
{
	ksort($parameter);
	
	$queryArray = Array();
	
	foreach($parameter as $key => $value)
	{
		if(!empty($value))
		{
			array_push($queryArray, "{$key}={$value}");
		}
	}
	
	return implode('&', $queryArray);
}

function generateSignature($queryString, $key)
{
	if(!empty($queryString) && !empty($key))
	{
		return strtoupper(md5($queryString . '&key=' . $key));
	}
	
	return 0;
}
?>