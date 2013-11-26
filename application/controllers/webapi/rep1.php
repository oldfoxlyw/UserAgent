<?php

class Rep1 extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function post($format = 'json')
	{
		$time = $this->input->get('time', TRUE);
		
		if(!empty($time))
		{
			$currentTime = time();
			$date = date('d', $currentTime);
			
			$lastTime = $currentTime - $time;
			$lastDate = date('d', $lastTime);
			
			if($date != $lastDate)
			{
				for($i=$lastTime; $i<=$currentTime; $i+=86400)
				{
					$tmpTime = strtotime(date('Y-m-d', $i) . ' 23:59:59');
					$onlineTime = $tmpTime - $i;
					if($onlineTime > 0)
					{
						$parameter = array(
								'time'			=>	$onlineTime,
								'posttime'		=>	$tmpTime
						);
						var_dump($parameter);
					}
				}
			}
			else
			{
				$parameter = array(
						'time'			=>	$time,
						'posttime'		=>	time()
				);
				var_dump($parameter);
			}
		}
	}
}

?>