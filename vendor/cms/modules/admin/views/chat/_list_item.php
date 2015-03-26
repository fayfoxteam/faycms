<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\models\File;
use fay\models\Message;
use fay\models\tables\Messages;

$settings = F::form('setting')->getAllData();
?>
<li class="chat-item" id="chat-<?php echo $data['id']?>">
	<?php echo Html::link(Html::img($data['avatar'], File::PIC_THUMBNAIL, array(
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
				<?php echo Html::encode($data[$settings['display_name']])?>
			</span>
			留言给<span class="ci-to"><?php
				echo Html::encode($data['target_'.$settings['display_name']]);
			?></span>
			<span class="ci-time abbr" title="<?php echo Date::format($data['create_time'])?>"><?php
				echo Date::niceShort($data['create_time']);
			?></span>
		</div>
		<div class="ci-meta">
			<span class="ci-status"><?php if($data['status'] == Messages::STATUS_APPROVED){
				echo '<span class="fc-green">已通过</span>';
			}else if($data['status'] == Messages::STATUS_UNAPPROVED){
				echo '<span class="fc-red">已驳回</span>';
			}else if($data['status'] == Messages::STATUS_PENDING){
				echo '<span class="fc-orange">待审</span>';
			}?></span>
		</div>
		<div class="ci-content">
			<p><?php echo nl2br(Html::encode($data['content']))?></p>
		</div>
		<div class="ci-footer">
			<a href="#chat-dialog" class="ci-reply-link" data-id="<?php echo $data['id']?>">
				<i class="fa fa-reply"></i>
				<span>回复</span>(<em><?php echo Message::model()->getReplyCount($data['id'])?></em>)&nbsp;
			</a>
			<span class="ci-options"><?php
			if(F::app()->checkPermission('admin/chat/approve')){
				echo Html::link('<span>批准</span>&nbsp;|&nbsp;', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-green approve-link'.($data['status'] == Messages::STATUS_APPROVED ? ' hide' : ''),
					'encode'=>false,
					'title'=>false,
				));
			}
			if(F::app()->checkPermission('admin/chat/unapprove')){
				echo Html::link('<span>驳回</span>&nbsp;|&nbsp;', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-orange unapprove-link'.($data['status'] == Messages::STATUS_UNAPPROVED ? ' hide' : ''),
					'encode'=>false,
					'title'=>false,
				));
			}
			if(F::app()->checkPermission('admin/chat/delete')){
				echo Html::link('<span>回收站</span>&nbsp;|&nbsp;', 'javascript:;', array(
					'data-id'=>$data['id'],
					'class'=>'fc-red delete-link',
					'encode'=>false,
					'title'=>false,
				));
			}
			if(F::app()->checkPermission('admin/chat/remove-all')){
				echo Html::link('<span>删除会话</span>', 'javascript:;', array(
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