<?php
//当前页
$currentPage = $listview->currentPage;
//总页数
$totalPages = $listview->totalPages;
//加载地址
$reload = $listview->reload;
//前后显示页数
$adjacents = $listview->adjacents;

$prevlabel = "«";
$nextlabel = "»";

$out = "<div class='pager'><form method='get'>\n<ul>";

$currentPage = $currentPage<=0?1:$currentPage;
$currentPage = $currentPage>$totalPages?$totalPages:$currentPage;

// previous
if($currentPage==1) {
	$out.= '<li><a class="prev disabled" href="javascript:;">«</a></li>';
}
elseif($currentPage==2) {
	$out.= "<li><a href='{$reload}'>{$prevlabel}</a></li>\n";
}else {
	$out.= "<li><a href=\"" . $reload . "?page=" . ($currentPage-1) . "\">" . $prevlabel . "</a></li>\n";
}

// first
if($currentPage>($adjacents+1)) {
	$out.= "<li><a href=\"" . $reload . "\">1</a></li>\n";
}

// interval
if($currentPage>($adjacents+2)) {
	$out.= "<li>...</li>\n";
}

// pages
$pmin = ($currentPage>$adjacents) ? ($currentPage-$adjacents) : 1;
$pmax = ($currentPage<($totalPages-$adjacents)) ? ($currentPage+$adjacents) : $totalPages;
for($i=$pmin; $i<=$pmax; $i++) {
	if($i==$currentPage) {
		$out.= "<li><a class='action' href=''> ". $i . "</a></li>\n";
	}
	elseif($i==1) {
		$out.= "<li><a href=\"" . $reload . "\">" . $i . "</a></li>\n";
	}
	else {
		$out.= "<li><a href=\"" . $reload . "?page=" . $i . "\">" . $i . "</a></li>\n";
	}
}

// interval
if($currentPage<($totalPages-$adjacents-1)) {
	$out.= "<li>...</li>\n";
}

// last
if($currentPage<($totalPages-$adjacents)) {
	$out.= "<li><a href=\"" . $reload . "?page=" . $totalPages . "\">" . $totalPages . "</a></li>\n";
}

// next
if($currentPage<$totalPages) {
	$out.= "<li><a href=\"" . $reload . "?page=" . ($currentPage+1) . "\">»</a></li>\n";
}
else {
	$out.= '<li><a class="next disabled" href="javascript:;">»</a></li>';
}

$out.="</ul></form>";
$out.= '<div class="clear"></div>';
$out.= '</div>';

echo $out;