<?php
namespace apidoc\modules\frontend\controllers;

use apidoc\library\FrontController;
use apidoc\models\tables\Apis;
use fay\models\Category;
use apidoc\models\Output;
use apidoc\models\tables\Outputs;
use fay\core\HttpException;
use fay\helpers\StringHelper;

class ModelController extends FrontController{
	public function __construct(){
		parent::__construct();
	}
	
	public function item(){
		//表单验证
		$this->form()->setRules(array(
			array(array('model_id'), 'required'),
			array(array('model_id', 'api_id'), 'int', array('min'=>1)),
		))->setFilters(array(
			'model_id'=>'intval',
			'api_id'=>'intval',
		))->setLabels(array(
			'model_id'=>'模型ID',
			'api_id'=>'API ID',
		))->check();
		
		//通过API ID确定展开的菜单页
		$api_id = $this->form()->getData('api_id');
		if($api_id){
			$api = Apis::model()->find($api_id, 'cat_id');
			if($api){
				$category = Category::model()->get($api['cat_id'], 'alias');
				$this->layout->current_directory = $category['alias'];
			}
		}
		
		$model_id = $this->form()->getData('model_id');
		$output = Output::model()->get($model_id, 'id,name,type,description,sample');
		if(!$output || $output['type'] != Outputs::TYPE_OBJECT){
			throw new HttpException('您访问的页面不存在');
		}
		
		//Layout 参数
		$this->layout->assign(array(
			'subtitle'=>StringHelper::underscore2case($output['name']),
			'title'=>$output['description'],
			'canonical'=>$this->view->url('model/'.$output['id']),
		));
		
		//View
		$this->view->assign(array(
			'output'=>$output,
			'properties'=>Output::model()->getByParent($output['id'], Outputs::model()->getPublicFields()),
		))->render();
	}
}