<?php

if($listview->id){
	$page_param = $listview->id.'_page';
}else{
	$page_param = 'page';
}
echo '<div class="pager"><ul>';
echo "<li class=\"summary\">共&nbsp;{$listview->totalRecords}&nbsp;条记录，当前第&nbsp;{$listview->startRecord}&nbsp;到&nbsp;{$listview->endRecord}&nbsp;条</li>";
// previous
if($listview->currentPage == 1) {
	echo "<li><a class=\"prev disabled\" href=\"javascript:;\">«</a></li>";
}elseif($listview->currentPage==2) {
	echo "<li><a class=\"prev\" href=\"".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>1)))."\" title=\"上一页\">«</a></li>";
}else {
	echo "<li><a class=\"prev\" href=\"".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>$listview->currentPage - 1)))."\" title=\"上一页\">«</a></li>";
}

// first
if($listview->currentPage > ($listview->adjacents+1)) {
	echo "<li><a href=\"".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>1)))."\">1</a></li>";
}

// interval
if($listview->currentPage > ($listview->adjacents+2)) {
	echo "<li><a href='javascript:;'>...</a></li>";
}

// pages
$pmin = $listview->currentPage > $listview->adjacents ? $listview->currentPage - $listview->adjacents : 1;
$pmax = $listview->currentPage < $listview->totalPages - $listview->adjacents ? $listview->currentPage + $listview->adjacents : $listview->totalPages;
for($i=$pmin; $i<=$pmax; $i++){
	if($i == $listview->currentPage){
		echo "<li><a href=\"javascript:;\" class=\"page action\">{$i}</a></li>";
	}else if($i == 1) {
		echo "<li><a href='".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>false)))."'>1</a></li>";
	}else{
		echo "<li><a href=\"".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>$i)))."\" class=\"page\">{$i}</a></li>";
	}
}

// interval
if($listview->currentPage<($listview->totalPages-$listview->adjacents-1)) {
	echo "<li><a href='javascript:;'>...</a></li>";
}

// last
if($listview->currentPage < $listview->totalPages - $listview->adjacents) {
	echo "<li><a href=\"".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>$listview->totalPages)))."\">{$listview->totalPages}</a></li>";
}

// next
if($listview->currentPage < $listview->totalPages) {
	echo "<li><a class=\"next\" href=\"".F::app()->view->url(F::app()->uri->router, array_merge(F::app()->input->get(), array($page_param=>$listview->currentPage + 1)))."\" title=\"下一页\">»</a></li>";
}else{
	echo "<li><a class=\"next disabled\" href=\"javascript:;\">»</a></li>";
}

echo '</ul><div class="clear"></div></div>';