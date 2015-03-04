<div class="col-main">
	<section class="post-list">
	<?php if(isset($subtitle)){?>
		<article class="post-list-header"><?php echo $subtitle?></article>
	<?php }?>
		<?php $listview->showData()?>
	</section>
	<?php echo $listview->showPage()?>
</div>
<div class="col-side">
	<?php echo F::app()->widget->render('profile')?>
	<?php echo F::app()->widget->render('contact')?>
	<?php echo F::app()->widget->load('recent_posts')?>
	<div class="fixed-content">
		<?php echo F::app()->widget->load('categories')?>
	</div>
</div>
<div class="clear"></div>