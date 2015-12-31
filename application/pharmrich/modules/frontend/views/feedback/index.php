<div class="centered-wrapper cf mt30">
	<section class="g-mn">
		<ul><?php $listview->showData()?></ul>
		<?php echo $listview->showPager()?>
	</section>
	<aside class="g-sd"><?php F::widget()->area('feedback-sidebar')?></aside>
</div>