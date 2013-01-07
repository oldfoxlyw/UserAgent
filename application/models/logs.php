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
			$relativeParameter		=	json_encode($_REQUEST);
			$currentTime			=	time();
			$currentTimeLocal		=	date("Y-m-d H:i:s", $currentTime);
			
			$logAction = $parameter['log_action'];
			$logActionArg = explode('_', $logAction);
			switch($logActionArg[0]) {
				case 'ACCOUNT':
					$row = array(
						'log_GUID'				=>	$parameter['account_guid'],
						'log_account_name'		=>	$parameter['account_name'],
						'log_account_email'		=>	'',
						'log_action'			=>	$logAction,
						'log_uri'				=>	$uri,
						'log_method'			=>	$relativeMethod,
						'log_parameter'			=>	$relativeParameter,
						'log_time'				=>	$currentTime,
						'log_time_local'		=>	$currentTimeLocal,
						'game_id'				=>	empty($parameter['game_id']) ? '' : $parameter['game_id'],
						'section_id'				=>	empty($parameter['section_id']) ? '' : $parameter['section_id'],
						'server_id'				=>	empty($parameter['server_id']) ? '' : $parameter['server_id']
					);
					$this->logdb->insert($this->accountTable, $row);
					break;
			}
		}
	}
}
?>