<?php
/**
 * @var \fay\common\ListView $listview
 */
?>
<div class="col-main">
	<section class="post-list">
	<?php if(isset($subtitle)){?>
		<article class="post-list-header"><?php echo $subtitle?></article>
	<?php }?>
		<?php $listview->showData()?>
	</section>
	<?php $listview->showPager()?>
</div>
<div class="col-side">
	<?php F::widget()->render('profile')?>
	<?php F::widget()->render('contact')?>
	<?php F::widget()->load('recent_posts')?>
	<div class="fixed-content">
		<?php F::widget()->load('categories')?>
	</div>
</div>
<div class="clear"></div>