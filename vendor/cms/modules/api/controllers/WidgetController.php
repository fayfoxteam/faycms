<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\helpers\StringHelper;
use fay\models\tables\Widgets;
use fay\core\HttpException;
use fay\core\Response;

/**
 * 小工具
 */
class WidgetController extends ApiController{
	/**
	 * 根据widget name及其他传入参数，渲染一个widget
	 * @param string $name 小工具名称
	 */
	public function render(){
		//表单验证
		$this->form()->setRules(array(
			array(array('name'), 'required'),
		))->setFilters(array(
			'name'=>'trim',
			'action'=>'trim',
			'_index'=>'trim',
			'_alias'=>'trim',
		))->setLabels(array(
			'name'=>'名称',
		))->check();
		
		$widget_obj = \F::widget()->get($this->form()->getData('name'));
		if($widget_obj == null){
			throw new HttpException('Widget不存在或已被删除');
		}
		
		$widget_obj->_index = $this->form()->getData('_index');
		$widget_obj->alias = $this->form()->getData('_alias');
		
		$action = StringHelper::hyphen2case($this->form()->getData('action', 'index'), false);
		if(method_exists($widget_obj, $action)){
			$widget_obj->{$action}($this->input->request());
		}else if(method_exists($widget_obj, $action.'Action')){
			$widget_obj->{$action.'Action'}($this->input->request());
		}else{
			throw new HttpException('Widget方法不存在');
		}
	}
	
	/**
	 * 根据别名渲染一个widget
	 * @param string $alias 小工具别名
	 */
	public function load(){
		//表单验证
		$this->form()->setRules(array(
			array(array('alias'), 'required'),
		))->setFilters(array(
			'alias'=>'trim',
		))->setLabels(array(
			'alias'=>'别名',
		))->check();
		
		$alias = $this->form()->getData('alias');
		$widget_config = Widgets::model()->fetchRow(array(
			'alias = ?'=>$alias,
		));
		
		if(!$widget_config){
			throw new HttpException('Widget不存在或已被删除');
		}
		
		if($widget_config['enabled']){
			$widget_obj = \F::widget()->get($widget_config['widget_name']);
			$widget_obj->alias = $alias;//别名
			$action = $this->input->request('action', 'trim', 'index');
			if($widget_obj == null){
				throw new HttpException('Widget不存在或已被删除');
			}
			$widget_obj->{$action}(json_decode($widget_config['options'], true));
		}else{
			throw new HttpException('小工具未启用');
		}
	}
	
	/**
	 * 获取widget实例参数，需要widget实现IndexController::getData()方法
	 * @param string $alias 小工具别名
	 */
	public function data(){
		//表单验证
		$this->form()->setRules(array(
			array(array('alias'), 'required'),
		))->setFilters(array(
			'alias'=>'trim',
		))->setLabels(array(
			'alias'=>'别名',
		))->check();
		
		$data = \F::widget()->getData($this->form()->getData('alias'));
		Response::json($data);
	}
}