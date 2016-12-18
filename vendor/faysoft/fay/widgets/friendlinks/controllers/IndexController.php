<?php
namespace fay\widgets\friendlinks\controllers;

use fay\widget\Widget;
use fay\services\Link;

class IndexController extends Widget{
	/**
	 * 初始化配置
	 * @param array $config
	 * @return array
	 */
	public function initConfig($config){
		//title
		if(empty($config['title'])){
			$config['title'] = '友情链接';
		}
		
		if(empty($config['number'])){
			$config['number'] = 5;
		}
		
		if(empty($config['cat_id'])){
			$config['cat_id'] = 0;
		}
		
		//设置模版
		empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
		
		return $this->config = $config;
	}
	
	public function index(){
		$links = Link::service()->get($this->config['cat_id'], $this->config['number']);
		
		//若内容可显示，则不显示该widget
		if(empty($links)){
			return;
		}
		
		$this->renderTemplate(array(
			'links'=>$links,
		));
	}
	
}