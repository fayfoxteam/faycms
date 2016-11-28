<?php
/**
 * @var $config
 * @var $posts
 */

//获取分类描述
$cat = \fay\services\Category::service()->get($config['cat_id'], 'description');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="title-group">
				<h2 class="title"><?php echo \fay\helpers\Html::encode($config['title'])?></h2>
				<div class="description">
					<p><?php echo \fay\helpers\Html::encode($cat['description'])?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row question-list">
		<div class="col-md-12">
			<?php foreach($posts as $k => $p){?>
				<article>
					<strong>Q<?php echo $k+1?> - <?php echo \fay\helpers\Html::encode($p['post']['title'])?></strong>
					<?php
						$post = \fay\services\Post::service()->get($p['post']['id'], 'content');
						echo $post['post']['content'];
					?>
				</article>
			<?php }?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 center">
			<div class="separator-container">
				<div class="separator"></div>
			</div>
			<a href="javascript:;" class="btn btn-transparent">Contact For More Questions?</a>
		</div>
	</div>
</div>