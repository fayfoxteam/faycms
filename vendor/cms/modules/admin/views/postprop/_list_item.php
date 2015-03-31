<?php
use fay\helpers\Html;
use fay\models\tables\Props;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<?php if($data['alias']){?>
		<em class="fc-grey">[ <?php echo $data['alias']?> ]</em>
		<?php }?>
		<?php if($data['refer'] != $refer){?>
		<sup class="bg-yellow title-sup" title="继承自父节点">继承</sup>
		<?php }else{?>
		<sup class="bg-green title-sup" title="自有属性">自有</sup>
		<?php }?>
		<?php if($data['refer'] == $refer){?>
		<div class="row-actions">
			<?php echo Html::link('编辑', array('admin/post-prop/edit', array(
				'id'=>$data['id'],
			) + F::input()->get()))?>
			<?php echo Html::link('删除', array('admin/post-prop/delete', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(
				'class'=>'remove-link fc-red',
			))?>
		</div>
		<?php }?>
	</td>
	<td><?php switch($data['element']){
		case Props::ELEMENT_TEXT:
			echo '文本框';
		break;
		case Props::ELEMENT_RADIO:
			echo '单选框';
		break;
		case Props::ELEMENT_SELECT:
			echo '下拉框';
		break;
		case Props::ELEMENT_CHECKBOX:
			echo '多选框';
		break;
		case Props::ELEMENT_TEXTAREA:
			echo '文本域';
		break;
	}?></td>
	<td><?php echo $data['required'] ? '是' : '否';?></td>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'form-control w50 ib edit-sort',
	))?></td>
</tr>