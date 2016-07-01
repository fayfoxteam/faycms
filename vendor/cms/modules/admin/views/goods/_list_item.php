<?php
use fay\helpers\Html;
use fay\services\File;
use fay\models\tables\Goods;
use fay\helpers\Date;

$editable = F::app()->checkPermission('admin/goods/edit');
?>
<tr valign="top" id="file-<?php echo $data['id']?>">
	<td><?php echo Html::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
		'disabled'=>$editable ? false : 'disabled',
	));?></td>
	<?php if(in_array('id', $cols)){?>
	<td><?php echo $data['id']?></td>
	<?php }?>
	<?php if(in_array('thumbnail', $cols)){?>
	<td class="align-center">
		<?php echo Html::link(Html::img($data['thumbnail'], File::PIC_THUMBNAIL, array(
			'width'=>60,
			'height'=>60,
			'spare'=>'thumbnail',
		)), array('admin/file/pic', array(
			'f'=>$data['thumbnail'],
		)), array(
			'class'=>'fancybox-image',
			'encode'=>false,
			'title'=>Html::encode($data['title']),
		))?>
	</td>
	<?php }?>
	<td>
		<strong><?php
			if($editable){
				echo Html::link($data['title'] ? $data['title'] : '--无标题--', array('admin/post/edit', array(
					'id'=>$data['id'],
				)));
			}else{
				echo Html::link($data['title'] ? $data['title'] : '--无标题--', 'javascript:;');
			}
		?></strong>
		<div class="row-actions">
			<?php echo Html::link('编辑', array('admin/goods/edit', array('id'=>$data['id'])))?>
			<?php echo Html::link('查看', array('goods/item', array('id'=>$data['id'])), array(
				'target'=>'_blank',
			))?>
			<?php echo Html::link('删除', array('admin/goods/delete', array('id'=>$data['id'])), array(
				'class'=>'fc-red remove-link',
			))?>
		</div>
	</td>
	<?php if(in_array('sn', $cols)){?>
	<td><?php echo $data['sn']?></td>
	<?php }?>
	<?php if(in_array('category', $cols)){?>
	<td><?php echo Html::link($data['cat_title'], array('admin/goods/index', array(
		'cat_id'=>$data['cat_id'],
	)))?></td>
	<?php }?>
	<?php if(in_array('user', $cols)){?>
	<td><?php
		echo $data[F::form('setting')->getData('display_name', 'username')];
	?></td>
	<?php }?>
	<?php if(in_array('price', $cols)){?>
	<td><?php echo $data['price']?></td>
	<?php }?>
	<?php if(in_array('is_new', $cols)){?>
	<td><?php echo Html::link('', 'javascript:;', array(
		'class'=>($data['is_new'] ? 'tick-circle' : 'cross-circle') . ' ' . ($editable ? 'is-new-link' : ''),
		'data-id'=>$data['id'],
		'encode'=>false,
	));?></td>
	<?php }?>
	<?php if(in_array('is_hot', $cols)){?>
	<td><?php echo Html::link('', 'javascript:;', array(
		'class'=>($data['is_hot'] ? 'tick-circle' : 'cross-circle') . ' ' . ($editable ? 'is-hot-link' : ''),
		'data-id'=>$data['id'],
		'encode'=>false,
	));?></td>
	<?php }?>
	<?php if(in_array('views', $cols)){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
	<?php if(in_array('sales', $cols)){?>
	<td><?php echo $data['sales']?></td>
	<?php }?>
	<?php if(in_array('comments', $cols)){?>
	<td><?php echo $data['comments']?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td><?php if($data['status'] == Goods::STATUS_INSTOCK){
		echo '<span class="fc-orange">在库</span>';
	}else if($data['status'] == Goods::STATUS_ONSALE){
		echo '<span class="fc-green">销售中</span>';
	}?></td>
	<?php }?>
	<?php if(in_array('sort', $cols)){?>
	<td><?php if($editable){
		echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
			'data-id'=>$data['id'],
			'class'=>'form-control w70 ib edit-sort',
		));
	}else{
		echo $data['sort'];
	}?></td>
	<?php }?>
	<?php if(in_array('publish_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['publish_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['publish_time']);
			}else{
				echo Date::format($data['publish_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('last_modified_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['last_modified_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['last_modified_time']);
			}else{
				echo Date::format($data['last_modified_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['create_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['create_time']);
			}else{
				echo Date::format($data['create_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
</tr>