<?php
use fay\helpers\HtmlHelper;

foreach($cats as $k => $c){
	echo HtmlHelper::link($c['title'], $c['link'], array(
		'before'=>$k ? ', ' : ''
	));
}