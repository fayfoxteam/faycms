<?php
use fay\helpers\Html;

if(isset($data) && is_array($data)){
	foreach($data as $v){
		echo Html::tag('p', array(), $v);
	}
}