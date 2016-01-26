<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\core\Widget;
use fay\models\File;

class IndexController extends Widget{
	public function getData($config){
		$data = empty($config['files']) ? array() : $config['files'];
		foreach($data as &$d){
			$d['src'] = File::getUrl($d['file_id'], (empty($config['width']) && empty($config['height'])) ? File::PIC_ORIGINAL : File::PIC_RESIZE, array(
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
			'alias'=>$this->alias,
			'_index'=>$this->_index,
		))->render();
	}
}