<?php
use fay\helpers\Html;
use fay\services\FileService;
?>
<div class="box" id="<?php echo Html::encode($widget->alias);?>">
	<div class="box-title">
		<h3><?php
			echo Html::link('', array('cat/'.$widget->config['top']), array(
				'class'=>'more-link',
			));
			echo Html::encode($widget->config['title']);
		?></h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<div class="box-gallery-container">
					<ul class="box-gallery">
					<?php foreach($posts as $p){
						echo '<li>', Html::link(Html::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
							'dw'=>203,
							'dh'=>132,
						)), $p['post']['link'], array(
							'encode'=>false,
							'alt'=>$p['post']['title'],
							'title'=>$p['post']['title'],
						)), Html::link($p['post']['title'], $p['post']['link'], array(
							'class'=>'title',
						)), '</li>';
					}?>
					</ul>
				</div>
			</div>
		</div></div></div></div>
	</div>
</div>