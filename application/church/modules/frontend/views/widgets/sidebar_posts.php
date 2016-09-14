<?php
use fay\helpers\Html;

/**
 * @var array $posts
 */
?>
<?php dump($posts)?>
<div class="widget">
	<h5 class="widget-title"><?php echo Html::encode($config['title'])?></h5>
	<?php foreach($posts as $p){?>
		<article>
			<div class="post-thumb">
				<a href="<?php echo $p['post']['link']?>"><?php
					echo Html::img($p['post'])
				?></a>
			</div>
			<div class="post-container">
				<h5 class="post-title">
					<a href="">这是一个文章标题</a>
				</h5>
				<div class="post-meta">
					<span class="post-meta-category">分类1</span>
					<time class="post-meta-time">3天前</time>
				</div>
			</div>
		</article>
	<?php }?>
	<article>
		<div class="post-thumb">
			<a href=""><img src="http://55.fayfox.com/fayfox/file/pic/f/10000?t=4&dw=60&dh=60" /></a>
		</div>
		<div class="post-container">
			<h5 class="post-title">
				<a href="">这是一个文章标题</a>
			</h5>
			<div class="post-meta">
				<span class="post-meta-category">分类1</span>
				<time class="post-meta-time">3天前</time>
			</div>
		</div>
	</article>
</div>