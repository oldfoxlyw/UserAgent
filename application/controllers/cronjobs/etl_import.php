<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Etl_import extends CI_Controller
{
	public function import()
	{
		set_time_limit(0);

		$fp = fopen('/var/dw/gold_log.log', 'r');
		$database = $this->load->database('log_cachedb', TRUE);
		while(!feof($fp))
		{
			$sql = fgets($fp);
			$result = $database->query($sql);
			var_dump($result);
		}
		fclose($fp);
	}
}

?>