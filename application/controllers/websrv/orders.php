<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('websrv/order');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
	}
	
	public function check($format = 'json') {
		$gameId = $this->input->get_post('game_id', TRUE);
		$sectionId = $this->input->get_post('server_section', TRUE);
		$serverId = $this->input->get_post('server_id', TRUE);
		$playerId = $this->input->get_post('player_id', TRUE);
		$checkSum = $this->input->get_post('checksum', TRUE);
		
		if(!empty($gameId) && !empty($sectionId) && !empty($serverId) && !empty($playerId) && !empty($checkSum)) {
			$result = $this->order->get($checkSum);
			//var_dump($result);
			//exit();
			if($result==FALSE)
			{
				$parameter = array(
					'player_id'		=>	$playerId,
					'game_id'		=>	$gameId,
					'section_id'		=>	$sectionId,
					'server_id'		=>	$serverId,
					'checksum'		=>	$checkSum
				);
				$this->order->insert($parameter);
				
				$jsonData = array(
					'message'		=>	'ORDERS_ADDED'
				);
			} else {
				$this->order->addCount($checkSum);
				$jsonData = array(
					'message'		=>	'ORDERS_EXIST'
				);
			}
		} else {
				$jsonData = array(
					'message'		=>	'ORDERS_NO_PARAM'
				);
		}
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
