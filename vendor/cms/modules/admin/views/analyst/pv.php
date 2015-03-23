<?php
use fay\helpers\Html;
use cms\helpers\ListTableHelper;
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
	<ul class="subsubsub fl">
		<li class="<?php if($flag === 'today')echo 'sel';?>">
			<a href="<?php echo $this->url('admin/analyst/pv', array(
				'start_time'=>date('Y-m-d 00:00:00', $today),
				'end_time'=>'',
				'site'=>F::app()->input->get('site'),
				'trackid'=>F::app()->input->get('trackid'),
				'ip'=>F::app()->input->get('ip'),
			))?>">今天</a>
			|
		</li>
		<li class="<?php if($flag === 'yesterday')echo 'sel';?>">
			<a href="<?php echo $this->url('admin/analyst/pv', array(
				'start_time'=>date('Y-m-d 00:00:00', $yesterday),
				'end_time'=>date('Y-m-d 00:00:00', $today),
				'site'=>F::app()->input->get('site'),
				'trackid'=>F::app()->input->get('trackid'),
				'ip'=>F::app()->input->get('ip'),
			))?>">昨天</a>
			|
		</li>
		<li class="<?php if($flag === 'week')echo 'sel';?>">
			<a href="<?php echo $this->url('admin/analyst/pv', array(
				'start_time'=>date('Y-m-d 00:00:00', $week),
				'end_time'=>'',
				'site'=>F::app()->input->get('site'),
				'trackid'=>F::app()->input->get('trackid'),
				'ip'=>F::app()->input->get('ip'),
			))?>">最近7天</a>
			|
		</li>
		<li class="<?php if($flag === 'month')echo 'sel';?>">
			<a href="<?php echo $this->url('admin/analyst/pv', array(
				'start_time'=>date('Y-m-d 00:00:00', $month),
				'end_time'=>'',
				'site'=>F::app()->input->get('site'),
				'trackid'=>F::app()->input->get('trackid'),
				'ip'=>F::app()->input->get('ip'),
			))?>">最近30天</a>
		</li>
	</ul>
	<?php $listview->showPager();?>
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr> 
				<th>受访页面</th>
				<th class="w100"><?php echo ListTableHelper::getSortLink('pv', '浏览量(PV)')?></th>
				<th class="w100"><?php echo ListTableHelper::getSortLink('uv', '访客数(UV)')?></th>
				<th class="w35">IP数</th>
				<th class="w100">站点</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>受访页面</th>
				<th><?php echo ListTableHelper::getSortLink('pv', '浏览量(PV)')?></th>
				<th><?php echo ListTableHelper::getSortLink('uv', '访客数(UV)')?></th>
				<th>IP数</th>
				<th>站点</th>
			</tr>
		</tfoot>
		<tbody>
		<?php $listview->showData();?>
		</tbody>
	</table>
	<?php $listview->showPager();?>
</div>