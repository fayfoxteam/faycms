<?php
use fay\helpers\HtmlHelper;
use ncp\helpers\FriendlyLink;
if($listview->total_pages > 1){

isset($params) || $params = array();
?>
<div class="page1">
	<?php
	echo HtmlHelper::link('首页', FriendlyLink::getLink($type, array(
		'page'=>1
	) + $params), array(
		'class'=>'pg1',
		'title'=>'首页',
		'encode'=>false,
	));
	//上一页
	if($listview->current_page == 2){
		echo HtmlHelper::link('上一页', FriendlyLink::getLink($type, array(
			'page'=>1
		) + $params), array(
			'class'=>'prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->current_page > 2){
		echo HtmlHelper::link('上页', FriendlyLink::getLink($type, array(
			'page'=>$listview->current_page - 1
		) + $params), array(
			'class'=>'prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}
	
	//首页
	if($listview->current_page > ($listview->adjacents + 1)) {
		echo HtmlHelper::link(1, FriendlyLink::getLink($type, array(
			'page'=>1
		) + $params), array(
			'class'=>'num',
		));
	}
	
	//点点点
	if($listview->current_page > ($listview->adjacents + 2)) {
		echo '<span class="num dots">&hellip;</span>';
	}
	
	//页码
	$pmin = $listview->current_page > $listview->adjacents ? $listview->current_page - $listview->adjacents : 1;
	$pmax = $listview->current_page < $listview->total_pages - $listview->adjacents ? $listview->current_page + $listview->adjacents : $listview->total_pages;
	for($i=$pmin; $i<=$pmax; $i++){
		if($i == $listview->current_page){
			echo '<span class="num1">', $i, '</span>';
		}else if($i == 1){
			echo HtmlHelper::link(1, FriendlyLink::getLink($type, array(
				'page'=>1
			) + $params), array(
				'class'=>'num',
			));
		}else{
			echo HtmlHelper::link($i, FriendlyLink::getLink($type, array(
				'page'=>$i
			) + $params), array(
				'class'=>'num',
			));
		}
	}
	
	//点点点
	// interval
	if($listview->current_page < ($listview->total_pages - $listview->adjacents - 1)) {
		echo '<span class="num dots">&hellip;</span>';
	}
	
	//尾页
	if($listview->current_page < $listview->total_pages - $listview->adjacents) {
		echo HtmlHelper::link($listview->total_pages, FriendlyLink::getLink($type, array(
			'page'=>$listview->total_pages
		) + $params), array(
			'class'=>'num',
		));
	}
	
	//下一页
	if($listview->current_page < $listview->total_pages){
		echo HtmlHelper::link('下一页', FriendlyLink::getLink($type, array(
			'page'=>$listview->current_page + 1
		) + $params), array(
			'class'=>'next',
			'title'=>'下一页',
			'encode'=>false,
		));
	}
	echo HtmlHelper::link('尾页', FriendlyLink::getLink($type, array(
		'page'=>$listview->total_pages
	) + $params), array(
		'class'=>'pg1',
		'title'=>'尾页',
		'encode'=>false,
	));
	?>
</div>
<?php }?>