<?php
/**
 * @var $widget fay\widgets\image\controllers\IndexController
 * @var $data array
 */
use fay\helpers\HtmlHelper;

if($data['file_id']){
	if(!empty($data['link'])){
		echo HtmlHelper::link(HtmlHelper::img($data['src']), $widget->config['link'], array(
			'encode'=>false,
			'target'=>isset($widget->config['target']) ? $widget->config['target'] : false,
			'title'=>false
		));
	}else{
		echo HtmlHelper::img($data['src']);
	}
}