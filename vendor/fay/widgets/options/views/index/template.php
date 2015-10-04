<?php
use fay\helpers\Html;

echo Html::tag('h3', array(), Html::encode($title));
if(isset($data) && is_array($data)){
	foreach($data as $d){
		echo Html::tag('p', array(
			'prepend'=>array(
				'tag'=>'label',
				'text'=>Html::encode($d['key']),
			)
		), Html::encode($d['value']));
	}
}