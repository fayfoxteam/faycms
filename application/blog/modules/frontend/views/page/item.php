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
	<?php echo F::app()->widget->render('profile')?>
	<?php echo F::app()->widget->render('contact')?>
	<?php echo F::app()->widget->load('categories')?>
</div>
<div class="clear"></div>