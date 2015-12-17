<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="page-title">
	<h1><?php echo Html::encode($post['post']['title'])?></h1>
</div>
<div class="meta">
	<?php echo date('d M Y', $post['post']['publish_time'])?>
	/
	<span class="fc-red"><?php echo $post['post']['views']?> Views</span>
	/
	<?php echo Html::link($post['post']['cat_title'], array('product/' . $post['post']['cat_alias']))?>
</div>
<div class="page-content cf"><?php echo $post['post']['content']?></div>
<?php if($post['files']){?>
	<h6>PHOTO GALLERY</h6>
	<ul class="post-gallery">
	<?php foreach($post['files'] as $f){?>
		<?php if(!$f['is_image'])continue;//不是图片无法显示，直接跳过?>
		<li>
			<a href="<?php echo $f['url']?>">
				<span class="item-on-hover"><span class="hover-image"></span></span>
				<?php echo Html::img($f['file_id'], File::PIC_RESIZE, array(
					'dw'=>200,
					'dh'=>200,
				))?>
			</a>
		</li>
	<?php }?>
	</ul>
<?php }?>