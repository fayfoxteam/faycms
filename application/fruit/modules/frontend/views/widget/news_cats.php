<?php
use fay\helpers\HtmlHelper;
?>
<aside class="box">
	<div class="box-title">
		<h3 class="sub-title"><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
	</div>
	<div class="box-content">
		<ul class="box-cats">
		<?php foreach($cats as $c){?>
			<li><?php echo HtmlHelper::link($c['title'], array(
				'news/'.$c['alias']
			))?></li>
		<?php }?>
		</ul>
	</div>
</aside>