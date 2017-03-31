<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
use fay\services\CategoryService;
use fay\helpers\StringHelper;

$cat = CategoryService::service()->get($widget->config['top']);
?>
<div class="teacher-list-container">
	<h2><?php echo HtmlHelper::encode($widget->config['title'])?></h2>
	<div class="teacher-list">
		<ul class="cf"><?php foreach($posts as $p){
			echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
				'dw'=>180,
				'dh'=>228,
				'alt'=>HtmlHelper::encode($p['title']),
				'after'=>array(
					'tag'=>'span',
					'text'=>HtmlHelper::encode($p['title']),
				),
			)), array('teacher'), array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($p['title']),
				'wrapper'=>'li',
			));
		}?></ul>
	</div>
</div>
<div class="more-description">
	<?php echo StringHelper::nl2p(HtmlHelper::encode($cat['description']))?>
</div>