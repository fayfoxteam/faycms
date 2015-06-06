<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="box" id="<?php echo Html::encode($alias);?>">
	<div class="box-title">
		<h3><?php
			echo Html::link('', array('cat/'.$data['top']), array(
				'class'=>'more-link',
			));
			echo Html::encode($data['title']);
		?></h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<div class="box-gallery-container">
					<ul class="box-gallery">
					<?php foreach($posts as $p){
						echo '<li>', Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
							'dw'=>203,
							'dh'=>132,
						)), array(str_replace('{$id}', $p['id'], $data['uri'])), array(
							'encode'=>false,
							'alt'=>$p['title'],
							'title'=>$p['title'],
						)), Html::link($p['title'], array(str_replace('{$id}', $p['id'], $data['uri'])), array(
							'class'=>'title',
						)), '</li>';
					}?>
					</ul>
				</div>
			</div>
		</div></div></div></div>
	</div>
</div>