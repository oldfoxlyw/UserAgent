<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {
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
	
	public function init($format = 'json') {
		$productInfo = $this->input->post('productInfo', TRUE);
		$productName = $this->input->post('productName', TRUE);
		$totalFee = $this->input->post('totalFee', TRUE);
		
		if(!empty($productInfo) && !empty($productName) && !empty($totalFee)) {
			
		} else {
			$jsonData = array(
				'message'		=>	'PAYMENT_NO_PARAM'
			);
		}
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
