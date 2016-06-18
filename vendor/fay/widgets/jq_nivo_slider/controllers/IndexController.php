<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\widget\Widget;
use fay\models\File;

class IndexController extends Widget{
	public function getData($config){
		$data = empty($config['files']) ? array() : $config['files'];
		foreach($data as $k => $d){
			if((!empty($d['start_time']) && \F::app()->current_time < $d['start_time']) ||
				(!empty($d['end_time']) && \F::app()->current_time > $d['end_time'])){
				unset($data[$k]);
				continue;
			}
			$data[$k]['src'] = File::getUrl($d['file_id'], (empty($config['width']) && empty($config['height'])) ? File::PIC_ORIGINAL : File::PIC_RESIZE, array(
				'dw'=>empty($config['width']) ? false : $config['width'],
				'dh'=>empty($config['height']) ?  false : $config['height'],
			), true);
		}
		return $data;
	}
	
	public function index($config){
		if(empty($config)){
			$config = array();
		}
		empty($config['animSpeed']) && $config['animSpeed'] = 500;
		empty($config['pauseTime']) && $config['pauseTime'] = 5000;
		empty($config['effect']) && $config['effect'] = 'random';
		isset($config['directionNav']) || $config['directionNav'] = '1';
		
		$this->view->assign(array(
			'config'=>$config,
			'files'=>$this->getData($config),
			'alias'=>$this->alias,
			'_index'=>$this->_index,
		))->render();
	}
}