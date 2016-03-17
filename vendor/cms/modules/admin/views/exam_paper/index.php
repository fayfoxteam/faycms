<?php
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				试卷名称
				<?php echo F::form('search')->inputText('keywords', array(
					'class'=>'form-control w200',
				))?>
				|
				<?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+Html::getSelectOptions($cats), array(
					'class'=>'form-control',
				));?>
			</div>
			<div class="mb5">
				创建时间
				<?php echo F::form('search')->inputText('start_time', array(
					'class'=>'form-control datetimepicker',
				));?>
				-
				<?php echo F::form('search')->inputText('end_time', array(
					'class'=>'form-control datetimepicker',
				));?>
				<?php echo F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
		<?php $listview->showPager();?>
		<table class="list-table">
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
</div>