<?php
use fay\helpers\Html;
use fay\services\File;
?>
<div class="box right works">
	<h3 class="box-title">
		<span><?php echo Html::encode($config['title'])?></span>
	</h3>
	<div class="box-content">
		<ul class="cf">
		<?php foreach($posts as $p){
			echo Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>198,
				'dh'=>156,
				'alt'=>Html::encode($p['title']),
			)), array('works-'.$p['id']), array(
				'encode'=>false,
				'title'=>Html::encode($p['title']),
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