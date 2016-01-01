<?php
use fay\helpers\Html;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<?php if($data['alias']){?>
		<em class="fc-grey">[ <?php echo $data['alias']?> ]</em>
		<?php }?>
		<?php if($data['cat_id'] != F::form()->getData('cat_id')){?>
		<sup class="bg-yellow title-sup" title="继承自父节点">继承</sup>
		<?php }else{?>
		<sup class="bg-green title-sup" title="自有属性">自有</sup>
		<?php }?>
		<div class="row-actions">
			<?php if($data['cat_id'] == F::form()->getData('cat_id')){?>
			<div class="row-actions">
				<?php echo Html::link('编辑', array('admin/goods-cat-prop/edit', array(
					'id'=>$data['id'],
				) + F::input()->get()))?>
				<?php echo Html::link('删除', array('admin/goods-cat-prop/delete', array(
					'id'=>$data['id'],
				) + F::input()->get()), array(
					'class'=>'remove-link fc-red',
				))?>
			</div>
			<?php }?>
		</div>
	</td>
	<td><?php echo $data['required'] ? '是' : '否';?></td>
	<td><?php echo $data['is_sale_prop'] ? '是' : '否';?></td>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'form-control w50 ib edit-sort',
	))?></td>
</tr>