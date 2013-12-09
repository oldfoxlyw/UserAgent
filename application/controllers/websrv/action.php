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
		$playerId = $this->input->get_post('player_id', TRUE);
		$roleId = $this->input->get_post('role_id', TRUE);
		$nickname = $this->input->get_post('nickname', TRUE);
		$logId = $this->input->get_post('log_id', TRUE);
		$content = $this->input->get_post('log_content', TRUE);
		
		$logdb = $this->load->database('logdb', TRUE);
		$logdb->query("insert into `log_test`(`content`)VALUES('" . json_encode($_POST) . "')");
		
		if(!empty($playerId) && !empty($content))
		{
			$this->load->model('maction_mall');
			$parameter = array(
					'player_id'		=>	$playerId,
					'role_id'		=>	$roleId,
					'nickname'		=>	$nickname,
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
}

?>