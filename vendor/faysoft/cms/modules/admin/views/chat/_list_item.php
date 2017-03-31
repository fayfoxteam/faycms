<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
use fay\services\file\FileService;
use fay\services\MessageService;
use fay\models\tables\MessagesTable;
?>
<li class="chat-item" id="chat-<?php echo $data['id']?>">
	<?php echo HtmlHelper::link(HtmlHelper::img($data['avatar'], FileService::PIC_THUMBNAIL, array(
		'width'=>40,
		'height'=>40,
		'class'=>'circle ci-avatar',
		'spare'=>'avatar',
	)), array('admin/user/item', array(
		'id'=>$data['user_id'],
	)), array(
		'title'=>false,
		'encode'=>false,
	))?>
	<div class="chat-body">
		<div class="ci-header">
			<span class="ci-user">
				<?php echo HtmlHelper::encode($data[$settings['display_name']])?>
			</span>
			留言给<span class="ci-to"><?php
				echo empty($data['to_'.$settings['display_name']]) ? '匿名' : HtmlHelper::encode($data['to_'.$settings['display_name']]);
			?></span>
			<abbr class="ci-time" title="<?php echo DateHelper::format($data['create_time'])?>"><?php
				echo DateHelper::niceShort($data['create_time']);
			?></abbr>
		</div>
		<div class="ci-meta">
			<span class="ci-status"><?php if($data['status'] == MessagesTable::STATUS_APPROVED){
				echo '<span class="fc-green">已通过</span>';
			}else if($data['status'] == MessagesTable::STATUS_UNAPPROVED){
				echo '<span class="fc-red">已驳回</span>';
			}else if($data['status'] == MessagesTable::STATUS_PENDING){
				echo '<span class="fc-orange">待审</span>';
			}?></span>
		</div>
		<div class="ci-content">
			<p><?php echo nl2br(HtmlHelper::encode($data['content']))?></p>
		</div>
		<div class="ci-footer">
			<a href="#chat-dialog" class="ci-reply-link" data-id="<?php echo $data['id']?>">
				<i class="fa fa-reply"></i>
				<span>回复</span>(<em><?php echo MessageService::service()->getReplyCount($data['id'])?></em>)&nbsp;
			</a>
			<span class="ci-options"><?php
			if(F::app()->checkPermission('admin/chat/approve')){
				echo HtmlHelper::link('<span>批准</span>&nbsp;|&nbsp;', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-green approve-link'.($data['status'] == MessagesTable::STATUS_APPROVED ? ' hide' : ''),
					'encode'=>false,
					'title'=>false,
				));
			}
			if(F::app()->checkPermission('admin/chat/unapprove')){
				echo HtmlHelper::link('<span>驳回</span>&nbsp;|&nbsp;', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-orange unapprove-link'.($data['status'] == MessagesTable::STATUS_UNAPPROVED ? ' hide' : ''),
					'encode'=>false,
					'title'=>false,
				));
			}
			if(F::app()->checkPermission('admin/chat/delete')){
				echo HtmlHelper::link('<span>回收站</span>&nbsp;|&nbsp;', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-red delete-link',
					'encode'=>false,
					'title'=>false,
				));
			}
			if(F::app()->checkPermission('admin/chat/remove-all')){
				echo HtmlHelper::link('<span>删除会话</span>', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-red remove-all-link',
					'encode'=>false,
					'title'=>false,
				));
			}
			?></span>
		</div>
	</div>
</li>