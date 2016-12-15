<?php
/**
 * @var $widget fay\widgets\image\controllers\IndexController
 * @var $data array
 */
use fay\helpers\Html;

if($data['file_id']){
	if(!empty($data['link'])){
		echo Html::link(Html::img($data['src']), $widget->config['link'], array(
			'encode'=>false,
			'target'=>isset($widget->config['target']) ? $widget->config['target'] : false,
			'title'=>false
		));
	}else{
		echo Html::img($data['src']);
	}
}