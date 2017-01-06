<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
?>
<ul class="clearfix">
<?php foreach($posts as $post){?>
	<li>
		<div class="inner">
			<figure><?php echo HtmlHelper::img($post['post']['thumbnail'], FileService::PIC_RESIZE, array(
				'dw'=>362,
				'dh'=>240,
			))?></figure>
			<div class="mask"></div>
			<h2><?php echo HtmlHelper::link($post['post']['title'], array(
				'product/'.$post['post']['id']
			))?></h2>
			<div class="abstract"><?php echo HtmlHelper::encode($post['post']['abstract'])?></div>
		</div>
	</li>
<?php }?>
</ul>