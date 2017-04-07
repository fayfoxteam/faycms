<?php
use fay\helpers\HtmlHelper;
use fay\models\tables\PostCommentsTable;
use cms\helpers\PostCommentHelper;

$settings = F::form('setting')->getAllData();
$cols = F::form('setting')->getData('cols', array());
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5"><?php
				echo F::form('search')->select('keywords_field', array(
					'pc.content'=>'评论内容',
					'p.title'=>'文章标题',
					'pc.id'=>'评论ID',
					'p.id'=>'文章ID',
					'pc.user_id'=>'评论人ID',
				), array(
					'class'=>'form-control',
				)),
				'&nbsp;',
				F::form('search')->inputText('keywords', array(
					'class'=>'form-control w200',
				)),
				HtmlHelper::tag('span', array(
					'class'=>'pl11',
				), '评论时间'),
				'&nbsp;',
				F::form('search')->inputText('start_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'时间',
					'class'=>'form-control datetimepicker',
				)),
				' - ',
				F::form('search')->inputText('end_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'时间',
					'class'=>'form-control datetimepicker',
				)),
				F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<ul class="subsubsub">
			<li class="all <?php if(F::app()->input->get('status') === null && F::app()->input->get('deleted') === null)echo 'sel';?>">
				<a href="<?php echo $this->url('cms/admin/post-comment/index')?>">全部</a>
				<span class="fc-grey">(<span id="all-post-comments-count">
					<img src="<?php echo $this->assets('images/throbber.gif')?>" />
				</span>)</span>
				|
			</li>
			<li class="publish <?php if(F::app()->input->get('status', 'intval') === PostCommentsTable::STATUS_PENDING && F::app()->input->get('deleted') != 1)echo 'sel';?>">
				<?php echo HtmlHelper::link(PostCommentHelper::getStatus(PostCommentsTable::STATUS_PENDING, 0, false), array('cms/admin/post-comment/index', array(
					'status'=>PostCommentsTable::STATUS_PENDING,
				)));?>
				<span class="fc-grey">(<span id="pending-post-comment-count">
					<img src="<?php echo $this->assets('images/throbber.gif')?>" />
				</span>)</span>
				|
			</li>
			<li class="draft <?php if(F::app()->input->get('status', 'intval') === PostCommentsTable::STATUS_APPROVED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
				<?php echo HtmlHelper::link(PostCommentHelper::getStatus(PostCommentsTable::STATUS_APPROVED, 0, false), array('cms/admin/post-comment/index', array(
					'status'=>PostCommentsTable::STATUS_APPROVED,
				)));?>
				<span class="fc-grey">(<span id="approved-post-comment-count">
					<img src="<?php echo $this->assets('images/throbber.gif')?>" />
				</span>)</span>
				|
			</li>
			<li class="draft <?php if(F::app()->input->get('status', 'intval') === PostCommentsTable::STATUS_UNAPPROVED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
				<?php echo HtmlHelper::link(PostCommentHelper::getStatus(PostCommentsTable::STATUS_UNAPPROVED, 0, false), array('cms/admin/post-comment/index', array(
					'status'=>PostCommentsTable::STATUS_UNAPPROVED,
				)));?>
				<span class="fc-grey">(<span id="unapproved-post-comment-count">
					<img src="<?php echo $this->assets('images/throbber.gif')?>" />
				</span>)</span>
				|
			</li>
			<li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
				<?php echo HtmlHelper::link(PostCommentHelper::getStatus(0, 1, false), array('cms/admin/post-comment/index', array(
					'delete_time'=>\F::app()->current_time,
				)));?>
				<span class="fc-grey">(<span id="deleted-post-comment-count">
					<img src="<?php echo $this->assets('images/throbber.gif')?>" />
				</span>)</span>
			</li>
		</ul>
	</div>
