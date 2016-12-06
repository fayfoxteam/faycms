<?php
namespace fay\widgets\contact\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	/**
	 * 配置信息
	 */
	private $config;
	
	public function getData($config){
		$this->initConfig($config);
	}
	
	public function index($config){
		$this->initConfig($config);
		
		//template
		if(empty($this->config['template'])){
			$this->view->render('template', array(
				'config'=>$this->config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['template'])){
				\F::app()->view->renderPartial($this->config['template'], array(
					'config'=>$this->config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$this->config['template'].'<?php ');
			}
		}
	}
	
	/**
	 * 初始化配置
	 * @param array $config
	 */
	private function initConfig($config){
		//默认表单元素
		isset($config['elements']) || $config['elements'] = array(
			'name', 'content', 'mobile',
		);
		
		//默认必选项
		isset($config['required']) || $config['required'] = array(
			'name', 'content', 'mobile',
		);
		
		//默认标签
		isset($config['label']) || $config['label'] = array(
			'name' => '称呼',
			'email' => '邮箱',
			'content' => '内容',
			'mobile' => '电话',
			'title' => '标题',
			'country' => '国家',
		);
		
		//默认占位符
		isset($config['placeholder']) || $config['placeholder'] = $config['label'];
		
		//提交按钮文案
		empty($config['submit_text']) && $config['submit_text'] = '发送';
		
		$this->config = $config;
	}
}