<?php
use fay\helpers\HtmlHelper;

if(!empty($widget->config['title'])){
	echo HtmlHelper::tag('h3', array(), HtmlHelper::encode($widget->config['title']));
}

foreach($widget->config['data'] as $d){
	echo HtmlHelper::tag('p', array(
		'prepend'=>array(
			'tag'=>'label',
			'text'=>HtmlHelper::encode($d['key']),
		)
	), HtmlHelper::encode($d['value']));
}