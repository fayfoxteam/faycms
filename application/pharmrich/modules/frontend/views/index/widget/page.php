<?php
use fay\helpers\Html;
use fay\models\File;
?>
<section class="box" id="<?php echo $alias?>">
	<div class="box-title">
		<h2><?php echo $page['title']?></h2>
		<?php echo Html::link('More', array($page['alias']), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content"><?php
		if($page['thumbnail']){
			echo Html::link(Html::img($page['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>156,
				'dh'=>110,
				'alt'=>Html::encode($page['title']),
			)), array($page['alias']), array(
				'encode'=>false,
				'title'=>Html::encode($page['title']),
			));
		}
		echo nl2br($page['abstract']);
	?></div>
</section>