<?php
use fay\helpers\Html;
use fay\models\File;
?>
<section class="box" id="<?php echo $alias?>">
	<div class="box-title">
		<h2><?php echo $config['title']?></h2>
		<?php echo Html::link('More', array('news'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<?php foreach($posts as $p){
			if($p['thumbnail']){
				echo Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
					'dw'=>234,
					'dh'=>165,
					'alt'=>Html::encode($p['title']),
				)), $p['link'], array(
					'encode'=>false,
					'title'=>Html::encode($p['title']),
				));
				break;
			}
		}?>
		<ul>
		<?php foreach($posts as $p){
			echo Html::link($p['title'], $p['link'], array(
				'wrapper'=>'li',
			));
		}?>
		</ul>
	</div>
</section>