<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
use cms\helpers\PageHelper;
use fay\services\PageService;
?>
<tr valign="top" id="page-<?php echo $data['id']?>">
	<td>
		<strong>
			<?php echo HtmlHelper::link($data['title'], array('admin/page/edit', array(
				'id'=>$data['id'],
			)), array(
				'target'=>'_blank',
			))?>
		</strong>
		<div class="row-actions">
		<?php if($data['deleted'] == 0){
			echo HtmlHelper::link('编辑', array('admin/page/edit', array(
				'id'=>$data['id'],
			)), array(), true);
			echo HtmlHelper::link('移入回收站', array('admin/page/delete', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'delete-page fc-red',
			), true);
		}else{
			echo HtmlHelper::link('还原', array('admin/page/undelete', array(
				'id'=>$data['id'],
			)), array(), true);
			echo HtmlHelper::link('永久删除', array('admin/page/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'delete-page fc-red remove-link',
			), true);
		}?>
		</div>
	</td>
	<?php if(in_array('category', $cols)){?>
	<td class="wp15"><?php 
		$cats = PageService::service()->getPageCats($data['id']);
		foreach($cats as $key => $cat){
			if($key){
				echo ', ';
			}
			echo HtmlHelper::link($cat['title'], array('admin/page/index', array(
				'cat_id'=>$cat['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td class="wp10"><?php echo PageHelper::getStatus($data['status'], $data['deleted']);?></td>
	<?php }?>
	<?php if(in_array('alias', $cols)){?>
	<td><?php echo $data['alias']?></td>
	<?php }?>
	<?php if(in_array('views', $cols)){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
	<?php if(in_array('update_time', $cols)){?>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['update_time'])?>">
			<?php echo DateHelper::niceShort($data['update_time'])?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
			<?php echo DateHelper::niceShort($data['create_time'])?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('sort', $cols)){?>
	<td><?php echo HtmlHelper::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'form-control page-sort ib w50',
	))?></td>
	<?php }?>
</tr>