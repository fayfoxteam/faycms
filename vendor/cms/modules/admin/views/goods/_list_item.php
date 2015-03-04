<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\tables\Goods;
use fay\helpers\Date;
?>
<tr valign="top" id="file-<?php echo $data['id']?>">
	<td class="align-center">
		<?php echo Html::link(Html::img($data['thumbnail'], File::PIC_THUMBNAIL, array(
			'width'=>60,
			'height'=>60,
		)), array('admin/file/pic', array(
			'f'=>$data['thumbnail'],
		)), array(
			'class'=>'fancybox-image',
			'encode'=>false,
			'title'=>Html::encode($data['title']),
		))?>
	</td>
	<td>
		<strong>
			<?php echo Html::link($data['title'])?>
		</strong>
		<div class="row-actions">
			<?php echo Html::link('编辑', array('admin/goods/edit', array('id'=>$data['id'])))?>
			<?php echo Html::link('查看', array('goods/item', array('id'=>$data['id'])), array(
				'target'=>'_blank',
			))?>
			<?php echo Html::link('删除', array('admin/goods/delete', array('id'=>$data['id'])), array(
				'class'=>'color-red remove-link',
			))?>
		</div>
	</td>
	<td><?php echo $data['sn']?></td>
	<td><?php echo Html::link($data['cat_title'], array('admin/goods/index', array(
		'cat_id'=>$data['cat_id'],
	)))?></td>
	<td><?php echo $data['price']?></td>
	<td><?php echo $data['is_new'] ? Html::link('', 'javascript:;', array(
		'class'=>'tick-circle is-new-link',
		'data-id'=>$data['id'],
		'encode'=>false,
	)) : Html::link('', 'javascript:;', array(
		'class'=>'cross-circle is-new-link',
		'data-id'=>$data['id'],
		'encode'=>false,
	));?></td>
	<td><?php echo $data['is_hot'] ? Html::link('', 'javascript:;', array(
		'class'=>'tick-circle is-hot-link',
		'data-id'=>$data['id'],
		'encode'=>false,
	)) : Html::link('', 'javascript:;', array(
		'class'=>'cross-circle is-hot-link',
		'data-id'=>$data['id'],
		'encode'=>false,
	));?></td>
	<td><?php if($data['status'] == Goods::STATUS_INSTOCK){
		echo '<span class="color-orange">在库</span>';
	}else if($data['status'] == Goods::STATUS_ONSALE){
		echo '<span class="color-green">销售中</span>';
	}?></td>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'edit-sort w30',
	))?></td>
	<td><span class="time abbr" title="<?php echo Date::format($data['create_time'])?>"><?php echo Date::niceShort($data['create_time'])?></span></td>
</tr>