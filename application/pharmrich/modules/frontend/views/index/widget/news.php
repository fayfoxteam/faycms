<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;

/**
 * @var $widget
 * @var $posts array
 */
?>
<section class="box" id="<?php echo $widget->alias?>">
	<div class="box-title">
		<h2><?php echo $widget->config['title']?></h2>
		<?php echo HtmlHelper::link('More', array('news'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<?php foreach($posts as $p){
			if($p['post']['thumbnail']['id']){
				echo HtmlHelper::link(HtmlHelper::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
					'dw'=>234,
					'dh'=>165,
					'alt'=>HtmlHelper::encode($p['post']['title']),
				)), $p['post']['link'], array(
					'encode'=>false,
					'title'=>HtmlHelper::encode($p['post']['title']),
				));
				break;
			}
		}?>
		<ul>
		<?php foreach($posts as $p){
			echo HtmlHelper::link($p['post']['title'], $p['post']['link'], array(
				'wrapper'=>'li',
			));
		}?>
		</ul>
	</div>
</section>