<?php
use fay\helpers\Html;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('admin/goods-cat-prop/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
			<a href="<?php echo $this->url('admin/goods-cat-prop/delete', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red">删除</a>
		</div>
	</td>
	<td><?php echo $data['required'] ? '是' : '否';?></td>
	<td><?php echo $data['is_sale_prop'] ? '是' : '否';?></td>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'tag-sort w30',
	))?></td>
</tr>