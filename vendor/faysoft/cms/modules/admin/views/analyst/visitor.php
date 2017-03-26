<?php
use fay\helpers\HtmlHelper;

$cols = F::form('setting')->getData('cols', array());
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				<?php echo F::form('search')->select('se', array(
					''=>'--搜索引擎--',
					'360' =>'360',
					'baidu' =>'百度',	
					'soso' =>'搜搜',
					'google'=>'谷歌',
				), array(
					'class'=>'form-control',
				))?>
				|
				TrackId
				<?php echo F::form('search')->inputText('trackid', array(
					'class'=>'form-control',
				));?>
				|
				IP
				<?php echo F::form('search')->inputText('ip', array(
					'class'=>'form-control',
				))?>
				|
				<?php echo F::form('search')->select('site', array(''=>'--所有站点--')+HtmlHelper::getSelectOptions($sites, 'id', 'title'), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="mb5">
				访问时间
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
					if(in_array('se', $cols)){
						echo '<th>搜索引擎</th>';
					}
					if(in_array('keywords', $cols)){
						echo '<th>关键词</th>';
					}
					if(in_array('browser', $cols)){
						echo '<th>浏览器内核</th>';
					}
					if(in_array('browser_version', $cols)){
						echo '<th>内核版本</th>';
					}
					if(in_array('shell', $cols)){
						echo '<th>浏览器套壳</th>';
					}
					if(in_array('shell_version', $cols)){
						echo '<th>套壳版本</th>';
					}
					if(in_array('os', $cols)){
						echo '<th>操作系统</th>';
					}
					if(in_array('ua', $cols)){
						echo '<th>UA</th>';
					}
					if(in_array('screen', $cols)){
						echo '<th>屏幕大小</th>';
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
					if(in_array('se', $cols)){
						echo '<th>搜索引擎</th>';
					}
					if(in_array('keywords', $cols)){
						echo '<th>关键词</th>';
					}
					if(in_array('browser', $cols)){
						echo '<th>浏览器内核</th>';
					}
					if(in_array('browser_version', $cols)){
						echo '<th>内核版本</th>';
					}
					if(in_array('shell', $cols)){
						echo '<th>浏览器套壳</th>';
					}
					if(in_array('shell_version', $cols)){
						echo '<th>套壳版本</th>';
					}
					if(in_array('os', $cols)){
						echo '<th>操作系统</th>';
					}
					if(in_array('ua', $cols)){
						echo '<th>UA</th>';
					}
					if(in_array('screen', $cols)){
						echo '<th>屏幕大小</th>';
					}
				?></tr>
			</tfoot>
			<tbody>
			<?php $listview->showData(array(
				'cols'=>$cols,
				'iplocation'=>$iplocation,
			));?>
			</tbody>
		</table>
		<?php $listview->showPager();?>
	</div>
</div>