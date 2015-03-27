<?php
use fay\helpers\Html;
?>
<div class="pager">
<?php
if($listview->totalPages > 1){
	if($listview->id){
		$page_param = $listview->id.'_page';
	}else{
		$page_param = 'page';
	}
	
	$gets = F::app()->input->get();
	unset($gets[$page_param]);
?>
	<span class="summary"><?php echo $listview->totalRecords?>条记录</span>
	<?php
	echo Html::link('&laquo;', array(F::app()->uri->router, $gets), array(
		'class'=>'page-numbers first'.($listview->currentPage == 1 ? ' disabled' : ''),
		'title'=>'首页',
		'encode'=>false,
	));
	//上一页
	if($listview->currentPage == 1){
		echo Html::link('&lsaquo;', array(F::app()->uri->router, $gets), array(
			'class'=>'page-numbers prev disabled',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->currentPage == 2){
		echo Html::link('&lsaquo;', array(F::app()->uri->router, $gets), array(
			'class'=>'page-numbers prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->currentPage > 2){
		echo Html::link('&lsaquo;', array(F::app()->uri->router, $gets + array(
			$page_param=>$listview->currentPage - 1,
		)), array(
			'class'=>'page-numbers prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}
	
	echo Html::inputNumber($page_param, $listview->currentPage, array(
		'class'=>'form-control pager-input',
		'before'=>' 第 ',
		'after'=>' 页，共'.$listview->totalPages.'页 ',
		'min'=>1,
		'max'=>$listview->totalPages,
	));
	
	//下一页
	echo Html::link('&rsaquo;', array(F::app()->uri->router, $gets + array(
		$page_param=>$listview->currentPage == $listview->totalPages ? $listview->currentPage : $listview->currentPage + 1,
	)), array(
		'class'=>'page-numbers next'.($listview->currentPage == $listview->totalPages ? ' disabled' : ''),
		'title'=>'下一页',
		'encode'=>false,
	));
	echo Html::link('&raquo;', array(F::app()->uri->router, $gets + array(
		$page_param=>$listview->totalPages,
	)), array(
		'class'=>'page-numbers end'.($listview->currentPage == $listview->totalPages ? ' disabled' : ''),
		'title'=>'末页',
		'encode'=>false,
	));
	
	?>
<?php }else{?>
	<span class="summary"><?php echo $listview->totalRecords?>条记录</span>
<?php }?>
</div>