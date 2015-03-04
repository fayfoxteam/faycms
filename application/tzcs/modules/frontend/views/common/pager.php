<?php
use fay\helpers\Html;
if($listview->totalPages > 1){
?>
<ul class="paginator">
<li>
	<?php 
	//上一页
	if($listview->currentPage == 2){
		echo Html::link('上一页', $listview->reload, array(
			'class'=>'inb',
			'id'   => 'prevNo',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->currentPage > 2){
		echo Html::link('上一页', $listview->reload . '?page=' . ($listview->currentPage - 1), array(
			'class'=>'inb',
			'id'   => 'prevNo',
			'title'=>'上一页',
			'encode'=>false,
		));
	}
	echo '</li><li>';

	//首页
	if($listview->currentPage > ($listview->adjacents + 1)) {
		echo Html::link(1, $listview->reload, array(
			'class'=>'page-numbers',
		));
	}

	//点点点
	if($listview->currentPage > ($listview->adjacents + 2)) {
		echo '<span class="page-numbers">&hellip;</span>';
	}
	
	//页码
	$pmin = $listview->currentPage > $listview->adjacents ? $listview->currentPage - $listview->adjacents : 1;
	$pmax = $listview->currentPage < $listview->totalPages - $listview->adjacents ? $listview->currentPage + $listview->adjacents : $listview->totalPages;
	for($i=$pmin; $i<=$pmax; $i++){
		if($i == $listview->currentPage){
			echo '<span class="current">', $i, '</span>';
		}else if($i == 1){
			echo Html::link(1, $listview->reload, array(
				'class'=>'',
			));
		}else{
			echo Html::link($i, $listview->reload . '?page='.$i, array(
				'class'=>'',
			));
		}
	}

	//点点点
	// interval
	if($listview->currentPage < ($listview->totalPages - $listview->adjacents - 1)) {
		echo '<span class="page-numbers">&hellip;</span>';
	}
	echo '</li><li>';
	//末页
	if($listview->currentPage < $listview->totalPages - $listview->adjacents) {
		echo Html::link($listview->totalPages, $listview->reload . '?page=' . $listview->totalPages, array(
			'class'=>'page-numbers',
		));
	}
	echo '</li><li>';
	//下一页
	if($listview->currentPage < $listview->totalPages){
		echo Html::link('下一页', $listview->reload . '?page=' . ($listview->currentPage + 1), array(
			'class'=>'',
			'id'   =>'next',
			'title'=>'下一页',
			'encode'=>false,
		));
	}
	?>
</li>
<li class="fdyb">当前<?php echo $listview->currentPage;?>/<?php echo $listview->totalPages;?>页,共<?php echo $listview->totalRecords?>条记录</li>
</ul>
<?php }?>