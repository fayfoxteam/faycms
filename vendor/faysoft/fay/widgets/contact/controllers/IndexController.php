<?php
namespace fay\widgets\contact\controllers;

use fay\core\Response;
use fay\services\Contact;
use fay\widget\Widget;

class IndexController extends Widget{
	/**
	 * 配置信息
	 */
	private $config;
	
	public function getData($config){
		$this->initConfig($config);
	}
	
	public function send($config){
		$this->initConfig($config);
		$this->initForm();
		
		//表单验证
		if($this->form('widget_contact')->check()){
			Contact::service()->create($this->form('widget_contact')->getAllData());
			Response::notify('success', 'Message has been send.');
		}else{
			Response::notify('error', $this->form()->getFirstError());
		}
	}
	
	public function index($config){
		$this->initConfig($config);
		$this->initForm();
		
		//重新组织配置信息，传递给前端
		$frontend_config = array(
			'elements'=>array(),
			'submit_text'=>$this->config['submit_text'],
			'submit_success'=>$this->config['submit_success'],
			'submit_btn_class'=>$this->config['submit_btn_class'],
		);
		foreach($this->config['elements'] as $e){
			$frontend_config['elements'][] = array(
				'name'=>$e,
				'label'=>isset($this->config['label'][$e]) ? $this->config['label'][$e] : '',
				'placeholder'=>isset($this->config['placeholder'][$e]) ? $this->config['placeholder'][$e] : '',
			);
		}
		
		//template
		if(empty($this->config['template'])){
			$this->view->render('template', array(
				'config'=>$frontend_config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['template'])){
				\F::app()->view->renderPartial($this->config['template'], array(
					'config'=>$frontend_config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				$config = $frontend_config;
				eval('?>'.$this->config['template'].'<?php ');
			}
		}
	}
	
	/**
	 * 初始化表单验证
	 * @throws \fay\core\Exception
	 */
	private function initForm(){
		$this->form('widget_contact')->setData($this->input->post());
		foreach($this->config['elements'] as $element){
			if($element == 'email'){
				$this->form('widget_contact')
					->setRule(array($element, 'email', array(
						'message'=>$this->config['format_message'][$element],
					)));
			}
			if(isset($this->config['require_message'][$element])){
				$this->form('widget_contact')
					->setRule(array($element, 'required', array(
						'message'=>$this->config['require_message'][$element],
					)));
			}
			
			$this->form('widget_contact')
				->setFilters(array(
					$element=>'trim',
				))->setLabels(array(
					$element=>!empty($this->config['label'][$element]) ? $this->config['label'][$element] : ucfirst($element),
				));
		}
	}
	
	/**
	 * 初始化配置
	 * @param array $config
	 */
	private function initConfig($config){
		//默认表单元素
		isset($config['elements']) || $config['elements'] = array(
			'name', 'email', 'content',
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
		
		//提交按钮CSS类
		isset($config['submit_btn_class']) || $config['submit_btn_class'] = 'btn';
		
		//提交成功文案
		empty($config['submit_success']) && $config['submit_success'] = '发送成功';
		
		//默认必填项
		isset($config['require_message']) || $config['require_message'] = array(
			'content'=>'内容不能为空'
		);
		
		if(in_array('email', $config['elements']) && empty($config['format_message']['email'])){
			$config['format_message']['email'] = '邮箱格式错误';
		}
		
		$this->config = $config;
	}
}