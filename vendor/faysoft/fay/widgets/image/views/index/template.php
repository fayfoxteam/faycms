<?php
/**
 * @var array $config
 */
use fay\helpers\Html;

if($config['file_id']){
	if(!empty($config['link'])){
		echo Html::link(Html::img($config['file_id'], \fay\services\File::PIC_ORIGINAL, array(
			'width'=>$config['width'],
			'height'=>$config['height'],
		)), $config['link'], array(
			'encode'=>false,
			'target'=>isset($config['target']) ? $config['target'] : false,
			'title'=>false
		));
	}else{
		echo Html::img($config['file_id'], \fay\services\File::PIC_ORIGINAL, array(
			'width'=>$config['width'],
			'height'=>$config['height'],
		));
	}
}