<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('return_format');
	}
	
	public function server_list($format = 'json')
	{
		$jsonData = Array(
				'errors'			=>	'《冰火王座》全服例行维护中，预计将于11:00结束，由此给各位玩家造成的不便，我们深表歉意！'
		);
		echo $this->return_format->format($jsonData, $format);
		exit();
	}
}
?>
