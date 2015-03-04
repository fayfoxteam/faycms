<?php
use fay\helpers\Html;
if($listview->totalPages > 1){
?>
<div class="pagination">
	<span class="summary">共 <?php echo $listview->totalPages?> 页 / <?php echo $listview->totalRecords?> 个结果</span>
	<?php
	echo Html::link('首页', $listview->reload, array(
		'class'=>'page-numbers first',
		'title'=>'首页',
		'encode'=>false,
	));
	//上一页
	if($listview->currentPage == 2){
		echo Html::link('上页', $listview->reload, array(
			'class'=>'page-numbers prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->currentPage > 2){
		echo Html::link('上页', $listview->reload . '?page=' . ($listview->currentPage - 1), array(
			'class'=>'page-numbers prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}
	
	//首页
	if($listview->currentPage > ($listview->adjacents + 1)) {
		echo Html::link(1, $listview->reload, array(
			'class'=>'page-numbers',
		));
	}
	
	//点点点
	if($listview->currentPage > ($listview->adjacents + 2)) {
		echo '<span class="page-numbers dots">&hellip;</span>';
	}
	
	//页码
	$pmin = $listview->currentPage > $listview->adjacents ? $listview->currentPage - $listview->adjacents : 1;
	$pmax = $listview->currentPage < $listview->totalPages - $listview->adjacents ? $listview->currentPage + $listview->adjacents : $listview->totalPages;
	for($i=$pmin; $i<=$pmax; $i++){
		if($i == $listview->currentPage){
			echo '<span class="page-numbers crt">', $i, '</span>';
		}else if($i == 1){
			echo Html::link(1, $listview->reload, array(
				'class'=>'page-numbers',
			));
		}else{
			echo Html::link($i, $listview->reload . '?page='.$i, array(
				'class'=>'page-numbers',
			));
		}
	}
	
	//点点点
	// interval
	if($listview->currentPage < ($listview->totalPages - $listview->adjacents - 1)) {
		echo '<span class="page-numbers dots">&hellip;</span>';
	}
	
	//末页
	if($listview->currentPage < $listview->totalPages - $listview->adjacents) {
		echo Html::link($listview->totalPages, $listview->reload . '?page=' . $listview->totalPages, array(
			'class'=>'page-numbers',
		));
	}
	
	//下一页
	if($listview->currentPage < $listview->totalPages){
		echo Html::link('下页', $listview->reload . '?page=' . ($listview->currentPage + 1), array(
			'class'=>'page-numbers next',
			'title'=>'下一页',
			'encode'=>false,
		));
	}
	echo Html::link('末页', $listview->reload . '?page=' . $listview->totalPages, array(
		'class'=>'page-numbers end',
		'title'=>'末页',
		'encode'=>false,
	));
	?>
</div>
<?php }?>