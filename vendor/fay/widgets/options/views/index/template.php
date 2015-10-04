<?php
use fay\helpers\Html;

if(isset($data) && is_array($data)){
	foreach($data as $d){
		echo Html::tag('p', array(
			'prepend'=>array(
				'tag'=>'label',
				'text'=>$d['key'],
			)
		), $d['value']);
	}
}