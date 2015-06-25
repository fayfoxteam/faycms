<?php
use fay\helpers\Html;

if(!empty($config['link'])){
	echo Html::link(Html::img($config['file_id'], 1, array(
		'width'=>$config['width'],
		'height'=>$config['height'],
	)), $config['link'], array(
		'encode'=>false,
		'target'=>isset($config['target']) ? $config['target'] : false,
		'title'=>false
	));
}else{
	echo Html::img($config['file_id'], 1, array(
		'width'=>$config['width'],
		'height'=>$config['height'],
	));
}