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

		$this->load->model('maction_mall');
		$extention = array(
				'select'		=>	'content'
		);
		
		if(empty($playerId) && empty($roleId) && empty($nickname))
		{
			$parameter = array(
					'success'		=>	false,
					'error'			=>	'ACTION_MALL_ERROR_NO_PARAM'
			);
		}
		else 
		{
			if(!empty($playerId))
			{
				$parameter = array(
						'player_id'		=>	$playerId
				);
				$result = $this->maction_mall->read($parameter, $extention);
			}
			elseif (!empty($roleId))
			{
				$parameter = array(
						'role_id'		=>	$roleId
				);
				$result = $this->maction_mall->read($parameter, $extention);
			}
			elseif (!empty($nickname))
			{
				$parameter = array(
						'nickname'		=>	$nickname
				);
				$result = $this->maction_mall->read($parameter, $extention);
			}
			
			echo json_encode($result);
		}
	}
}

?>