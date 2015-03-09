<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Etl_import extends CI_Controller
{
	public function import($date = null)
	{
		ini_set("display_errors", 1);
		error_reporting(E_ALL);
		set_time_limit(0);

		if(empty($date))
		{
			$time = time() - 2 * 86400;
			$date = date('Ymd', $time);
		}
		log_message('custom', '------------------------------------------------------');
		echo "------------------------------------------------------\n";
		log_message('custom', 'Start to ETL progress...');
		echo "Start to ETL progress...\n";
		log_message('custom', '------------------------------------------------------');
		echo "------------------------------------------------------\n";
		log_message('custom', 'Data: ' . $date);
		echo 'Data: ' . $date . "\n";
		log_message('custom', '------------------------------------------------------');
		echo "------------------------------------------------------\n";

		$list = array(
			'arena_score_log',
			'blue_crystal_log',
			'equipment_log',
			'experience_log',
			'gold_log',
			'guild_contribution_log',
			'item_log',
			'retinue_log',
			'role_info_log'
		);
		foreach($list as $item)
		{
			log_message('custom', 'Load ' . $item . '-' . $date);
			echo 'Load ' . $item . '-' . $date . "\n";
			$fp = popen("tar -xzvf /var/dw/{$item}-{$date}", 'r');
			// $fp = fopen('/var/dw/gold_log.log', 'r');
			if(!empty($fp))
			{
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
			else
			{
				log_message('custom', "Warning! /var/dw/{$item}-{$date} not found!");
				echo "Warning! /var/dw/{$item}-{$date} not found!";
			}
		}
		log_message('custom', '------------------------------------------------------');
		echo "------------------------------------------------------\n";
		log_message('custom', 'ETL progress complete.');
		echo "ETL progress complete.\n";
		log_message('custom', '------------------------------------------------------');
		echo "------------------------------------------------------\n";
	}
}

?>