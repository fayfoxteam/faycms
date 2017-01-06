<?php
use fay\helpers\Html;
use fay\services\FileService;

/**
 * @var $widget
 * @var $posts array
 */
?>
<section class="box" id="<?php echo $widget->alias?>">
	<div class="box-title">
		<h2><?php echo $widget->config['title']?></h2>
		<?php echo Html::link('More', array('news'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<?php foreach($posts as $p){
			if($p['post']['thumbnail']['id']){
				echo Html::link(Html::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
					'dw'=>234,
					'dh'=>165,
					'alt'=>Html::encode($p['post']['title']),
				)), $p['post']['link'], array(
					'encode'=>false,
					'title'=>Html::encode($p['post']['title']),
				));
				break;
			}
		}?>
		<ul>
		<?php foreach($posts as $p){
			echo Html::link($p['post']['title'], $p['post']['link'], array(
				'wrapper'=>'li',
			));
		}?>
		</ul>
	</div>
</section>