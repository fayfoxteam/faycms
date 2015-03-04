<?php
use fay\helpers\Html;

$cols = F::form('setting')->getData('cols', array());
?>
<div class="col-1">
	<form method="get" id="search-form">
		<div class="mb5">
			TrackId
			<?php echo F::form('search')->inputText('trackid');?>
			|
			IP
			<?php echo F::form('search')->inputText('ip')?>
			|
			<?php echo F::form('search')->select('site', array(''=>'--所有站点--')+Html::getSelectOptions($sites, 'id', 'title'))?>
		</div>
		<div class="mb5">
			访问时间
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
	<?php $listview->showPage();?>
	<table class="list-table">
		<thead>
			<tr><?php 
				if(in_array('area', $cols)){
					echo '<th>地域</th>';
				}
				if(in_array('ip', $cols)){
					echo '<th>IP</th>';
				}
				if(in_array('url', $cols)){
					echo '<th>受访页面</th>';
				}
				if(in_array('create_time', $cols)){
					echo '<th class="w90">访问时间</th>';
				}
				if(in_array('site', $cols)){
					echo '<th>站点</th>';
				}
				if(in_array('trackid', $cols)){
					echo '<th>Trackid</th>';
				}
				if(in_array('refer', $cols)){
					echo '<th>来源</th>';
				}
				if(in_array('views', $cols)){
					echo '<th class="w70">打开次数</th>';
				}
			?></tr>
		</thead>
		<tfoot>
			<tr><?php 
				if(in_array('area', $cols)){
					echo '<th>地域</th>';
				}
				if(in_array('ip', $cols)){
					echo '<th>IP</th>';
				}
				if(in_array('url', $cols)){
					echo '<th>入口页面</th>';
				}
				if(in_array('create_time', $cols)){
					echo '<th>访问时间</th>';
				}
				if(in_array('site', $cols)){
					echo '<th>站点</th>';
				}
				if(in_array('trackid', $cols)){
					echo '<th>Trackid</th>';
				}
				if(in_array('refer', $cols)){
					echo '<th>来源</th>';
				}
				if(in_array('views', $cols)){
					echo '<th>打开次数</th>';
				}
			?></tr>
		</tfoot>
		<tbody>
		<?php $listview->showData(array(
			'cols'=>$cols,
		));?>
		</tbody>
	</table>
	<?php $listview->showPage();?>
</div>