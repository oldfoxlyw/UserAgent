<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Message extends CI_Controller
{
	private $productdb = null;
	
	public function __construct()
	{
		parent::__construct ();
		$this->productdb = $this->load->database('productdb', true);
	}
	
	public function check()
	{
		$this->load->model('mmessage');
		$this->load->model('mserver');
		$this->load->model('webapi/connector');
		
		$serverIp = array();
		$serverResult = $this->mserver->read();
		foreach ($serverResult as $server)
		{
			$server->server_ip = json_decode($server->server_ip);
			$server->server_ip = $server->server_ip[0];
			$serverIp[$server->account_server_id] = 'http://' . $server->server_ip->lan . ':' . $server->server_ip->port;
		}
		
		$time = time();
		$minutes = intval(date('i', $time));
		$hour = intval(date('H', $time));
		$date = intval(date('d', $time));
		
		$parameter = array(
				'starttime <='	=>	$time,
				'endtime >='	=>	$time
		);
		
		$result = $this->mmessage->read($parameter);
		
		$this->load->model('web_account');
		foreach($result as $row)
		{
			$dateArray = explode(',', $row->date);
			if(in_array('*', $dateArray) || in_array($date, $dateArray))
			{
				$hourArray = explode(',', $row->hour);
				if(in_array('*', $hourArray) || in_array($hour, $hourArray))
				{
					$minutesArray = explode(',', $row->minutes);
					if(in_array('*', $minutesArray) || in_array($minutes, $minutesArray))
					{
						if(!empty($row->content))
						{
							$ip = $serverIp[$row->server_id];
							
							$parameter = array(
									'content'			=>	$row->content
							);
							$data = $this->connector->post($ip . '/announcement', $parameter, FALSE);
							
							$sql = "insert into debug(text)values('{$data}')";
							$this->web_account->db()->query($sql);
						}
					}
				}
			}
		}
	}
}

?>