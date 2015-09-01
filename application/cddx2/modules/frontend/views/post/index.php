<?php
use fay\helpers\Html;
?>
<div class="inner cf">
	<div class="breadcrumbs">
		<?php
		echo Html::link('网站首页', array('')),
			' &gt; ',
			Html::encode($cat['title']);
		?>
	</div>
	<div class="g-sd">
		<?php F::widget()->load('left-cats')?>
	</div>
	<div class="g-mn">
		<h1 class="sub-title"><?php echo Html::encode($cat['title'])?></h1>
		<?php F::widget()->load('post-list')?>
	</div>
</div>