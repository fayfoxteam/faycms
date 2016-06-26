<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\services\File;
?>
<li>
	<div class="avatar">
		<?php echo Html::link(Html::img($data['avatar'], File::PIC_THUMBNAIL, array(
			'alt'=>$data['nickname'],
			'spare'=>'avatar',
		)), array('u/'.$data['user_id']), array(
			'encode'=>false,
			'title'=>false,
		))?>
	</div>
	<div class="meta">
		<?php echo Html::link($data['nickname'], array('u/'.$data['user_id']), array(
			'class'=>'user-link',
		))?>
		<time class="time"><?php echo Date::niceShort($data['create_time'])?></time>
	</div>
	<div class="comment-content"><?php echo Html::encode($data['content'])?></div>
	<?php if($data['parent']){?>
	<div class="parent">
		<em class="arrow-border"></em>
		<em class="arrow"></em>
		<?php echo Html::link($data['parent_nickname'], array('u/'.$data['parent_user_id']), array(
			'class'=>'parent-user-link',
		))?> 说：
		<p class="parent-content"><?php echo Html::encode($data['parent_content'])?></p>
	</div>
	<?php }?>
	<?php echo Html::link('', 'javascript:;', array(
		'title'=>'回复',
		'class'=>'icon-reply reply-link',
		'data-parent'=>$data['id'],
	))?>
</li>