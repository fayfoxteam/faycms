<?php
//当前页
$current_page = $listview->current_page;
//总页数
$total_pages = $listview->total_pages;
//加载地址
$reload = $listview->reload;
//前后显示页数
$adjacents = $listview->adjacents;

$prevlabel = "«";
$nextlabel = "»";

$out = "<div class='pager'><form method='get'>\n<ul>";

$current_page = $current_page<=0?1:$current_page;
$current_page = $current_page>$total_pages?$total_pages:$current_page;

// previous
if($current_page==1) {
	$out.= '<li><a class="prev disabled" href="javascript:;">«</a></li>';
}
elseif($current_page==2) {
	$out.= "<li><a href='{$reload}'>{$prevlabel}</a></li>\n";
}else {
	$out.= "<li><a href=\"" . $reload . "?page=" . ($current_page-1) . "\">" . $prevlabel . "</a></li>\n";
}

// first
if($current_page>($adjacents+1)) {
	$out.= "<li><a href=\"" . $reload . "\">1</a></li>\n";
}

// interval
if($current_page>($adjacents+2)) {
	$out.= "<li>...</li>\n";
}

// pages
$pmin = ($current_page>$adjacents) ? ($current_page-$adjacents) : 1;
$pmax = ($current_page<($total_pages-$adjacents)) ? ($current_page+$adjacents) : $total_pages;
for($i=$pmin; $i<=$pmax; $i++) {
	if($i==$current_page) {
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
if($current_page<($total_pages-$adjacents-1)) {
	$out.= "<li>...</li>\n";
}

// last
if($current_page<($total_pages-$adjacents)) {
	$out.= "<li><a href=\"" . $reload . "?page=" . $total_pages . "\">" . $total_pages . "</a></li>\n";
}

// next
if($current_page<$total_pages) {
	$out.= "<li><a href=\"" . $reload . "?page=" . ($current_page+1) . "\">»</a></li>\n";
}
else {
	$out.= '<li><a class="next disabled" href="javascript:;">»</a></li>';
}

$out.="</ul></form>";
$out.= '<div class="clear"></div>';
$out.= '</div>';

echo $out;