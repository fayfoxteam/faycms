<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
?>
<div class="box right works">
	<h3 class="box-title">
		<span><?php echo HtmlHelper::encode($widget->config['title'])?></span>
	</h3>
	<div class="box-content">
		<ul class="cf">
		<?php foreach($posts as $p){
			echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
				'dw'=>198,
				'dh'=>156,
				'alt'=>HtmlHelper::encode($p['title']),
			)), array('works-'.$p['id']), array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($p['title']),
				'append'=>array(
					'tag'=>'span',
					'class'=>'zoom-bg',
					'text'=>'',
					'after'=>array(
						'tag'=>'span',
						'class'=>'zoom-icon',
						'text'=>'',
					),
				),
				'wrapper'=>'li',
			));
		}?>
		</ul>
	</div>
</div>