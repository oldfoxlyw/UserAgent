<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Check extends CI_Controller
{
	private $channeldb = null;
	
	public function __construct()
	{
		parent::__construct ();
		$this->channeldb = $this->load->database('channeldb', true);
	}

	public function round()
	{
		error_reporting(E_ALL);
		set_time_limit(1800);

		$time = time();
		$prevTime = $time - 86400;
		$datetime = date('Y-m-d', $prevTime);
		$log_filename = 'access.log-' . date('Ymd', $prevTime) . '.gz.log';

		$sql = "SELECT `ip`, `time` FROM `click_table` WHERE `time` >= '{$datetime} 00:00:00' AND `time` <= '{$datetime} 23:59:59'";
		$result = $this->channeldb->query($sql)->result();
		// foreach ($result as $row)
		// {

		// }

		$file = fopen('/home/liyiwen/logs/nginx/' . $log_filename, 'r');
		$line_count = 0;
		while(!feof($file))
		{
			$line = fgets($file);
			$line_count++;
		}

		echo $line_count;
	}
}

?>