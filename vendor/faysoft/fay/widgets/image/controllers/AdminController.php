<?php
namespace fay\widgets\image\controllers;

use fay\widget\Widget;
use fay\services\Flash;

class AdminController extends Widget{
	public function initConfig($config){
		isset($config['file_id']) || $config['file_id'] = 0;
		
		//设置模版
		empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
		$this->form->setData(array(
			'template'=>$config['template'],
		), true);
		
		return $this->config = $config;
	}
	
	public function index(){
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$data = $this->form->getFilteredData();
		
		//若模版与默认模版一致，不保存
		if($this->isDefaultTemplate($data['template'])){
			$data['template'] = '';
		}
		
		$this->saveConfig($data);
		
		Flash::set('编辑成功', 'success');
	}
	
	public function rules(){
		return array(
			array(array('file_id', 'width', 'height'), 'int'),
			array('link', 'url'),
		);
	}
	
	public function labels(){
		return array(
			'width'=>'宽',
			'height'=>'高',
			'link'=>'链接地址',
		);
	}
	
	public function filters(){
		return array(
			'file_id'=>'intval',
			'width'=>'intval',
			'height'=>'intval',
			'link'=>'trim',
			'target'=>'trim',
			'template'=>'trim',
		);
	}
}