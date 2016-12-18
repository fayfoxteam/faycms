<?php
use fay\helpers\Html;

if(!empty($widget->config['title'])){
	echo Html::tag('h3', array(), Html::encode($widget->config['title']));
}

foreach($widget->config['data'] as $d){
	echo Html::tag('p', array(
		'prepend'=>array(
			'tag'=>'label',
			'text'=>Html::encode($d['key']),
		)
	), Html::encode($d['value']));
}