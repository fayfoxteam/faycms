<?php
use fay\helpers\Html;
?>
<tr valign="top" id="option-<?php echo $data['id']?>">
	<td>
		<strong><?php echo $data['alias']?></strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('admin/widgetarea/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
			<a href="<?php echo $this->url('admin/widgetarea/delete', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red remove-link">永久删除</a>
		</div>
	</td>
	<td><?php echo Html::encode($data['description'])?></td>
</tr>