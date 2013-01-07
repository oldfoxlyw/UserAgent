<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Return_format extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	public function format($parameter, $format = 'json') {
		if($format == 'json') {
			return json_encode($parameter);
		} elseif ($format == 'text') {
			$ret = '';
			foreach($parameter as $key=>$value) {
				if(is_array($parameter)) {
					$ret .= $this->format($parameter, $format);
				} else {
					$ret .= "&{$key}={$value}";
				}
			}
			return $ret;
		} elseif ($format == 'xml') {
			$this->load->helper('xml');
			return xml_encode($parameter);
		}
	}
}
?>