<?php
use fay\helpers\Html;
use fay\models\Template;
?>
<tr>
	<td>
		<strong><?php echo Html::encode($data['alias'])?></strong>
		<div class="row-actions">
			<?php echo Html::link('编辑', array('admin/template/edit', array('id'=>$data['id'])))?>
			<?php echo Html::link('删除', array('admin/template/delete', array('id'=>$data['id'])), array(
				'class'=>'fc-red remove-link',
			))?>
		</div>
	</td>
	<td><?php echo Html::encode($data['description'])?></td>
	<td><?php echo $data['enable'] ? Html::link('', 'javascript:;', array(
		'class'=>'tick-circle is-enable-link',
		'data-id'=>$data['id'],
		'encode'=>false,
	)) : Html::link('', 'javascript:;', array(
		'class'=>'cross-circle is-enable-link',
		'data-id'=>$data['id'],
		'encode'=>false,
	));?></td>
	<td><?php echo Template::getType($data['type'])?></td>
</tr>