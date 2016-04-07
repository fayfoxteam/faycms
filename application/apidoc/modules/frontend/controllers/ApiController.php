<?php
namespace apidoc\modules\frontend\controllers;

use apidoc\library\FrontController;
use apidoc\models\Api;
use fay\core\HttpException;

class ApiController extends FrontController{
	public function __construct(){
		parent::__construct();
	}
	
	public function item(){
		//表单验证
		$this->form()->setRules(array(
			array(array('id'), 'required'),
			array(array('id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'id'=>'intval',
			'fields'=>'trim',
			'cat'=>'trim',
		))->setLabels(array(
			'id'=>'接口ID',
		))->check();
		
		$id = $this->form()->getData('id');
		
		$api = Api::model()->get($id);
		
		if(!$api){
			throw new HttpException('您访问的页面不存在');
		}
		
		$this->layout->current_directory = $api['category']['alias'];
		$this->layout->subtitle = $api['api']['router'];
		$this->layout->title = $api['api']['title'];
		
		$this->view->api = $api;
		$this->view->render();
	}
}