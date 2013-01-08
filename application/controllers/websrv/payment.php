<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {
	private $root_path = null;
	private $authKey = null;
	
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('websrv/tenpay_connector');
		$this->load->model('logs');
		$this->load->model('return_format');
		$this->load->model('param_check');
	}
	
	public function init($format = 'json') {
		$accountId = $this->input->get_post('accountId', TRUE);
		$productInfo = $this->input->get_post('productInfo', TRUE);
		$productName = $this->input->get_post('productName', TRUE);
		$totalFee = $this->input->get_post('totalFee', TRUE);
		
		if(!empty($accountId) && !empty($productInfo) && !empty($productName) && !empty($totalFee)) {
			$parameter = array(
				'account_id'			=>	$accountId,
				'item_name'			=>	$productName,
				'item_info'				=>	$productInfo,
				'recharge_amount'	=>	$totalFee,
				'recharge_type'		=>	'TENPAY',
				'recharge_status'	=>	'PROCESS',
				'order_time'			=>	time()
			);
			$this->tenpay_connector->startPayment($parameter);
		} else {
			$jsonData = array(
				'message'		=>	'PAYMENT_NO_PARAM'
			);
		}
		echo $this->return_format->format($jsonData, $format);
	}
}
?>
