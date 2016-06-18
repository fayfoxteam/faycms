<?php
namespace fay\widgets\image\controllers;

use fay\widget\Widget;
use fay\models\File;

class IndexController extends Widget{
	public function getData($config){
		$config['src'] = File::getUrl($config['file_id'], (empty($config['width']) && empty($config['height'])) ? File::PIC_ORIGINAL : File::PIC_RESIZE, array(
			'dw'=>empty($config['width']) ? false : $config['width'],
			'dh'=>empty($config['height']) ?  false : $config['height'],
		), true);
		
		return $config;
	}
	
	public function index($config){
		$this->view->alias = $this->alias;
		$this->view->config = $config;
		$this->view->render();
	}
}