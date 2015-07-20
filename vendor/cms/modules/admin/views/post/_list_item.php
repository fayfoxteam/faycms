<?php
use fay\helpers\Html;
use fay\models\Post;
use fay\helpers\Date;
use cms\helpers\PostHelper;
use fay\models\tables\Users;
use fay\models\Option;

/**
 * 超级管理员或未开启分类权限或当前用户有此分类操作权限，则文章可编辑
 */
if(F::session()->get('role') == Users::ROLE_SUPERADMIN || !F::app()->role_cats || in_array($data['cat_id'], F::session()->get('role_cats', array()))){
	/**
	 * 是否有权限编辑（此处验证的是分类权限）
	 */
	$editable = true;
}else{
	$editable = false;
}
?>
<tr valign="top" id="post-<?php echo $data['id']?>">
	<td><?php echo Html::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
		'disabled'=>$editable ? false : 'disabled',
	));?></td>
	<td>
		<strong>
			<?php if($editable){
				echo Html::link($data['title'] ? $data['title'] : '--无标题--', array('admin/post/edit', array(
					'id'=>$data['id'],
				)));
			}else{
				echo Html::link($data['title'] ? $data['title'] : '--无标题--', 'javascript:;');
			}?>
		</strong>
		<div class="row-actions">
		<?php if($editable){
			if($data['deleted'] == 0){
				echo Html::link('编辑', array('admin/post/edit', array(
					'id'=>$data['id'],
				)), array(), true);
				echo Html::link('移入回收站', array('admin/post/delete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-red',
				), true);
			}else{
				echo Html::link('还原', array('admin/post/undelete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'undelete-post',
				), true);
				echo Html::link('永久删除', array('admin/post/remove', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'delete-post fc-red remove-link',
				), true);
			}
		}?>
		</div>
	</td>
	<?php if(in_array('main_category', $cols)){?>
	<td><?php echo Html::link($data['cat_title'], array('admin/post/index', array(
		'cat_id'=>$data['cat_id'],
	)));?></td>
	<?php }?>
	<?php if(in_array('category', $cols)){?>
	<td><?php
		$cats = Post::model()->getCats($data['id']);
		foreach($cats as $key => $cat){
			if($key){
				echo ', ';
			}
			echo Html::link($cat['title'], array('admin/post/index', array(
				'cat_id'=>$cat['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('tags', $cols)){?>
	<td><?php
		$tags = Post::model()->getTags($data['id']);
		foreach($tags as $key => $tag){
			if($key){
				echo ', ';
			}
			echo Html::link($tag['title'], array('admin/post/index', array(
				'tag_id'=>$tag['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td>
	<?php echo PostHelper::getStatus($data['status'], $data['deleted']);?>
	</td>
	<?php }?>
	<?php if(in_array('user', $cols)){?>
	<td><?php echo Html::encode($data[F::form('setting')->getData('display_name', 'username')])?></td>
	<?php }?>
	<?php if(in_array('views', $cols)){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
	<?php if(in_array('comments', $cols)){?>
	<td><?php echo $data['comments']?></td>
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
	<?php if(in_array('last_view_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['last_view_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['last_view_time']);
			}else{
				echo Date::format($data['last_view_time']);
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
	<?php if(in_array('sort', $cols)){?>
	<td><?php if($editable){
		echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
			'size'=>3,
			'maxlength'=>3,
			'data-id'=>$data['id'],
			'class'=>'form-control w50 post-sort',
		));
	}else{
		echo $data['sort'];
	}?></td>
	<?php }?>
</tr>