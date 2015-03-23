<?php
use fay\helpers\Html;
?>
<div class="col-1">
	<form method="get" id="search-form">
		<div class="mb5">
			试卷名称
			<?php echo F::form('search')->inputText('keywords', array('class'=>'w200'))?>
			|
			<?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+Html::getSelectOptions($cats));?>
		</div>
		<div class="mb5">
			创建时间
			<?php echo F::form('search')->inputText('start_time', array(
				'data-rule'=>'datetime',
				'data-label'=>'时间',
				'class'=>'datetimepicker',
			));?>
			-
			<?php echo F::form('search')->inputText('end_time', array(
				'data-rule'=>'datetime',
				'data-label'=>'时间',
				'class'=>'datetimepicker',
			));?>
			<a href="javascript:;" class="btn-3" id="search-form-submit">查询</a>
		</div>
	</form>
	<?php $listview->showPager();?>
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr>
				<th>试卷名称</th>
				<th>分类</th>
				<th>状态</th>
				<th>分值</th>
				<th class="wp15">创建时间</th>
				<th class="wp15">最后修改时间</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>试卷名称</th>
				<th>分类</th>
				<th>状态</th>
				<th>分值</th>
				<th>创建时间</th>
				<th>最后修改时间</th>
			</tr>
		</tfoot>
		<tbody>
	<?php
		$listview->showData();
	?>
		</tbody>
	</table>
	<?php $listview->showPager();?>
</div>