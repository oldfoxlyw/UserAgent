<?php

class Rep extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model('return_format');
	}
	
	public function post($format = 'json')
	{
		$this->load->model('mrep');
		
		$serverId = $this->input->post('server_id', TRUE);
		$guid = $this->input->post('player_id', TRUE);
		$type = $this->input->post('type', TRUE);
		$time = $this->input->post('time', TRUE);
		$profession = $this->input->post('profession', TRUE);
		$nickname = $this->input->post('nickname', TRUE);
		
		if(!empty($serverId) && !empty($guid) && !empty($time))
		{
			$currentTime = time();
			$date = date('d', $currentTime);
			
			$lastTime = $currentTime - $time;
			$lastDate = date('d', $lastTime);

			$this->load->model('web_account');
			$result = $this->web_account->get($guid);
			$partnerKey = empty($result) ? '' : $result->partner_key;
			
			if($date != $lastDate)
			{
				if($time <= 86400)
				{
					$tmpTime = strtotime(date('Y-m-d', $lastTime) . ' 23:59:59');
					$onlineTime = $tmpTime - $lastTime;
					if($onlineTime > 0)
					{
						$parameter = array(
								'server_id'		=>	$serverId,
								'player_id'		=>	$guid,
								'type'			=>	$type,
								'time'			=>	$onlineTime,
								'profession'	=>	$profession,
								'nickname'		=>	$nickname,
								'posttime'		=>	$tmpTime,
								'partner_key'	=>	$partnerKey
						);
						$this->mrep->create($parameter);
						
						$parameter = array(
								'server_id'		=>	$serverId,
								'player_id'		=>	$guid,
								'type'			=>	$type,
								'time'			=>	$time - $onlineTime,
								'profession'	=>	$profession,
								'nickname'		=>	$nickname,
								'posttime'		=>	$currentTime,
								'partner_key'	=>	$partnerKey
						);
						$this->mrep->create($parameter);
					}
				}
			}
			else
			{
				$parameter = array(
						'server_id'		=>	$serverId,
						'player_id'		=>	$guid,
						'type'			=>	$type,
						'time'			=>	$time,
						'profession'	=>	$profession,
						'nickname'		=>	$nickname,
						'posttime'		=>	time(),
						'partner_key'	=>	$partnerKey
				);
				$this->mrep->create($parameter);
			}
			
			$jsonData = Array(
					'success'	=>	1,
					'error_code'=>	0
			);
		}
		else
		{
			$jsonData = Array(
					'success'	=>	0,
					'error_code'=>	ERROR_NO_PARAM,
					'error'		=>	'参数不足，必要参数：server_id, player_id, time'
			);
		}
		echo $this->return_format->format($jsonData, $format);
	}

	public function report_online_count()
	{
		$serverId = $this->input->post('server_id', TRUE);
		$count = $this->input->post('count', TRUE);
		$time = $this->input->post('time', TRUE);

		if(!empty($serverId))
		{
			$this->load->model('monlinecount');

			if(empty($time))
			{
				$time = time();
			}
			$date = date('Y-m-d', $time);
			$hour = date('H', $time);

			$parameter = array(
				'server_id'		=>	$serverId,
				'log_date'		=>	$date,
				'log_hour'		=>	$hour,
				'log_count'		=>	$count
			);
			$this->monlinecount->create($parameter);
		}
	}
}

?>