<?php
use fay\helpers\Html;
use fay\models\tables\Pages;
use cms\models\Page;
use cms\helpers\ListTableHelper;
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				<?php echo F::form('search')->select('keyword_field', array(
					'title'=>'标题',
					'alias'=>'别名',
				), array(
					'class'=>'form-control'
				));?>
				<?php echo F::form('search')->inputText('keywords' ,array(
					'class'=>'form-control w200',
				));?>
				|
				<?php echo F::form('search')->select('cat_id', array(''=>'--分类--') + Html::getSelectOptions($cats, 'id', 'title'), array(
					'class'=>'form-control'
				))?>
			</div>
			<div>
				<?php echo F::form('search')->select('time_field', array(
					'create_time'=>'创建时间',
					'last_modified_time'=>'最后修改时间',
				), array(
					'class'=>'form-control'
				));?>
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
		<ul class="subsubsub">
			<li class="all <?php if(F::app()->input->get('status') === null && F::app()->input->get('deleted') === null)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/page/index')?>">全部</a>
				<span class="fc-grey">(<?php echo Page::model()->getCount()?>)</span>
				|
			</li>
			<li class="publish <?php if(F::app()->input->get('status') == Pages::STATUS_PUBLISHED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/page/index', array('status'=>Pages::STATUS_PUBLISHED))?>">已发布</a>
				<span class="fc-grey">(<?php echo Page::model()->getCount(Pages::STATUS_PUBLISHED)?>)</span>
				|
			</li>
			<li class="draft <?php if(F::app()->input->get('status', 'intval') === Pages::STATUS_DRAFT && F::app()->input->get('deleted') != 1)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/page/index', array('status'=>Pages::STATUS_DRAFT))?>">草稿</a>
				<span class="fc-grey">(<?php echo Page::model()->getCount(Pages::STATUS_DRAFT)?>)</span>
				|
			</li>
			<li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/page/index', array('deleted'=>1))?>">回收站</a>
				<span class="fc-grey">(<?php echo Page::model()->getDeletedCount()?>)</span>
			</li>
		</ul>
		<table class="list-table">
			<thead>
				<tr>
					<th>标题</th>
					<?php if(!isset($_settings['cols']) || in_array('category', $_settings['cols'])){?>
					<th>分类</th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('status', $_settings['cols'])){?>
					<th>状态</th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('alias', $_settings['cols'])){?>
					<th>别名</th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('views', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('views', '阅读数')?></th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('last_modified_time', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('create_time', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('sort', $_settings['cols'])){?>
					<th class="w90"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
					<?php }?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>标题</th>
					<?php if(!isset($_settings['cols']) || in_array('category', $_settings['cols'])){?>
					<th>分类</th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('status', $_settings['cols'])){?>
					<th>状态</th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('alias', $_settings['cols'])){?>
					<th>别名</th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('views', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('views', '阅读数')?></th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('last_modified_time', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('create_time', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
					<?php }?>
					<?php if(!isset($_settings['cols']) || in_array('sort', $_settings['cols'])){?>
					<th><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
					<?php }?>
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
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
	$(".page-sort").feditsort({
		'url':system.url("admin/page/sort")
	});
});
</script>