<?php
use fay\helpers\Html;

echo Html::link('<span>'.Html::encode($data['title']).'</span>', array('post-'.$data['id']), array(
	'wrapper'=>'li',
	'before'=>array(
		'tag'=>'time',
		'text'=>date('Y-m-d', $data['publish_time']),
	),
	'encode'=>false,
	'title'=>Html::encode($data['title']),
));

if($index % 5 == 0){
	echo Html::tag('li', array(
		'class'=>'separator',
	), '');
}