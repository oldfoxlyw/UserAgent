<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servers extends CI_Controller {
	private $root_path = null;
	
	public function __construct() {
		parent::__construct();
		$this->root_path = $this->config->item('root_path');
		$this->load->model('logs');
		$this->load->model('return_format');
	}
	
	public function server_list($format = 'json') {
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$sectionId	=	$this->input->get_post('server_section', TRUE);
		$mode		=	$this->input->get_post('mode', TRUE);
		$lang		=	$this->input->get_post('language', TRUE);
		
		$authKey	=	$this->config->item('game_auth_key');
		$authToken	=	$authKey[$gameId]['auth_key'];
		
		if($sectionId===FALSE || empty($sectionId)) {
			$sectionId	=	$this->config->item('game_section_id');
		}
		if($mode===FALSE || empty($mode)) {
			$mode = 'normal';
		}
		
		switch($lang) {
			case 'CN':
				$lang = 'zh-cn';
				break;
			case 'EN':
				$lang = 'english';
				break;
			default:
				$lang = 'zh-cn';
		}
		
		if(!empty($gameId)) {
			if($this->config->item('game_server_cache')) {
				//使用缓存
				$cache = $this->config->item('game_server_list');
				if(empty($cache[$gameId]['section'][$sectionId])) {
					
				}
				$result = array();
				foreach($cache[$gameId]['section'][$sectionId]['server'] as $key => $value) {
					$row = array(
						'account_server_id'	=>	$key,
						'server_name'		=>	$value['server_name'],
						'server_ip'			=>	$value['server_ip'] . ':' . $value['server_port'],
						'server_max_player'	=>	$value['server_max_player'],
						'account_count'		=>	$value['account_count'],
						'server_language'	=>	$value['server_language'],
						'server_recommend'	=>	$value['server_recommend']
					);
					array_push($result, $row);
				}
			} else {
				//不使用缓存
				$this->load->model('websrv/server', 'server');
				$parameter = array(
					'use_cache_style'			=>	true,
					'game_id'					=>	$gameId,
					'server_mode'			=>	$mode,
					'order_by'					=>	'server_sort'
					//'account_server_section'	=>	$sectionId
				);
				$result = $this->server->getAllResult($parameter);
			}
			$this->lang->load('server_list', $lang);
			$this->load->helper('language');
			foreach($result as $value) {
				$serverName = lang('server_list_' . $value->server_name);
				if(!empty($serverName)) {
					$value->server_name = $serverName;
				}
				$value->server_language = lang('server_list_language_' . $value->server_language);
			}
			
			$jsonData = Array(
				'message'	=>	'SERVER_LIST_SUCCESS',
				'server'	=>	$result
			);
			echo $this->return_format->format($jsonData, $format);
		} else {
			$jsonData = Array(
				'message'	=>	'SERVER_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
	
	public function server_list_new($format = 'json') {
		$gameId		=	$this->input->get_post('game_id', TRUE);
		$sectionId	=	$this->input->get_post('server_section', TRUE);
		$mode		=	$this->input->get_post('mode', TRUE);
		$lang		=	$this->input->get_post('language', TRUE);
	
		$authKey	=	$this->config->item('game_auth_key');
		$authToken	=	$authKey[$gameId]['auth_key'];
	
		if($sectionId===FALSE || empty($sectionId)) {
			$sectionId	=	$this->config->item('game_section_id');
		}
		if($mode===FALSE || empty($mode)) {
			$mode = 'normal';
		}
	
		switch($lang) {
			case 'CN':
				$lang = 'zh-cn';
				break;
			case 'EN':
				$lang = 'english';
				break;
			default:
				$lang = 'zh-cn';
		}
	
		if(!empty($gameId)) {
			//不使用缓存
			$this->load->model('websrv/server_new', 'server_new');
			$parameter = array(
					'use_cache_style'			=>	true,
					'game_id'					=>	$gameId,
					'server_mode'			=>	$mode,
					'order_by'					=>	'server_sort'
					//'account_server_section'	=>	$sectionId
			);
			$result = $this->server_new->getAllResult($parameter);
			
			$this->lang->load('server_list', $lang);
			$this->load->helper('language');
			foreach($result as $value) {
				$serverName = lang('server_list_' . $value->server_name);
				if(!empty($serverName)) {
					$value->server_name = $serverName;
				}
				$value->server_language = lang('server_list_language_' . $value->server_language);
			}
				
			$jsonData = Array(
					'message'	=>	'SERVER_LIST_SUCCESS',
					'server'	=>	$result
			);
			echo $this->return_format->format($jsonData, $format);
		} else {
			$jsonData = Array(
					'message'	=>	'SERVER_ERROR_NO_PARAM'
			);
			echo $this->return_format->format($jsonData, $format);
		}
	}
}
?>
