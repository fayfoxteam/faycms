<?php
use fay\models\Message;
use fay\models\tables\Messages;
?>
<div class="col-1">
	<ul class="subsubsub">
		<li class="all <?php if(F::app()->input->get('status') === null && F::app()->input->get('deleted') === null)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/comment/index')?>">全部</a>
			<span class="color-grey">(<?php echo Message::model()->getCount()?>)</span>
			|
		</li>
		<li class="publish <?php if(F::app()->input->get('status', 'intval') === Messages::STATUS_PENDING && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/comment/index', array('status'=>Messages::STATUS_PENDING))?>">待审</a>
			<span class="color-grey">(<?php echo Message::model()->getCount(Messages::STATUS_PENDING)?>)</span>
			|
		</li>
		<li class="draft <?php if(F::app()->input->get('status', 'intval') === Messages::STATUS_APPROVED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/comment/index', array('status'=>Messages::STATUS_APPROVED))?>">通过</a>
			<span class="color-grey">(<?php echo Message::model()->getCount(Messages::STATUS_APPROVED)?>)</span>
			|
		</li>
		<li class="draft <?php if(F::app()->input->get('status', 'intval') === Messages::STATUS_UNAPPROVED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/comment/index', array('status'=>Messages::STATUS_UNAPPROVED))?>">驳回</a>
			<span class="color-grey">(<?php echo Message::model()->getCount(Messages::STATUS_UNAPPROVED)?>)</span>
			|
		</li>
		<li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
			<a href="<?php echo $this->url('admin/comment/index', array('deleted'=>1))?>">回收站</a>
			<span class="color-grey">(<?php echo Message::model()->getDeletedCount()?>)</span>
		</li>
	</ul>
	
	<table class="list-table" border="0" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="wp15">评论者</th>
				<th>评论内容</th>
				<th class="wp15">评论给</th>
				<th class="w35">状态</th>
				<th class="wp10">评论时间</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>评论者</th>
				<th>评论内容</th>
				<th>评论给</th>
				<th>状态</th>
				<th>评论时间</th>
			</tr>
		</tfoot>
		<tbody>
			<?php $listview->showData();?>
		</tbody>
	</table>
	<?php echo $listview->showPager()?>
</div>
