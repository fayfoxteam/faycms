<?php
use fay\helpers\Html;
use fay\models\File;
use fay\helpers\Date;
?>
<article>
	<header>
		<h2><?php echo Html::link($data['title'], array('news/'.$data['id']))?></h2>
		<div class="meta">
			<span>发布于：<?php
				echo Date::niceShort($data['publish_time'])
			?></span>
			<span>阅读数：<?php echo $data['views']?></span>
		</div>
	</header>
	<?php if($data['thumbnail']){?>
	<figure><?php
		echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
			'dw'=>748,
			'dh'=>286,
			'alt'=>Html::encode($data['title']),
		)), array('news/'.$data['id']), array(
			'encode'=>false,
			'title'=>Html::encode($data['title']),
		));
	?></figure>
	<?php }?>
	<div class="introtext">
		<?php echo Html::encode($data['abstract'])?>
	</div>
	<?php echo Html::link('阅读全文', array('news/'.$data['id']), array(
		'class'=>'more',
	))?>
</article>