<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	private $appkey = 'e024d44d86d6d701ecac8ef23c8e8b1bcd7bc0d8';
	
	public function __construct() {
		parent::__construct();
		$this->load->model('return_format');
	}

	public function init($format = 'json')
	{
		$server_id = $this->input->get_post('serv_id', TRUE);
		$nickname = $this->input->get_post('player_id', TRUE);
		$order_id = $this->input->get_post('order_id', TRUE);
		$amount = $this->input->get_post('money', TRUE);
		$create_time = $this->input->get_post('create_time', TRUE);
		$sign = $this->input->get_post('sign', TRUE);

		$check = array($this->appkey . $server_id . $nickname . $order_id . $amount . $create_time . $this->appkey);
		$check = md5(implode('', $check));
		if($sign == $check)
		{
			
		}
		else
		{
			$jsonData = array(
				'err_code'			=>	1,
				'desc'				=>	'sign invalid'
			);
		}
	}
}
?>
