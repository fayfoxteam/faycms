<?php
/**
 * @var $post
 */
$props = \fay\helpers\ArrayHelper::column($post['props'], null, 'alias');

\F::app()->layout->assign(array(
	'title'=>$post['post']['title'],
	'subtitle'=>$props['subtitle']['value'],
));
?>
<main class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="post-meta">
				<span class="time"><?php echo date('F j, Y', $post['post']['publish_time'])?></span>
				<span class="dot"> · </span>
				<?php foreach($post['tags'] as $tag){?>
					<a class="tag"><?php echo \fay\helpers\Html::encode($tag['tag']['title'])?></a>
				<?php }?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="post-content">
				<?php echo $post['post']['content']?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 center">
			<div class="separator-container">
				<div class="separator"></div>
			</div>
			<a href="<?php echo $this->url()?>#section-contact" class="btn btn-blue">CONTACT US</a>
		</div>
	</div>
	<div class="row">
		<nav class="cf post-nav">
			<div class="col-md-6 previous">
				<a href="<?php echo $this->url('post/'.$post['nav']['prev']['id'])?>">
					<i class="fa fa-angle-left"></i>
					Previous
				</a>
				<a href="<?php echo $this->url('post/'.$post['nav']['prev']['id'])?>" class="post-title">
					<?php echo \fay\helpers\Html::encode($post['nav']['prev']['title'])?>
				</a>
			</div>
			<div class="col-md-6 next">
				<a href="<?php echo $this->url('post/'.$post['nav']['next']['id'])?>">
					Next
					<i class="fa fa-angle-right"></i>
				</a>
				<a href="<?php echo $this->url('post/'.$post['nav']['next']['id'])?>" class="post-title">
					<?php echo \fay\helpers\Html::encode($post['nav']['next']['title'])?>
				</a>
			</div>
		</nav>
	</div>
</main>