<?php
use fay\helpers\Html;
?>
<header class="g-hd">
	<div class="w1000">
		<div class="hd-search-bar">
			<form>
				<span><?php echo date('Y年m月d日')?></span>
				<span class="sep">|</span>
				<span><?php echo Html::inputText('q', '', array(
					'placeholder'=>'请输入关键词',
				))?></span>
			</form>
		</div>
	</div>
	<nav class="g-nav">
		<div class="w1000">
			<?php F::widget()->load('header-nav')?>
		</div>
	</nav>
</header>