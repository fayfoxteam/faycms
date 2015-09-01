<?php
use fay\helpers\Html;
?>
<ul class="inner-post-list"><?php
	foreach($posts as $k => $p){
		echo Html::link('<span>'.Html::encode($p['title']).'</span>', array('post/'.$p['id']), array(
			'wrapper'=>'li',
			'before'=>array(
				'tag'=>'time',
				'text'=>date('Y-m-d', $p['publish_time']),
			),
			'encode'=>false,
			'title'=>Html::encode($p['title']),
		));
		
		if(($k + 1) % 5 == 0){
			echo Html::tag('li', array(
				'class'=>'separator',
			), '');
		}
	}
?></ul>