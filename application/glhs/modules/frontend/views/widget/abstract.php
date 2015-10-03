<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\Category;
use fay\helpers\String;

$cat = Category::model()->get($config['top']);
?>
<div class="teacher-list-container">
	<h2 class="en">teacher strength</h2>
	<h2><?php echo Html::encode($config['title'])?></h2>
	<div class="description">强大的师资是教学成果的保证</div>
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
	<?php echo String::nl2p(Html::encode($cat['description']))?>
</div>