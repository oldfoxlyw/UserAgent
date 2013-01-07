<?php
function xml_encode($array, $level = 0) {
	$return = '';
	if($level == 0) {
		$return .= '<?xml version="1.0" encoding="utf-8" ?><root>';
	}
	foreach($array as $key => $item) {
		if(!is_array($item) && !is_object($item)) {
			$return .= "<{$key}>{$item}</{$key}>";
		} else {
			$return .= "<{$key}>";
			$return .= xml_encode($item, $level + 1);
			$return .= "</{$key}>";
		}
	}
	if($level == 0) {
		$return .= '</root>';
	}
	
	return $return;
}
?>