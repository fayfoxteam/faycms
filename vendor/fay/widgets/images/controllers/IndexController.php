<?php
namespace fay\widgets\images\controllers;

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
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'files'=>$this->getData($config),
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'files'=>$this->getData($config),
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				$files = $this->getData($config);
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
}