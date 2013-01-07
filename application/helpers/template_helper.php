<?php
function parseTemplate($templateContent, $parser) {
	foreach($parser as $key=>$value) {
		$templateContent = str_replace("{%$key%}", $value, $templateContent);
	}
	return $templateContent;
}
?>