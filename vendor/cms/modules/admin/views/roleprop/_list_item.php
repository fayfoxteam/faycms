<?php
use fay\helpers\Html;
use fay\models\tables\Props;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<?php if($data['alias']){
			echo "<em> - {$data['alias']}</em>";
		}?>
		<div class="row-actions">
			<?php echo Html::link('编辑', array('admin/role-prop/edit', array(
				'id'=>$data['id'],
			) + F::input()->get()))?>
			<?php echo Html::link('删除', array('admin/role-prop/delete', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(
				'class'=>'remove-link color-red',
			))?>
		</div>
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
	<td><?php echo $data['is_show'] ? '<span class="color-green">是</span>' : '否';?></td>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'edit-sort w30',
	))?></td>
</tr>