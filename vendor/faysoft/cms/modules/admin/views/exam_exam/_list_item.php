<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['paper_title'])?></strong>
		<div class="row-actions">
			<?php
				echo Html::link('查看', array('admin/exam-exam/item', array(
					'id'=>$data['id'],
				)), array(), true);
				echo Html::link('永久删除', array('admin/exam-exam/remove', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-red remove-link',
				), true);
			?>
		</div>
	</td>
	<td><?php echo Html::encode($data[$display_name]);?></td>
	<td><?php echo $data['score'], ' / ', $data['total_score']?></td>
	<td><abbr class="time" title="<?php echo Date::format($data['start_time']), ' - ', Date::format($data['end_time'])?>">
		<?php echo Date::diff($data['start_time'], $data['end_time'])?>
	</abbr></td>
	<td><abbr class="time" title="<?php echo Date::format($data['start_time'])?>">
		<?php echo Date::niceShort($data['start_time'])?>
	</abbr></td>
</tr>