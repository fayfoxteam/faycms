<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<tr valign="top">
	<td class="<?php if(!$data['is_read']){
		echo 'bl-yellow';
	}else{
		echo 'pl11';
	}?>">
		<?php echo F::form()->inputCheckbox('ids[]', $data['id'])?>
	</td>
	<td>
		<?php echo Html::encode($data['content'])?>
		<div class="row-actions"><?php 
 			if($data['is_read']){
 				echo Html::link('标记为未读', array('admin/contact/set-unread', array('id'=>$data['id'])));
 			}else{
 				echo Html::link('标记为已读', array('admin/contact/set-read', array('id'=>$data['id'])));
 			}
			echo Html::link('删除', array('admin/contact/remove', array('id'=>$data['id'])), array(
				'class'=>'fc-red remove-link',
			));
			echo Html::link('回复', '#reply-dialog', array(
				'class'=>'reply-link',
				'data-id'=>$data['id'],
				'data-name'=>$data['name'],
				'data-phone'=>$data['phone'],
				'data-email'=>$data['email'],
				'data-reply'=>$data['reply'],
			));
		?></div>
	</td>
	<?php if(in_array('title', $cols)){?>
	<td><?php echo Html::encode($data['title'])?></td>
	<?php }?>
	<?php if(in_array('reply', $cols)){?>
	<td><?php echo Html::encode($data['reply'])?></td>
	<?php }?>
	<?php if(in_array('name', $cols)){?>
	<td><?php echo Html::encode($data['name'])?></td>
	<?php }?>
	<?php if(in_array('email', $cols)){?>
	<td><a href="mailto:<?php echo Html::encode($data['email'])?>">
		<?php echo Html::encode($data['email'])?>
	</a></td>
	<?php }?>
	<?php if(in_array('country', $cols)){?>
	<td><?php echo Html::encode($data['country'])?></td>
	<?php }?>
	<?php if(in_array('phone', $cols)){?>
	<td><?php echo Html::encode($data['phone'])?></td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['create_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['create_time']);
			}else{
				echo Date::format($data['create_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('area', $cols)){?>
	<td><abbr title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></abbr></td>
	<?php }?>
	<?php if(in_array('ip', $cols)){?>
	<td><?php echo long2ip($data['ip_int'])?></td>
	<?php }?>
</tr>