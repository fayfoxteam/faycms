<?php
use fay\helpers\HtmlHelper;
?>
<tr valign="top" id="option-<?php echo $data['id']?>">
	<td>
		<strong><?php echo $data['option_name']?></strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('cms/admin/option/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
			<?php if($data['is_system'] == 0){?>
			<a href="<?php echo $this->url('cms/admin/option/remove', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red remove-link">永久删除</a>
			<?php }?>
		</div>
	</td>
	<td><?php echo HtmlHelper::encode($data['option_value'])?></td>
	<td><?php 
		if($data['is_system'] == 1){
			echo '是';
		}else{
			echo '否';
		}
	?></td>
</tr>