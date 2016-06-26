<?php
use fay\helpers\Html;
use fay\services\File;
use fay\helpers\Date;
use fay\core\Sql;
use fay\models\tables\Messages;

$children = array();
if(!$data['is_terminal']){
	$sql = new Sql();
	$children = $sql->from(array('m'=>'messages'))
		->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'nickname,avatar,nickname')
		->joinLeft(array('m2'=>'messages'), 'm.parent = m2.id', 'user_id AS parent_user_id')
		->joinLeft(array('u2'=>'users'), 'm2.user_id = u2.id', 'nickname AS parent_nickname')
		->where(array(
			'm.root = '.$data['id'],
			'm.status = '.Messages::STATUS_APPROVED,
			'm.deleted = 0',
		))
		->order('id')
		->fetchAll();
}?>
<li id="msg-<?php echo $data['id']?>">	
	<div class="avatar">
		<?php echo Html::img($data['avatar'], File::PIC_THUMBNAIL, array(
			'alt'=>$data['nickname'],
			'spare'=>'avatar',
		))?>
	</div>
	<div class="meta">
		<?php echo Html::link($data['nickname'], 'javascript:;', array(
			'class'=>'user-link',
		))?>
		<time class="time"><?php echo Date::niceShort($data['create_time'])?></time>
	</div>
	<div class="message-content"><?php echo nl2br(Html::encode($data['content']))?></div>
	<ul class="children-list">
	<?php foreach($children as $m){?>
		<li>
			<span class="un"><?php
				if($m['user_id'] == $m['parent_user_id']){
					echo Html::link($m['nickname'], 'javascript:;'), ' : ';
				}else{
					echo Html::link($m['nickname'], 'javascript:;'),
					' 回复 ',
					Html::link($m['parent_nickname'], 'javascript:;'),
					' : ';
				}
			?></span>
			<p><?php echo nl2br(Html::encode($m['content']))?></p>
			<time><?php echo Date::niceShort($m['create_time'])?></time>
			<?php echo Html::link('', 'javascript:;', array(
				'title'=>'回复',
				'class'=>'icon-comment reply-child-link',
				'data-parent'=>$m['id'],
			))?>
		</li>
	<?php }?>
	</ul>
	<?php echo Html::link('', 'javascript:;', array(
		'title'=>'回复',
		'class'=>'icon-reply reply-link',
		'data-parent'=>$data['id'],
	))?>
</li>