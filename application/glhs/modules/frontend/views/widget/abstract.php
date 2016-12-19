<?php
use fay\helpers\Html;
use fay\services\File;
use fay\services\Category;
use fay\helpers\StringHelper;

$cat = Category::service()->get($widget->config['top']);
?>
<div class="teacher-list-container">
	<h2><?php echo Html::encode($widget->config['title'])?></h2>
	<div class="teacher-list">
		<ul class="cf"><?php foreach($posts as $p){
			echo Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>180,
				'dh'=>228,
				'alt'=>Html::encode($p['title']),
				'after'=>array(
					'tag'=>'span',
					'text'=>Html::encode($p['title']),
				),
			)), array('teacher'), array(
				'encode'=>false,
				'title'=>Html::encode($p['title']),
				'wrapper'=>'li',
			));
		}?></ul>
	</div>
</div>
<div class="more-description">
	<?php echo StringHelper::nl2p(Html::encode($cat['description']))?>
</div>