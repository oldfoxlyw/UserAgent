<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class logs extends CI_Model {
	private $accountTable = 'log_account';
	private $logdb = null;
	
	public function __construct() {
		parent::__construct();
		$this->logdb = $this->load->database('logdb', true);
	}
	
	public function write($parameter) {
		if(!empty($parameter) && !empty($parameter['log_action'])) {
			$uri					=	$this->input->server('REQUEST_URI');
			$relativeMethod			=	$this->input->server('REQUEST_METHOD');
			$requestIp				=	$this->input->ip_address();
			$relativeParameter		=	json_encode($_REQUEST);
			$row = array(
				'log_GUID'				=>	$parameter['account_guid'],
				'device_id'				=>	empty($parameter['device_id']) ? '' : $parameter['device_id'],
				'log_account_name'		=>	$parameter['account_name'],
				'log_account_level'		=>	$parameter['account_level'],
				'log_action'			=>	$parameter['log_action'],
				'log_parameter'			=>	$relativeParameter,
				'log_time'				=>	time(),
				'log_ip'				=>	$requestIp,
				'server_id'				=>	empty($parameter['server_id']) ? '' : $parameter['server_id'],
				'partner_key'			=>	empty($parameter['partner_key']) ? 'default' : $parameter['partner_key']
			);
			$this->logdb->insert($this->accountTable, $row);
		}
	}
	
	public function write_api($parameter) {
		if(!empty($parameter) && !empty($parameter['log_action'])) {
			$uri					=	$this->input->server('REQUEST_URI');
			$relativeMethod			=	$this->input->server('REQUEST_METHOD');
			$requestIp				=	$this->input->ip_address();
			$relativeParameter		=	json_encode($_REQUEST);
			$row = array(
				'log_GUID'				=>	$parameter['account_guid'],
				'log_account_name'		=>	$parameter['account_name'],
				'log_action'			=>	$parameter['log_action'],
				'log_parameter'			=>	$relativeParameter,
				'log_time'		=>	time(),
				'log_ip'				=>	$requestIp,
				'server_id'				=>	empty($parameter['server_id']) ? '' : $parameter['server_id']
			);
			$this->logdb->insert('log_api', $row);
		}
	}
}
?>