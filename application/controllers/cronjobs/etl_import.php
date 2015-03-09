<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Etl_import extends CI_Controller
{
	public function import($date)
	{
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
		set_time_limit(0);

		if(empty($date))
		{
			$time = time() - 2 * 86400;
			$date = date('Ymd', $time);
		}

		$fp = popen("tar -xzvf /var/dw/gold_log.log-{$date}", 'r');
		// $fp = fopen('/var/dw/gold_log.log', 'r');
		$database = $this->load->database('log_cachedb', TRUE);
		while(!feof($fp))
		{
			$sql = fgets($fp);
			$result = $database->query($sql);
			var_dump($result);
		}
		// fclose($fp);
		pclose($fp);
	}
}

?>