<?php
use fay\helpers\Html;
?>
<ul class="inner-post-list"><?php
	foreach($posts as $k => $p){
		echo Html::link('<span>'.Html::encode($p['post']['title']).'</span>', array('post/'.$p['post']['id']), array(
			'wrapper'=>'li',
			'before'=>array(
				'tag'=>'time',
				'text'=>date('Y-m-d', $p['post']['publish_time']),
			),
			'encode'=>false,
			'title'=>Html::encode($p['post']['title']),
		));
		
		if(($k + 1) % 5 == 0){
			echo Html::tag('li', array(
				'class'=>'separator',
			), '');
		}
	}
?></ul>