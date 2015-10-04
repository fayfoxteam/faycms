<?php
use fay\helpers\Html;

if(isset($values) && is_array($values)){
	foreach($values as $v){
		echo Html::tag('p', array(), $v);
	}
}