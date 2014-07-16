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

		$sql = "SELECT `ip`, `agent` FROM `click_table` WHERE `time` >= '{$datetime} 00:00:00' AND `time` <= '{$datetime} 23:59:59' GROUP BY `ip`";
		$result = $this->channeldb->query($sql)->result();
		$ips = array();
		foreach ($result as $row)
		{
			array_push($ips, array(
				$row->ip 	=> array(
					'count'		=>	0,
					'agent'		=>	$row->agent
				)
			));
		}

		$file = fopen('/home/data/nginx/' . $log_filename, 'r');
		if($file)
		{
			while(!feof($file))
			{
				$line = fgets($file);
				foreach ($ips as $ip => $value)
				{
					if(strpos($line, $ip))
					{
						$ips[$ip]['count']++;
					}
				}
			}
		}
		fclose($file);

		foreach ($ips as $ip=>$value)
		{
			if($value['count'] > 0)
			{
				echo $ip . '=> ';
				var_dump($value);
			}
		}
	}
}

?>