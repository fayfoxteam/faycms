<?php
namespace fay\widgets\images\controllers;

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
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'files'=>isset($config['files']) ? $config['files'] : array(),
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'files'=>isset($config['files']) ? $config['files'] : array(),
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				$files = isset($config['files']) ? $config['files'] : array();
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
}