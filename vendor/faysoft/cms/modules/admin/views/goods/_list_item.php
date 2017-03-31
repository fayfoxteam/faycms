<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
use fay\models\tables\GoodsTable;
use fay\helpers\DateHelper;

$editable = F::app()->checkPermission('admin/goods/edit');
?>
<tr valign="top" id="file-<?php echo $data['id']?>">
	<td><?php echo HtmlHelper::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
		'disabled'=>$editable ? false : 'disabled',
	));?></td>
	<?php if(in_array('id', $cols)){?>
	<td><?php echo $data['id']?></td>
	<?php }?>
	<?php if(in_array('thumbnail', $cols)){?>
	<td class="align-center">
		<?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_THUMBNAIL, array(
			'width'=>60,
			'height'=>60,
			'spare'=>'thumbnail',
		)), array('admin/file/pic', array(
			'f'=>$data['thumbnail'],
		)), array(
			'class'=>'fancybox-image',
			'encode'=>false,
			'title'=>HtmlHelper::encode($data['title']),
		))?>
	</td>
	<?php }?>
	<td>
		<strong><?php
			if($editable){
				echo HtmlHelper::link($data['title'] ? $data['title'] : '--无标题--', array('admin/post/edit', array(
					'id'=>$data['id'],
				)));
			}else{
				echo HtmlHelper::link($data['title'] ? $data['title'] : '--无标题--', 'javascript:;');
			}
		?></strong>
		<div class="row-actions">
			<?php echo HtmlHelper::link('编辑', array('admin/goods/edit', array('id'=>$data['id'])))?>
			<?php echo HtmlHelper::link('查看', array('goods/item', array('id'=>$data['id'])), array(
				'target'=>'_blank',
			))?>
			<?php echo HtmlHelper::link('删除', array('admin/goods/delete', array('id'=>$data['id'])), array(
				'class'=>'fc-red remove-link',
			))?>
		</div>
	</td>
	<?php if(in_array('sn', $cols)){?>
	<td><?php echo $data['sn']?></td>
	<?php }?>
	<?php if(in_array('category', $cols)){?>
	<td><?php echo HtmlHelper::link($data['cat_title'], array('admin/goods/index', array(
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
	<td><?php echo HtmlHelper::link('', 'javascript:;', array(
		'class'=>($data['is_new'] ? 'tick-circle' : 'cross-circle') . ' ' . ($editable ? 'is-new-link' : ''),
		'data-id'=>$data['id'],
		'encode'=>false,
	));?></td>
	<?php }?>
	<?php if(in_array('is_hot', $cols)){?>
	<td><?php echo HtmlHelper::link('', 'javascript:;', array(
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
	<td><?php if($data['status'] == GoodsTable::STATUS_INSTOCK){
		echo '<span class="fc-orange">在库</span>';
	}else if($data['status'] == GoodsTable::STATUS_ONSALE){
		echo '<span class="fc-green">销售中</span>';
	}?></td>
	<?php }?>
	<?php if(in_array('sort', $cols)){?>
	<td><?php if($editable){
		echo HtmlHelper::inputText("sort[{$data['id']}]", $data['sort'], array(
			'data-id'=>$data['id'],
			'class'=>'form-control w70 ib edit-sort',
		));
	}else{
		echo $data['sort'];
	}?></td>
	<?php }?>
	<?php if(in_array('publish_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo DateHelper::format($data['publish_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo DateHelper::niceShort($data['publish_time']);
			}else{
				echo DateHelper::format($data['publish_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('update_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo DateHelper::format($data['update_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo DateHelper::niceShort($data['update_time']);
			}else{
				echo DateHelper::format($data['update_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo DateHelper::niceShort($data['create_time']);
			}else{
				echo DateHelper::format($data['create_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
</tr>