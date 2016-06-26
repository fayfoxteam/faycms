<?php
use fay\helpers\Html;
use fay\services\File;
use siwi\helpers\FriendlyLink;
use siwi\models\Post;

$type = Post::model()->getType($data['cat_id']);
$type_title = '';
switch($type){
	case 'blog':
		$type_title = '博客';
	break;
	case 'material':
		$type_title = '素材';
	break;
	case 'work':
		$type_title = '作品';
	break;
}
?>
<article class="<?php if($index % 4 == 0)echo 'last'?>">
	<?php
		echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
			'dw'=>283,
			'dh'=>217,
			'alt'=>Html::encode($data['title']),
			'title'=>Html::encode($data['title']),
			'spare'=>'default',
			'class'=>'thumbnail',
		)), array('material/'.$data['id']) ,array(
			'encode'=>false,
			'title'=>Html::encode($data['title']),
			'prepend'=>'<span class="overlay"></span>',
		));
	
		echo Html::link('', array($type), array(
			'class'=>'type type-'.$type,
			'title'=>$type_title,
		));
	?>
	<div class="meta">
		<h3><?php echo Html::link($data['title'], array("{$type}/{$data['id']}"), array(
			'title'=>Html::encode($data['title']),
			'encode'=>false,
			'target'=>'_blank',
		))?></h3>
		<p class="cat">
			<?php echo Html::link($data['parent_cat_title'], FriendlyLink::get($type, $data['parent_cat_id']))?>
			-
			<?php echo Html::link($data['cat_title'], FriendlyLink::get($type, $data['parent_cat_id'], $data['cat_id']))?>
		</p>
	</div>
	<div class="cover">
		<?php echo Html::link(Html::img($data['avatar'], File::PIC_THUMBNAIL, array(
			'alt'=>Html::encode($data['nickname']),
			'title'=>Html::encode($data['nickname']),
			'spare'=>'avatar',
			'class'=>'avatar',
		)), array('u/'.$data['user_id']) ,array(
			'encode'=>false,
			'title'=>Html::encode($data['title']),
			'target'=>'_blank',
		));?>
		<?php echo Html::link($data['nickname'], array('u/'.$data['user_id']), array(
			'class'=>'nickname',
			'target'=>'_blank',
		))?>
		<div class="cover-meta">
			<span class="views"><i class="icon-eye"></i><?php echo $data['views']?></span>
			<span class="comments"><i class="icon-comment"></i><?php echo $data['comments']?></span>
			<span class="links"><i class="icon-heart"></i><?php echo $data['likes']?></span>
		</div>
	</div>
</article>