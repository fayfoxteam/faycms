<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\widget\Widget;
use fay\services\file\FileService;

class IndexController extends Widget{
	public function initConfig($config){
		empty($config['files']) && $config['files'] = array();
		isset($config['element_id']) || $config['element_id'] = '';
		isset($config['animSpeed']) || $config['animSpeed'] = 500;
		isset($config['pauseTime']) || $config['pauseTime'] = 5000;
		isset($config['effect']) || $config['effect'] = 'random';
		isset($config['directionNav']) || $config['directionNav'] = 1;
		isset($config['width']) || $config['width'] = 0;
		isset($config['height']) || $config['height'] = 0;
		
		return $this->config = $config;
	}
	
	public function getData(){
		$files = $this->config['files'];
		
		foreach($files as $k => $f){
			if((!empty($f['start_time']) && \F::app()->current_time < $f['start_time']) ||
				(!empty($f['end_time']) && \F::app()->current_time > $f['end_time'])){
				unset($files[$k]);
				continue;
			}
			
			$files[$k]['src'] = FileService::getUrl($f['file_id'], (empty($this->config['width']) && empty($this->config['height'])) ? FileService::PIC_ORIGINAL : FileService::PIC_RESIZE, array(
				'dw'=>empty($this->config['width']) ? false : $this->config['width'],
				'dh'=>empty($this->config['height']) ?  false : $this->config['height'],
			));
		}
		
		$config = $this->config;
		$config['files'] = $files;
		return $config;
	}
	
	public function index(){
		$data = $this->getData();
		
		$this->renderTemplate(array(
			'files'=>$data['files'],
		));
	}
}