<?php
use fay\helpers\Html;
use fay\models\Option;
?>
<div class="page-title">
	<div class="container">
		<h1><?php echo Html::encode($page['title'])?></h1>
		<div class="breadcrumbs">
			<ol>
				<li><?php echo Html::link(Option::get('site:sitename'), null)?></li>
				<li>关于我们</li>
			</ol>
		</div>
	</div>
</div>
<div class="container">
	<div class="g-mn">
		<h1 class="sec-title"><span><?php echo Html::encode($page['title'])?></span></h1>
		
		<div id="contact-page" class="clearfix">
			<?php echo $page['content']?>
		</div>
	</div>
</div>