<?php
use fay\helpers\Html;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['name'])?></strong>
		<div class="row-actions"><?php
			echo Html::link('编辑', array('admin/api/edit', array(
				'id'=>$data['id'],
			)), array(), true),
			Html::link('永久删除', array('admin/api/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'fc-red remove-link',
			), true);
		?></div>
	</td>
	<td><?php echo $data['link']?></td>
</tr>