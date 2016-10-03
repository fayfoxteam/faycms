<?php
use fay\helpers\Html;

echo Html::tag('h3', array(), Html::encode($title));
if(isset($data) && is_array($data)){
	foreach($data as $v){
		echo Html::tag('p', array(), Html::encode($v));
	}
}