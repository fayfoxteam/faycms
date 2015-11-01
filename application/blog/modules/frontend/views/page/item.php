<div class="col-main">
	<article class="post-item">
		<header>
			<h1><?php echo $page['title']?></h1>
		</header>
		<div class="post-content">
			<?php echo stripslashes($page['content'])?>
		</div>
	</article>
</div>
<div class="col-side">
	<?php echo F::widget()->render('profile')?>
	<?php echo F::widget()->render('contact')?>
	<?php echo F::widget()->load('categories')?>
</div>
<div class="clear"></div>