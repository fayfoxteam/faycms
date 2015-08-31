<?php
namespace fay\widgets\image\controllers;

use fay\core\Widget;
use fay\models\Flash;

class AdminController extends Widget{
	public function index($data){
		isset($data['file_id']) || $data['file_id'] = 0;
		$this->view->data = $data;
		$this->view->render();
	}
	
	/**
	 * 当有post提交的时候，会自动调用此方法
	 */
	public function onPost(){
		$this->setConfig($this->form->getFilteredData());
		Flash::set('编辑成功', 'success');
	}
	
	/**
	 * 会在编辑界面的侧边栏出现
	 * @param array $data 该widget实例的参数
	 */
	public function sidebar($data){
		$this->view->data = $data;
		
		$this->view->render('sidebar');
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
		);
	}
}