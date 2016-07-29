<?php
namespace fay\widgets\images\controllers;

use fay\widget\Widget;
use fay\services\File;

class IndexController extends Widget{
	public function getData($config){
		$files = empty($config['files']) ? array() : $config['files'];
		foreach($files as $k => $d){
			if((!empty($d['start_time']) && \F::app()->current_time < $d['start_time']) ||
				(!empty($d['end_time']) && \F::app()->current_time > $d['end_time'])){
				unset($files[$k]);
				continue;
			}
			$files[$k]['src'] = File::getUrl($d['file_id'], (empty($config['width']) && empty($config['height'])) ? File::PIC_ORIGINAL : File::PIC_RESIZE, array(
				'dw'=>empty($config['width']) ? false : $config['width'],
				'dh'=>empty($config['height']) ?  false : $config['height'],
			));
		}
		
		$files = array_values($files);
		
		if(!empty($config['random'])){
			shuffle($files);
		}
		
		if(!empty($config['limit'])){
			$files = array_slice($files, 0, $config['limit']);
		}
		
		return array(
			'title'=>empty($config['title']) ? '' : $config['title'],
			'files'=>$files
		);
	}
	
	public function index($config){
		$data = $this->getData($config);
		$title = empty($data['title']) ? array() : $data['title'];
		$files = empty($data['files']) ? array() : $data['files'];
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'title'=>$title,
				'files'=>$files,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'title'=>$title,
					'files'=>$files,
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
}