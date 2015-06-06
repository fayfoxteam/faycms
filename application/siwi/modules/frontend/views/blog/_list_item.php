<?php
use fay\models\File;
use fay\helpers\Html;
use fay\helpers\String;
use fay\helpers\Date;
?>
<article class="clearfix">
	<div class="thumbnail"><?php 
		if($data['thumbnail']){
			echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>300,
				'dh'=>230,
				'alt'=>Html::encode($data['title']),
				'title'=>Html::encode($data['title']),
			)), array('blog/'.$data['id']) ,array(
				'encode'=>false,
				'title'=>Html::encode($data['title']),
			));
		}else{
			echo Html::link("<img src='{$this->url()}images/no-image.jpg' width='300' height='230' />", array('blog/'.$data['id']) ,array(
				'encode'=>false,
				'title'=>Html::encode($data['title']),
			));
		}
	?></div>
	<header>
		<h1><?php echo Html::link(String::niceShort($data['title'], 38, true), array('blog/'.$data['id']), array(
			'title'=>Html::encode($data['title']),
			'encode'=>false,
		))?></h1>
		<div class="meta">
			<span class="author">
				作者：
				<?php echo Html::link($data['nickname'], array('u/'.$data['user_id']))?>
			</span>
			|
			<span class="date"><?php echo Date::niceShort($data['publish_time'])?></span>
			|
			<span class="comment"><?php echo $data['comments']?> 评论</span>
		</div>
	</header>
	<div class="abstract"><?php echo Html::encode($data['abstract'])?></div>
	<?php echo Html::link('阅读全文', array('blog/'.$data['id']), array(
		'class'=>'btn-blue more-link',
	))?>
</article>