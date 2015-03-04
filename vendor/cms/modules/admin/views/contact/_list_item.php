<?php
use fay\models\tables\Contacts;
use fay\helpers\Html;
use fay\helpers\Date;
?>
<tr valign="top">
	<td class="<?php if($data['status'] == Contacts::STATUS_UNREAD){
		echo 'bl-yellow';
	}else{
		echo 'pl11';
	}?>">
		<?php echo F::form()->inputCheckbox('ids[]', $data['id'])?>
	</td>
	<td>
		<?php echo Html::encode($data['content'])?>
		<div class="row-actions">
 			<?php 
 			if($data['status'] == Contacts::STATUS_READ){
 				echo Html::link('标记为未读', array('admin/contact/set-unread', array('id'=>$data['id'])));
 			}else{
 				echo Html::link('标记为已读', array('admin/contact/set-read', array('id'=>$data['id'])));
 			}
			echo Html::link('删除', array('admin/contact/remove', array('id'=>$data['id'])), array(
				'class'=>'color-red remove-link',
			))?>
		</div>
	</td>
	<?php if(in_array('realname', $cols)){?>
	<td><?php echo Html::encode($data['realname'])?></td>
	<?php }?>
	<?php if(in_array('email', $cols)){?>
	<td><a href="mailto:<?php echo Html::encode($data['email'])?>">
		<?php echo Html::encode($data['email'])?>
	</a></td>
	<?php }?>
	<?php if(in_array('phone', $cols)){?>
	<td><?php echo Html::encode($data['phone'])?></td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td>
		<span class="time abbr" title="<?php echo Date::format($data['create_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['create_time']);
			}else{
				echo Date::format($data['create_time']);
			}?>
		</span>
	</td>
	<?php }?>
	<?php if(in_array('area', $cols)){?>
	<td><span class="abbr" title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></span></td>
	<?php }?>
	<?php if(in_array('ip', $cols)){?>
	<td><?php echo long2ip($data['ip_int'])?></td>
	<?php }?>
</tr>