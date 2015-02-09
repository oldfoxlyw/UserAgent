<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Etl_import extends CI_Controller
{
	public function import()
	{
		set_time_limit(0);

		$fp = fopen('/var/dw/gold_log.log', 'r');
		while(!feof($fp))
		{
			echo fgets($fp) . "\n";
		}
		fclose($fp);
	}
}

?>