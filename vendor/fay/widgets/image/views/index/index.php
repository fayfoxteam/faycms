<?php
use fay\helpers\Html;

if(!empty($data['link'])){
	echo Html::link(Html::img($data['file_id'], 1, array(
		'width'=>$data['width'],
		'height'=>$data['height'],
	)), $data['link'], array(
		'encode'=>false,
		'target'=>isset($data['target']) ? $data['target'] : false,
		'title'=>false
	));
}else{
	echo Html::img($data['file_id'], 1, array(
		'width'=>$data['width'],
		'height'=>$data['height'],
	));
}