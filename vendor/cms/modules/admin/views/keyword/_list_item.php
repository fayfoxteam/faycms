<?php
use fay\helpers\Html;
?>
<tr valign="top" id="keyword-<?php echo $data['id']?>">
	<td>
		<strong><?php echo Html::encode($data['keyword'])?></strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('admin/keyword/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
			<a href="<?php echo $this->url('admin/keyword/remove', array('id'=>$data['id']) + F::input()->get())?>" class="color-red remove-link">永久删除</a>
		</div>
	</td>
	<td><?php echo $data['link']?></td>
</tr>