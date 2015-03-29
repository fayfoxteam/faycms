<?php
use fay\helpers\Html;
use fay\models\Page;
use fay\helpers\Date;
?>
<tr valign="top" id="page-<?php echo $data['id']?>">
	<td>
		<strong>
			<?php echo Html::link($data['title'], array('page/item', array(
				'id'=>$data['id'],
			)), array(
				'target'=>'_blank',
			))?>
		</strong>
		<div class="row-actions">
		<?php if($data['deleted'] == 0){
			echo Html::link('编辑', array('admin/page/edit', array(
				'id'=>$data['id'],
			)), array(), true);
			echo Html::link('移入回收站', array('admin/page/delete', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'delete-page fc-red',
			), true);
			echo Html::link('查看', array('page/item', array(
				'id'=>$data['id'],
			)), array(
				'target'=>'_blank',
			), true);
		}else{
			echo Html::link('还原', array('admin/page/undelete', array(
				'id'=>$data['id'],
			)), array(), true);
			echo Html::link('永久删除', array('admin/page/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'delete-page fc-red remove-link',
			), true);
		}?>
		</div>
	</td>
	<?php if(!isset($_settings['cols']) || in_array('category', $_settings['cols'])){?>
	<td class="wp15"><?php 
		$cats = Page::model()->getPageCats($data['id']);
		foreach($cats as $key => $cat){
			if($key){
				echo ', ';
			}
			echo Html::link($cat['title'], array('admin/page/index', array(
				'cat_id'=>$cat['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(!isset($_settings['cols']) || in_array('status', $_settings['cols'])){?>
	<td class="wp10"><?php echo Page::getPageStatus($data['status'], $data['deleted']);?></td>
	<?php }?>
	<?php if(!isset($_settings['cols']) || in_array('alias', $_settings['cols'])){?>
	<td><?php echo $data['alias']?></td>
	<?php }?>
	<?php if(!isset($_settings['cols']) || in_array('views', $_settings['cols'])){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
	<?php if(!isset($_settings['cols']) || in_array('last_modified_time', $_settings['cols'])){?>
	<td class="col-date">
		<abbr class="time" title="<?php echo Date::format($data['last_modified_time'])?>">
			<?php echo Date::niceShort($data['last_modified_time'])?>
		</abbr>
	</td>
	<?php }?>
	<?php if(!isset($_settings['cols']) || in_array('create_time', $_settings['cols'])){?>
	<td class="col-date">
		<abbr class="time" title="<?php echo Date::format($data['create_time'])?>">
			<?php echo Date::niceShort($data['create_time'])?>
		</abbr>
	</td>
	<?php }?>
	<?php if(!isset($_settings['cols']) || in_array('sort', $_settings['cols'])){?>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'post-sort w30',
	))?></td>
	<?php }?>
</tr>