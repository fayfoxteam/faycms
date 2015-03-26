<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<tr valign="top">
	<td class="<?php if(!$data['read']){
		echo 'bl-yellow';
	}else{
		echo 'pl11';
	}?>"><?php
		echo Html::inputCheckbox('ids[]', $data['id'], false, array(
			'class'=>'batch-ids',
		));
	?></td>
	<td>
		<strong><?php echo Html::encode($data['content'])?></strong>
		<div class="row-actions">
			<?php
			if($data['read']){
				echo Html::link('标记为未读', array('admin/notification/set-read', array(
					'read'=>0,
					'id'=>$data['id'],
				)), array(
					'class'=>'set-read-link fc-orange',
				));
			}else{
				echo Html::link('标记为已读', array('admin/notification/set-read', array(
					'read'=>1,
					'id'=>$data['id'],
				)), array(
					'class'=>'set-read-link fc-green',
				));
			}
			echo Html::link('删除', array('admin/notification/delete', array('id'=>$data['id'])), array(
				'class'=>'delete-notification fc-red',
			));?>
		</div>
	</td>
	<td class="wp15"><?php echo $data['cat_title'] ? $data['cat_title'] : '系统消息'?></td>
	<td class="wp15"><?php echo $data['username']?></td>
	<td class="col-date">
		<span class="time abbr" title="<?php echo Date::format($data['publish_time'])?>">
			<?php echo Date::niceShort($data['publish_time'])?>
		</span>
	</td>
</tr>