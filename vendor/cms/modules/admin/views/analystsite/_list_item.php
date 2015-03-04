<?php
use fay\helpers\Html;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<div class="row-actions">
			<?php 
			echo Html::link('编辑', array('admin/analyst-site/edit', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(), true);
			echo Html::link('永久删除', array('admin/analyst-site/delete', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(
				'class'=>'color-red remove-link',
			), true);
			?>
		</div>
	</td>
	<td><?php echo Html::encode($data['description'])?></td>
</tr>