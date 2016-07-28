<?php
use fay\helpers\Html;
if($listview->total_pages > 1){
?>
<div class="pager"><?php
	//上一页
	if($listview->current_page == 2){
		echo Html::link('', $listview->reload, array(
			'class'=>'prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->current_page > 2){
		echo Html::link('', $listview->reload . '?page=' . ($listview->current_page - 1), array(
			'class'=>'prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}
	
	//首页
	if($listview->current_page > ($listview->adjacents + 1)) {
		echo Html::link(1, $listview->reload);
	}
	
	//点点点
	if($listview->current_page > ($listview->adjacents + 2)) {
		echo '<span>&hellip;</span>';
	}
	
	//页码
	$pmin = $listview->current_page > $listview->adjacents ? $listview->current_page - $listview->adjacents : 1;
	$pmax = $listview->current_page < $listview->total_pages - $listview->adjacents ? $listview->current_page + $listview->adjacents : $listview->total_pages;
	for($i=$pmin; $i<=$pmax; $i++){
		if($i == $listview->current_page){
			echo '<span class="current">', $i, '</span>';
		}else if($i == 1){
			echo Html::link(1, $listview->reload);
		}else{
			echo Html::link($i, $listview->reload . '?page='.$i);
		}
	}
	
	//点点点
	// interval
	if($listview->current_page < ($listview->total_pages - $listview->adjacents - 1)) {
		echo '<span>&hellip;</span>';
	}
	
	//末页
	if($listview->current_page < $listview->total_pages - $listview->adjacents) {
		echo Html::link($listview->total_pages, $listview->reload . '?page=' . $listview->total_pages);
	}
	
	//下一页
	if($listview->current_page < $listview->total_pages){
		echo Html::link('', $listview->reload . '?page=' . ($listview->current_page + 1), array(
			'class'=>'next',
			'title'=>'下一页',
			'encode'=>false,
		));
	}
?></div>
<?php }?>