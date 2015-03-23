<?php
use fay\helpers\Html;
use fay\models\Page;
use fay\models\tables\Pages;
use cms\helpers\ListTableHelper;
?>
<div class="col-1">
	<form method="get" class="validform" id="post-form">
		<div class="mb5">
			<?php echo F::form()->select('keyword_field', array(
				'title'=>'标题',
				'alias'=>'别名',
			));?>
			<?php echo F::form()->inputText('keywords' ,array(
				'class'=>'w200',
			));?>
			|
			<?php echo F::form()->select('cat_id', array(''=>'--分类--') + Html::getSelectOptions($cats, 'id', 'title'))?>
		</div>
		<div>
			<?php echo F::form()->select('time_field', array(
				'create_time'=>'创建时间',
				'last_modified_time'=>'最后修改时间',
			));?>
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
			<a href="javascript:;" class="btn-3" id="post-form-submit">查询</a>
		</div>
	</form>
	<ul class="subsubsub">
		<li class="all <?php if(F::app()->input->get('status') === null && F::app()->input->get('deleted') === null)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/page/index')?>">全部</a>
			<span class="color-grey">(<?php echo Page::model()->getPageCount()?>)</span>
			|
		</li>
		<li class="publish <?php if(F::app()->input->get('status') == Pages::STATUS_PUBLISH && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/page/index', array('status'=>Pages::STATUS_PUBLISH))?>">已发布</a>
			<span class="color-grey">(<?php echo Page::model()->getPageCount(Pages::STATUS_PUBLISH)?>)</span>
			|
		</li>
		<li class="draft <?php if(F::app()->input->get('status', 'intval') === Pages::STATUS_DRAFT && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/page/index', array('status'=>Pages::STATUS_DRAFT))?>">草稿</a>
			<span class="color-grey">(<?php echo Page::model()->getPageCount(Pages::STATUS_DRAFT)?>)</span>
			|
		</li>
		<li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/page/index', array('deleted'=>1))?>">回收站</a>
			<span class="color-grey">(<?php echo Page::model()->getDeletedPageCount()?>)</span>
		</li>
	</ul>
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
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
				<th class="w70"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
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
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$(".post-sort").feditsort({
		'url':system.url("admin/page/sort")
	});
});
</script>