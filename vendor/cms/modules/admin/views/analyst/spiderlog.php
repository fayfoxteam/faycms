<?php
?>
<div class="col-1">
	<form method="get" id="user-index-form">
		<div class="mb5">
			访问页面
			<?php
				echo F::form()->inputText('url', array(
					'class'=>'w200',
					'after'=>' | ',
				));
				
				$spiders = F::app()->config->get('*', 'spiders');
				asort($spiders);
				$options = array(''=>'--搜索引擎--');
				foreach($spiders as $s){
					$options[$s] = $s;
				}
				echo F::form()->select('spider', $options)
			?>
		</div>
		<div class="mb5">
			访问时间
			<?php echo F::form()->inputText('start_time', array(
				'data-rule'=>'datetime',
				'data-label'=>'时间',
				'class'=>'datetimepicker',
			));?>
			-
			<?php echo F::form()->inputText('end_time', array(
				'data-rule'=>'datetime',
				'data-label'=>'时间',
				'class'=>'datetimepicker',
			));?>
		</div>
		<div class="mb5">
			<a href="" class="btn-3" id="user-index-form-submit">查询</a>
		</div>
	</form>
	<?php $listview->showPager();?>
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr>
				<th>搜索引擎</th>
				<th>访问地址</th>
				<th>user agent</th>
				<th>来源城市</th>
				<th>访问时间</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>搜索引擎</th>
				<th>访问地址</th>
				<th>user agent</th>
				<th>来源城市</th>
				<th>访问时间</th>
			</tr>
		</tfoot>
		<tbody>
		<?php $listview->showData();?>
		</tbody>
	</table>
	<?php $listview->showPager();?>
</div>