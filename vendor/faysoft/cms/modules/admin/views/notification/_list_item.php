<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>
<tr valign="top">
	<td class="<?php if(!$data['read']){
		echo 'bl-yellow';
	}else{
		echo 'pl11';
	}?>"><?php
		echo HtmlHelper::inputCheckbox('ids[]', $data['notification_id'], false, array(
			'class'=>'batch-ids',
		));
	?></td>
	<td>
		<strong><?php echo HtmlHelper::encode($data['title'])?></strong>
		<span class="fc-grey pl11">接收于:<abbr class="time" title="<?php echo DateHelper::format($data['publish_time'])?>">
			<?php echo DateHelper::niceShort($data['publish_time'])?>
		</abbr></span>
		<p><?php echo $data['content']?></p>
		<div class="row-actions">
			<?php
			if($data['read']){
				echo HtmlHelper::link('标记为未读', array('cms/admin/notification/set-read', array(
					'read'=>0,
					'id'=>$data['notification_id'],
				)), array(
					'class'=>'set-read-link fc-orange',
				));
			}else{
				echo HtmlHelper::link('标记为已读', array('cms/admin/notification/set-read', array(
					'read'=>1,
					'id'=>$data['notification_id'],
				)), array(
					'class'=>'set-read-link fc-green',
				));
			}
			echo HtmlHelper::link('删除', array('cms/admin/notification/delete', array('id'=>$data['notification_id'])), array(
				'class'=>'delete-notification fc-red',
			));?>
		</div>
	</td>
	<td class="wp15"><?php echo $data['cat_title'] ? $data['cat_title'] : '系统消息'?></td>
	<td class="wp15"><?php echo $data['username']?></td>
</tr>