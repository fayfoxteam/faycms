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
			array(array('api_id'), 'required'),
			array(array('api_id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'api_id'=>'intval',
		))->setLabels(array(
			'api_id'=>'接口ID',
		))->check();
		
		$api_id = $this->form()->getData('api_id');
		
		$api = Api::model()->get($api_id);
		
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