</div>
<form method="post" action="<?php echo $this->url('cms/admin/post-comment/batch')?>" id="batch-form" class="form-inline">
	<div class="row">
		<div class="col-5"><?php
			if(F::app()->input->get('deleted')){
				echo HtmlHelper::select('', array(
					''=>'批量操作',
					'undelete'=>F::app()->checkPermission('cms/admin/post-comment/undelete') ? '还原' : false,
					'remove'=>F::app()->checkPermission('cms/admin/post-comment/remove') ? '永久删除' : false,
				), '', array(
					'class'=>'form-control',
					'id'=>'batch-action',
				));
			}else{
				echo HtmlHelper::select('', array(
					''=>'批量操作',
					'set-approved'=>F::app()->checkPermission('cms/admin/post-comment/approve') ? '通过审核' : false,
					'set-disapproved'=>F::app()->checkPermission('cms/admin/post-comment/disapprove') ? '驳回' : false,
					'set-pending'=>F::app()->checkPermission('cms/admin/post-comment/pending') ? '标记为待审核' : false,
					'delete'=>F::app()->checkPermission('cms/admin/post-comment/delete') ? '移入回收站' : false,
				), '', array(
					'class'=>'form-control',
					'id'=>'batch-action',
				));
			}
			echo HtmlHelper::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit',
				'class'=>'btn btn-sm ml5',
			));
		?></div>
		<div class="col-7"><?php $listview->showPager()?></div>
	</div>
	<div class="row">
		<div class="col-12">
			<table class="list-table">
				<thead>
					<tr>
						<th class="w20"><input type="checkbox" class="batch-ids-all" /></th>
						<?php if(in_array('id', $cols)){?>
						<th class="w70">评论ID</th>
						<?php }?>
						<th>评论内容</th>
						<?php if(in_array('user', $cols)){?>
						<th>评论人</th>
						<?php }?>
						<?php if(in_array('post', $cols)){?>
						<th>评论给</th>
						<?php }?>
						<?php if(in_array('status', $cols)){?>
						<th>状态</th>
						<?php }?>
						<?php if(in_array('create_time', $cols)){?>
						<th class="wp10">评论时间</th>
						<?php }?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><input type="checkbox" class="batch-ids-all" /></th>
						<?php if(in_array('id', $cols)){?>
						<th>评论ID</th>
						<?php }?>
						<th>评论内容</th>
						<?php if(in_array('user', $cols)){?>
						<th>评论人</th>
						<?php }?>
						<?php if(in_array('post', $cols)){?>
						<th>评论给</th>
						<?php }?>
						<?php if(in_array('status', $cols)){?>
						<th>状态</th>
						<?php }?>
						<?php if(in_array('create_time', $cols)){?>
						<th>评论时间</th>
						<?php }?>
					</tr>
				</tfoot>
				<tbody>
					<?php $listview->showData(array(
						'settings'=>$settings,
						'cols'=>$cols,
					));?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-7 fr"><?php $listview->showPager()?></div>
		<div class="col-5"><?php
			if(F::app()->input->get('deleted')){
				echo HtmlHelper::select('', array(
					''=>'批量操作',
					'undelete'=>F::app()->checkPermission('cms/admin/post-comment/undelete') ? '还原' : false,
					'remove'=>F::app()->checkPermission('cms/admin/post-comment/remove') ? '永久删除' : false,
				), '', array(
					'class'=>'form-control',
					'id'=>'batch-action-2',
				));
			}else{
				echo HtmlHelper::select('', array(
					''=>'批量操作',
					'set-approved'=>F::app()->checkPermission('cms/admin/post-comment/approve') ? '通过审核' : false,
					'set-disapproved'=>F::app()->checkPermission('cms/admin/post-comment/disapprove') ? '驳回' : false,
					'set-pending'=>F::app()->checkPermission('cms/admin/post-comment/pending') ? '标记为待审核' : false,
					'delete'=>F::app()->checkPermission('cms/admin/post-comment/delete') ? '移入回收站' : false,
				), '', array(
					'class'=>'form-control',
					'id'=>'batch-action-2',
				));
			}
			echo HtmlHelper::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit-2',
				'class'=>'btn btn-sm ml5',
			));
		?></div>
	</div>
</form>
<script>
$(function(){
	//显示各状态文章数
	$.ajax({
		'type': 'GET',
		'url': system.url('cms/admin/post-comment/get-counts'),
		'dataType': 'json',
		'cache': false,
		'success': function(resp){
			$('#all-post-comments-count').text(resp.data.all);
			$('#pending-post-comment-count').text(resp.data.pending);
			$('#approved-post-comment-count').text(resp.data.approved);
			$('#unapproved-post-comment-count').text(resp.data.unapproved);
			$('#deleted-post-comment-count').text(resp.data.deleted);
		}
	});
});
</script>