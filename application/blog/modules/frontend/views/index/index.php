<div class="col-main">
	<section class="post-list">
	<?php if(isset($subtitle)){?>
		<article class="post-list-header"><?php echo $subtitle?></article>
	<?php }?>
		<?php $listview->showData()?>
	</section>
	<?php echo $listview->showPager()?>
</div>
<div class="col-side">
	<?php echo F::widget()->render('profile')?>
	<?php echo F::widget()->render('contact')?>
	<?php echo F::widget()->load('recent_posts')?>
	<div class="fixed-content">
		<?php echo F::widget()->load('categories')?>
	</div>
</div>
<div class="clear"></div>