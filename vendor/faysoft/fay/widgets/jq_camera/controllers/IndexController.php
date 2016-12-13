<?php
namespace fay\widgets\jq_camera\controllers;

use fay\widget\Widget;
use fay\services\File;

class IndexController extends Widget{
	public function getData(){
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
	
	public function index(){
		if(empty($config)){
			$config = array();
		}
		empty($config['height']) && $config['height'] = 450;
		empty($config['transPeriod']) && $config['transPeriod'] = 800;
		empty($config['time']) && $config['time'] = 5000;
		empty($config['fx']) && $config['fx'] = 'random';
		
		$this->view->assign(array(
			'config'=>$config,
			'files'=>$this->getData($config),
			'alias'=>$this->alias,
			'_index'=>$this->_index,
		))->render();
	}
}