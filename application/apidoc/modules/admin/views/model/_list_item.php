<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['name'])?></strong>
		<div class="row-actions">
			<?php 
				echo Html::link('编辑', array('admin/model/edit', array(
					'id'=>$data['id'],
				)), array(), true);
				echo Html::link('删除', array('admin/model/remove', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-red remove-link',
				), true);
			?>
		</div>
	</td>
	<td><?php echo Html::encode($data['description']);?></td>
	<td><?php echo Html::encode($data['since']);?></td>
	<td><abbr class="time" title="<?php echo Date::format($data['create_time'])?>">
		<?php echo Date::niceShort($data['create_time'])?>
	</abbr></td>
	<td><abbr class="time" title="<?php echo Date::format($data['last_modified_time'])?>">
		<?php echo Date::niceShort($data['last_modified_time'])?>
	</abbr></td>
</tr>