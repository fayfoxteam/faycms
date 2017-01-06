<?php
use fay\helpers\HtmlHelper;
use cms\helpers\PropHelper;
?>
<tr valign="top">
	<td>
		<strong><?php echo HtmlHelper::encode($data['title'])?></strong>
		<?php if($data['alias']){?>
		<em class="fc-grey">[ <?php echo $data['alias']?> ]</em>
		<?php }?>
		<?php if($data['refer'] != F::form()->getData('refer')){?>
		<sup class="bg-yellow title-sup" title="继承自父节点">继承</sup>
		<?php }else{?>
		<sup class="bg-green title-sup" title="自有属性">自有</sup>
		<?php }?>
		<?php if($data['refer'] == F::form()->getData('refer')){?>
		<div class="row-actions">
			<?php echo HtmlHelper::link('编辑', array('admin/post-prop/edit', array(
				'id'=>$data['id'],
			) + F::input()->get()))?>
			<?php echo HtmlHelper::link('删除', array('admin/post-prop/delete', array(
				'id'=>$data['id'],
			) + F::input()->get()), array(
				'class'=>'remove-link fc-red',
			))?>
		</div>
		<?php }?>
	</td>
	<td><?php PropHelper::getElement($data['element'])?></td>
	<td><?php echo $data['required'] ? '是' : '否';?></td>
	<td class="w90"><?php echo HtmlHelper::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'form-control w50 ib edit-sort',
	))?></td>
</tr>