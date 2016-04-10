<?php
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				试题
				<?php echo F::form('search')->inputText('keywords', array(
					'class'=>'form-control w200',
				))?>
				
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
	</div>
</div>
<div class="row">
	<div class="col-12">
		<?php $listview->showPager();?>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<table class="list-table">
			<thead>
				<tr>
					<th>名称</th>
					<th>描述</th>
					<th>自从</th>
					<th>创建时间</th>
					<th>最后修改时间</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>名称</th>
					<th>描述</th>
					<th>自从</th>
					<th>创建时间</th>
					<th>最后修改时间</th>
				</tr>
			</tfoot>
			<tbody><?php $listview->showData();?></tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<?php $listview->showPager();?>
	</div>
</div>