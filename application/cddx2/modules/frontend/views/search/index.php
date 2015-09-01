<?php
use fay\helpers\Html;
?>
<div class="inner cf">
	<div class="breadcrumbs">
		<?php
		echo Html::link('网站首页', array('')),
			' &gt; 全站搜索';
		?>
	</div>
	<div class="g-sd">
		<?php F::widget()->load('left-cats')?>
	</div>
	<div class="g-mn">
		<h1 class="sub-title">搜索关键词：<?php echo Html::encode($keywords)?></h1>
		<ul class="inner-post-list"><?php $listview->showData()?></ul>
		<?php $listview->showPager()?>
	</div>
</div>