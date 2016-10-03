<?php
use fay\helpers\Html;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['keyword'])?></strong>
		<div class="row-actions"><?php
			echo Html::link('编辑', array('admin/keyword/edit', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(), true),
			Html::link('永久删除', array('admin/keyword/remove', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(
				'class'=>'fc-red remove-link',
			), true);
		?></div>
	</td>
	<td><?php echo $data['link']?></td>
</tr>