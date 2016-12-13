<?php
namespace fay\widgets\image\controllers;

use fay\widget\Widget;
use fay\services\File;

class IndexController extends Widget{
	public function getData(){
		$config = $this->initConfig($config);
		
		$config['src'] = File::getUrl($config['file_id'], (empty($config['width']) && empty($config['height'])) ? File::PIC_ORIGINAL : File::PIC_RESIZE, array(
			'dw'=>empty($config['width']) ? false : $config['width'],
			'dh'=>empty($config['height']) ?  false : $config['height'],
		));
		
		return $config;
	}
	
	public function index(){
		$config = $this->initConfig($config);
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
	
	/**
	 * 初始化配置
	 * @param array $config
	 * @return array
	 */
	protected function initConfig($config){
		//默认表单元素
		isset($config['file_id']) || $config['file_id'] = '0';
		
		return $this->config = $config;
	}
}