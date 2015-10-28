<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\helpers\String;
use fay\models\tables\Widgets;
use fay\core\HttpException;
use fay\core\Response;

class WidgetController extends ApiController{
	/**
	 * 根据widget name及其他传入参数，渲染一个widget
	 * @throws HttpException
	 */
	public function render(){
		if($this->input->request('name')){
			$widget_obj = $this->widget->get($this->input->request('name', 'trim'));
			if($widget_obj == null){
				throw new HttpException('Widget不存在或已被删除');
			}
			
			$widget_obj->_index = $this->input->request('_index');
			$widget_obj->alias = $this->input->request('_alias');
			
			$action = String::hyphen2case($this->input->request('action', 'trim', 'index'), false);
			if(method_exists($widget_obj, $action)){
				$widget_obj->{$action}($this->input->request());
			}else if(method_exists($widget_obj, $action.'Action')){
				$widget_obj->{$action.'Action'}($this->input->request());
			}else{
				throw new HttpException('Widget方法不存在');
			}
		}else{
			throw new HttpException('不完整的请求');
		}
	}
	
	/**
	 * 根据别名渲染一个widget
	 * @throws HttpException
	 */
	public function load(){
		if($alias = $this->input->request('alias')){
			$widget_config = Widgets::model()->fetchRow(array(
				'alias = ?'=>$alias,
			));
			if($widget_config['enabled']){
				$widget_obj = $this->widget->get($widget_config['widget_name']);
				$widget_obj->alias = $alias;//别名
				$action = $this->input->request('action', 'trim', 'index');
				if($widget_obj == null){
					throw new HttpException('Widget不存在或已被删除');
				}
				$widget_obj->{$action}(json_decode($widget_config['options'], true));
			}else{
				throw new HttpException('小工具未启用');
			}
		}else{
			throw new HttpException('不完整的请求');
		}
	}
	
	/**
	 * 获取widget实例参数，需要widget实现IndexController::getData()方法
	 * @throws HttpException
	 */
	public function data(){
		if($alias = $this->input->request('alias')){
			$data = $this->widget->getData($alias);
			Response::json($data);
		}else{
			throw new HttpException('不完整的请求');
		}
	}
}