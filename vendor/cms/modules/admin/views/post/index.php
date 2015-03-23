<?php
use fay\helpers\Html;
use fay\models\Post;
use fay\models\tables\Posts;
use cms\helpers\ListTableHelper;

$cols = F::form('setting')->getData('cols', array());
?>
<div class="col-1">
	<?php echo F::form('search')->open(null, 'get')?>
		<div class="mb5">
		文章标题：<?php echo F::form('search')->inputText('title', array(
			'class'=>'w200',
		));?>
		|
		<?php echo F::form('search')->select('cat_id', array(
			''=>'--分类--',
		) + Html::getSelectOptions($cats, 'id', 'title'))?>
		<?php if(in_array('category', $enabled_boxes)){
			echo F::form('search')->inputCheckbox('with_slave', 1, array(
				'label'=>'附加分类',
				'title'=>'同时搜索文章主分类和附加分类',
			));
		}?>
		<?php echo F::form('search')->inputCheckbox('with_child', 1, array(
			'label'=>'子分类',
			'title'=>'符合所选分类子分类的文章也将被搜出',
		))?>
		</div>
		<div>
		<?php echo F::form('search')->select('time_field', array(
			'publish_time'=>'发布时间',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
		));?>
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
	<?php echo F::form('search')->close()?>
	<ul class="subsubsub fl">
		<li class="all <?php if(F::app()->input->get('status') === null && F::app()->input->get('deleted') === null)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/post/index')?>">全部</a>
			<span class="color-grey">(<?php echo Post::model()->getCount()?>)</span>
			|
		</li>
		<li class="publish <?php if(F::app()->input->get('status') == Posts::STATUS_PUBLISH && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/post/index', array('status'=>Posts::STATUS_PUBLISH))?>">已发布</a>
			<span class="color-grey">(<?php echo Post::model()->getCount(Posts::STATUS_PUBLISH)?>)</span>
			|
		</li>
		<li class="publish <?php if(F::app()->input->get('status') == Posts::STATUS_PENDING && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/post/index', array('status'=>Posts::STATUS_PENDING))?>">待审核</a>
			<span class="color-grey">(<?php echo Post::model()->getCount(Posts::STATUS_PENDING)?>)</span>
			|
		</li>
		<li class="draft <?php if(F::app()->input->get('status', 'intval') === Posts::STATUS_DRAFT && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/post/index', array('status'=>Posts::STATUS_DRAFT))?>">草稿</a>
			<span class="color-grey">(<?php echo Post::model()->getCount(Posts::STATUS_DRAFT)?>)</span>
			|
		</li>
		<li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/post/index', array('deleted'=>1))?>">回收站</a>
			<span class="color-grey">(<?php echo Post::model()->getDeletedCount()?>)</span>
		</li>
	</ul>
	<br class="clear" />
	<form method="post" action="<?php echo $this->url('admin/post/batch')?>" id="batch-form">
		<div class="fl mt5"><?php
			if(F::app()->input->get('deleted')){
				echo Html::select('batch_action', array(
					''=>'批量操作',
					'undelete'=>F::app()->checkPermission('admin/post/undelete') ? '还原' : false,
					'remove'=>F::app()->checkPermission('admin/post/remove') ? '永久删除' : false,
				));
			}else{
				echo Html::select('batch_action', array(
					''=>'批量操作',
					'set-publish'=>F::app()->checkPermission('admin/post/edit') ? '标记为已发布' : false,
					'set-draft'=>F::app()->checkPermission('admin/post/edit') ? '标记为草稿' : false,
					'delete'=>F::app()->checkPermission('admin/post/delete') ? '删除' : false,
					'review'=>(F::app()->checkPermission('admin/post/review') && F::app()->post_review) ? '通过审核' : false,
					'pending'=>(F::app()->checkPermission('admin/post/edit') && F::app()->post_review) ? '待审核' : false,
				));
			}
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPager();?>
		<br class="clear" />
		<table class="list-table">
			<thead>
				<tr>
					<th class="w20"><input type="checkbox" class="batch-ids-all" /></th>
					<th>标题</th>
					<?php if(in_array('main_category', $cols)){?>
					<th>主分类</th>
					<?php }?>
					<?php if(in_array('category', $cols)){?>
					<th>附加分类</th>
					<?php }?>
					<?php if(in_array('tags', $cols)){?>
					<th>标签</th>
					<?php }?>
					<?php if(in_array('status', $cols)){?>
					<th class="w70">状态</th>
					<?php }?>
					<?php if(in_array('user', $cols)){?>
					<th>作者</th>
					<?php }?>
					<?php if(in_array('views', $cols)){?>
					<th class="w70"><?php echo ListTableHelper::getSortLink('views', '阅读数')?></th>
					<?php }?>
					<?php if(in_array('comments', $cols)){?>
					<th class="w70"><?php echo ListTableHelper::getSortLink('comments', '评论数')?></th>
					<?php }?>
					<?php if(in_array('publish_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('publish_time', '发布时间')?></th>
					<?php }?>
					<?php if(in_array('last_view_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('last_view_time', '最后访问时间')?></th>
					<?php }?>
					<?php if(in_array('last_modified_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
					<?php }?>
					<?php if(in_array('create_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
					<?php }?>
					<?php if(in_array('sort', $cols)){?>
					<th class="w70"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
					<?php }?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><input type="checkbox" class="batch-ids-all" /></th>
					<th>标题</th>
					<?php if(in_array('main_category', $cols)){?>
					<th>主分类</th>
					<?php }?>
					<?php if(in_array('category', $cols)){?>
					<th>附加分类</th>
					<?php }?>
					<?php if(in_array('tags', $cols)){?>
					<th>标签</th>
					<?php }?>
					<?php if(in_array('status', $cols)){?>
					<th>状态</th>
					<?php }?>
					<?php if(in_array('user', $cols)){?>
					<th>作者</th>
					<?php }?>
					<?php if(in_array('views', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('views', '阅读数')?></th>
					<?php }?>
					<?php if(in_array('comments', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('comments', '评论数')?></th>
					<?php }?>
					<?php if(in_array('publish_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('publish_time', '发布时间')?></th>
					<?php }?>
					<?php if(in_array('last_view_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('last_view_time', '最后访问时间')?></th>
					<?php }?>
					<?php if(in_array('last_modified_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
					<?php }?>
					<?php if(in_array('create_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
					<?php }?>
					<?php if(in_array('sort', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
					<?php }?>
				</tr>
			</tfoot>
			<tbody><?php $listview->showData(array(
				'cols'=>$cols,
			));?></tbody>
		</table>
		<div class="fl mt5"><?php
			if(F::app()->input->get('deleted')){
				echo Html::select('batch_action_2', array(
					''=>'批量操作',
					'undelete'=>F::app()->checkPermission('admin/post/undelete') ? '还原' : false,
					'remove'=>F::app()->checkPermission('admin/post/remove') ? '永久删除' : false,
				));
			}else{
				echo Html::select('batch_action_2', array(
					''=>'批量操作',
					'set-publish'=>F::app()->checkPermission('admin/post/edit') ? '标记为已发布' : false,
					'set-draft'=>F::app()->checkPermission('admin/post/edit') ? '标记为草稿' : false,
					'delete'=>F::app()->checkPermission('admin/post/delete') ? '删除' : false,
					'review'=>(F::app()->checkPermission('admin/post/review') && F::app()->post_review) ? '通过审核' : false,
					'pending'=>(F::app()->checkPermission('admin/post/edit') && F::app()->post_review) ? '待审核' : false,
				));
			}
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit-2',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPager();?>
	</form>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$(".post-sort").feditsort({
		'url':system.url("admin/post/sort")
	});
});
</script>