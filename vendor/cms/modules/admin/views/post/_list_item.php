<?php
use fay\helpers\Html;
use fay\services\Post;
use fay\helpers\Date;
use cms\helpers\PostHelper;
use fay\services\File;
use fay\services\post\Tag as PostTag;
use fay\services\post\Category as PostCategory;

//分类权限判断
$editable = PostCategory::service()->isAllowedCat($data['cat_id']);
?>
<tr valign="top" id="post-<?php echo $data['id']?>">
	<td><?php echo Html::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
		'disabled'=>$editable ? false : 'disabled',
	));?></td>
	<?php if(in_array('id', $cols)){?>
	<td><?php echo $data['id']?></td>
	<?php }?>
	<?php if(in_array('thumbnail', $cols)){?>
	<td class="align-center"><?php
		if($data['thumbnail']){
			echo Html::link(Html::img($data['thumbnail'], File::PIC_THUMBNAIL, array(
				'width'=>60,
				'height'=>60,
				'spare'=>'default',
			)), File::getUrl($data['thumbnail']), array(
				'class'=>'file-image fancybox-image',
				'encode'=>false,
				'title'=>Html::encode($data['title']),
			));
		}else{
			echo Html::img($data['thumbnail'], File::PIC_THUMBNAIL, array(
				'width'=>60,
				'height'=>60,
				'spare'=>'default',
			));
		}
	?></td>
	<?php }?>
	<td>
		<strong><?php
			if($editable && !$data['deleted']){
				echo Html::link($data['title'] ? $data['title'] : '--无标题--', array('admin/post/edit', array(
					'id'=>$data['id'],
				)));
			}else{
				echo Html::link($data['title'] ? $data['title'] : '--无标题--', 'javascript:;');
			}
		?></strong>
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
		$cats = Post::service()->getCats($data['id']);
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
		$tags = PostTag::service()->get($data['id']);
		foreach($tags as $key => $tag){
			if($key){
				echo ', ';
			}
			echo Html::link($tag['tag']['title'], array('admin/post/index', array(
				'tag_id'=>$tag['tag']['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td><?php echo PostHelper::getStatus($data['status'], $data['deleted']);?></td>
	<?php }?>
	<?php if(in_array('user', $cols)){?>
	<td><?php
		echo Html::link($data[F::form('setting')->getData('display_name', 'username')], array(
			'admin/post/index', array(
				'keywords_field'=>'p.user_id',
				'keywords'=>$data['user_id'],
			),
		));
	?></td>
	<?php }?>
	<?php if(in_array('views', $cols)){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
	<?php if(in_array('real_views', $cols)){?>
	<td><?php echo $data['real_views']?></td>
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