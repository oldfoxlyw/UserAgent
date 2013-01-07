<?php
function getPage($currentPage, $totalPage, $parameter='') {
	if($totalPage >= 10) {
		$pageContent = "<div class=\"pagination\">";
		if($currentPage==1) {
			$pageContent .= "<a href=\"javascript:void(0)\" title=\"上一页\">&laquo; 上一页</a>";
		} else {
			$pageContent .= "<a href=\"?page=".($currentPage-1)."&{$parameter}\" title=\"上一页\">&laquo; 上一页</a>";
		}
		if($currentPage >= 1 && $currentPage <= 3 ) {
			for($i=1; $i<=5; $i++) {
				if($i==$currentPage) {
					$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number current\" title=\"{$i}\">{$i}</a>";
				} else {
					$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number\" title=\"{$i}\">{$i}</a>";
				}
			}
			$pageContent .= "...";
			$pageContent .= "<a href=\"?page={$totalPage}&{$parameter}\" class=\"number\" title=\"{$totalPage}\">{$totalPage}</a>";
		} elseif ($currentPage > 3 && $currentPage < ($totalPage-2)) {
			$pageContent .= "<a href=\"?page=1&{$parameter}\" class=\"number\" title=\"1\">1</a>";
			$pageContent .= "...";
			for($i=$currentPage-2; $i<=$currentPage+2; $i++) {
				if($i==$currentPage) {
					$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number current\" title=\"{$i}\">{$i}</a>";
				} else {
					$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number\" title=\"{$i}\">{$i}</a>";
				}
			}
			$pageContent .= "...";
			$pageContent .= "<a href=\"?page={$totalPage}&{$parameter}\" class=\"number\" title=\"{$totalPage}\">{$totalPage}</a>";
		} elseif($currentPage >= ($totalPage-2)) {
			$pageContent .= "<a href=\"?page=1&{$parameter}\" class=\"number\" title=\"1\">1</a>";
			$pageContent .= "...";
			for($i=$totalPage-4; $i<=$totalPage; $i++) {
				if($i==$currentPage) {
					$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number current\" title=\"{$i}\">{$i}</a>";
				} else {
					$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number\" title=\"{$i}\">{$i}</a>";
				}
			}
		}
		if($currentPage==$totalPage) {
			$pageContent .= "<a href=\"javascript:void(0)\" title=\"下一页\">下一页 &raquo;</a>";
		} else {
			$pageContent .= "<a href=\"?page=".($currentPage+1)."&{$parameter}\" title=\"下一页\">下一页 &raquo;</a>";
		}
		$pageContent .= "</div>\n";
		return $pageContent;
	} elseif ($totalPage > 0) {
		$pageContent = "<div class=\"pagination\">";
		if($currentPage==1) {
			$pageContent .= "<a href=\"javascript:void(0)\" title=\"上一页\">&laquo; 上一页</a>";
		} else {
			$pageContent .= "<a href=\"?page=".($currentPage-1)."&{$parameter}\" title=\"上一页\">&laquo; 上一页</a>";
		}
		for($i=1; $i<=$totalPage; $i++) {
			if($i==$currentPage) {
				$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number current\" title=\"{$i}\">{$i}</a>";
			} else {
				$pageContent .= "<a href=\"?page={$i}&{$parameter}\" class=\"number\" title=\"{$i}\">{$i}</a>";
			}
		}
		if($currentPage==$totalPage) {
			$pageContent .= "<a href=\"javascript:void(0)\" title=\"下一页\">下一页 &raquo;</a>";
		} else {
			$pageContent .= "<a href=\"?page=".($currentPage+1)."&{$parameter}\" title=\"下一页\">下一页 &raquo;</a>";
		}
		$pageContent .= "</div>\n";
		
		return $pageContent;
	}
}
?>