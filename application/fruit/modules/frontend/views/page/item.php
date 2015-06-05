<?php
use fay\helpers\Html;
?>
<div class="g-con">
	<div class="g-mn">
		<article class="page-item">
			<header>
				<h1><?php echo Html::encode($page['title'])?></h1>
			</header>
			<div class="page-content">
				<?php echo $page['content'];?>
			</div>
		</article>
	</div>
</div>