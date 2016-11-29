<?php
use fay\helpers\Html;

foreach($cats as $k => $c){
	echo Html::link($c['title'], $c['link'], array(
		'before'=>$k ? ', ' : ''
	));
}