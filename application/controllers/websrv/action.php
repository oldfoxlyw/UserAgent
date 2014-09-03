<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Action extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model('return_format');
	}
	
	public function mall($format = 'json')
	{
		$raw_post_data = file_get_contents('php://input', 'r');
		$inputParam = json_decode($raw_post_data);
		$playerId = $inputParam->player_id;
		$roleId = $inputParam->role_id;
		$serverId = $inputParam->server_id;
		$nickname = $inputParam->nickname;
		$logId = $inputParam->log_id;
		$content = $inputParam->log_content;
// 		$playerId = $this->input->get_post('player_id', TRUE);
// 		$roleId = $this->input->get_post('role_id', TRUE);
// 		$nickname = $this->input->get_post('nickname', TRUE);
// 		$logId = $this->input->get_post('log_id', TRUE);
// 		$content = $this->input->get_post('log_content', TRUE);
		
		if(!empty($playerId) && !empty($content))
		{
			// $serverId = empty($serverId) ? '' : $serverId;
			if(empty($serverId))
			{
				$this->load->model('web_account');
				$account = $this->web_account->get($playerId);
				if(!empty($account))
				{
					$serverId = $account->server_id;
				}
				else
				{
					$serverId = '';
				}
			}
			$this->load->model('maction_mall');
			$parameter = array(
					'player_id'		=>	$playerId,
					'role_id'		=>	$roleId,
					'nickname'		=>	$nickname,
					'server_id'		=>	$serverId,
					'content'		=>	$content,
					'posttime'		=>	time()
			);
			if($this->maction_mall->create($parameter))
			{
				$jsonData = array(
						'success'	=>	true,
						'message'	=>	'ACTION_MALL_SUCCESS',
						'log_id'	=>	$logId
				);
				echo $this->return_format->format($jsonData, $format);
			}
			else
			{
				$jsonData = array(
						'success'	=>	false,
						'error'		=>	'ACTION_MALL_ERROR_DATABASE',
						'log_id'	=>	$logId
				);
				echo $this->return_format->format($jsonData, $format);
			}
		}
		else
		{
				$jsonData = array(
						'success'	=>	false,
						'error'		=>	'ACTION_MALL_ERROR_NO_PARAM'
				);
				echo $this->return_format->format($jsonData, $format);
		}
	}

	//参与活动计数
	public function activity_count()
	{
		$server_id = $this->input->post('server_id');
		$activity_id = $this->input->post('id');
		$role_id = $this->input->post('role_id');

		log_message("error", "activity: server_id=" . $server_id . ", activity_id=" . $activity_id . ", role_id=" . $role_id);

		if(!empty($server_id) && !empty($activity_id))
		{
			$this->load->model('mlogactivity');
			$parameter = array(
				'server_id'		=>	$server_id,
				'activity_id'	=>	$activity_id,
				'role_id'		=>	$role_id,
				'time'			=>	time()
			);
			$this->mlogactivity->create($parameter);
		}
	}

	//领取礼包计数
	public function pack_count()
	{
		$server_id = $this->input->post('server_id');
		$pack_id = $this->input->post('id');
		$role_id = $this->input->post('role_id');

		log_message("error", "activity: server_id=" . $server_id . ", pack_id=" . $pack_id . ", role_id=" . $role_id);

		if(!empty($server_id) && !empty($pack_id))
		{
			$this->load->model('mlogpack');
			$parameter = array(
				'server_id'		=>	$server_id,
				'pack_id'		=>	$activity_id,
				'role_id'		=>	$role_id,
				'time'			=>	time()
			);
			$this->mlogpack->create($parameter);
		}
	}
}

?>