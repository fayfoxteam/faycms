<?php
use fay\helpers\Html;
use fay\helpers\Date;
use cms\helpers\FeedHelper;
use fay\models\tables\Roles;
use fay\models\feed\Tag as FeedTag;

/**
 * 超级管理员或未开启分类权限或当前用户有此分类操作权限，则文章可编辑
 */
if(in_array(Roles::ITEM_SUPER_ADMIN, F::session()->get('user.roles')) ||
	!F::app()->role_cats ||
	in_array($data['cat_id'], F::session()->get('role_cats', array()))){
	/**
	 * 是否有权限编辑（此处验证的是分类权限）
	 */
	$editable = true;
}else{
	$editable = false;
}
?>
<tr valign="top" id="feed-<?php echo $data['id']?>">
	<td><?php echo Html::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
		'disabled'=>$editable ? false : 'disabled',
	));?></td>
	<?php if(in_array('id', $cols)){?>
	<td><?php echo $data['id']?></td>
	<?php }?>
	<td>
		<strong><?php
			if($editable){
				echo Html::link($data['content'], array('admin/feed/edit', array(
					'id'=>$data['id'],
				)));
			}else{
				echo Html::link($data['content'], 'javascript:;');
			}
		?></strong>
		<div class="row-actions">
		<?php if($editable){
			if($data['deleted'] == 0){
				echo Html::link('编辑', array('admin/feed/edit', array(
					'id'=>$data['id'],
				)), array(), true);
				echo Html::link('移入回收站', array('admin/feed/delete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-red',
				), true);
			}else{
				echo Html::link('还原', array('admin/feed/undelete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'undelete-feed',
				), true);
				echo Html::link('永久删除', array('admin/feed/remove', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'delete-feed fc-red remove-link',
				), true);
			}
		}?>
		</div>
	</td>
	<?php if(in_array('tags', $cols)){?>
	<td><?php
		$tags = FeedTag::model()->get($data['id']);
		foreach($tags as $key => $tag){
			if($key){
				echo ', ';
			}
			echo Html::link($tag['title'], array('admin/feed/index', array(
				'tag_id'=>$tag['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td><?php echo FeedHelper::getStatus($data['status'], $data['deleted']);?></td>
	<?php }?>
	<?php if(in_array('user', $cols)){?>
	<td><?php
		echo Html::link($data[F::form('setting')->getData('display_name', 'nickname')], array(
			'admin/feed/index', array(
				'keywords_field'=>'p.user_id',
				'keywords'=>$data['user_id'],
			),
		));
	?></td>
	<?php }?>
	<?php if(in_array('comments', $cols)){?>
	<td><?php echo $data['comments']?></td>
	<?php }?>
	<?php if(in_array('real_comments', $cols)){?>
	<td><?php echo $data['real_comments']?></td>
	<?php }?>
	<?php if(in_array('likes', $cols)){?>
	<td><?php echo $data['likes']?></td>
	<?php }?>
	<?php if(in_array('real_likes', $cols)){?>
	<td><?php echo $data['real_likes']?></td>
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
	<?php if(in_array('sort', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['sort'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['sort']);
			}else{
				echo Date::format($data['sort']);
			}?>
		</abbr>
	</td>
	<?php }?>
</tr>