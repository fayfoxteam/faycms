<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use fay\helpers\String;
use fay\core\HttpException;

class WidgetController extends FrontController{
	//加载一个widget
	public function load(){
		if($this->input->get('name')){
			$widget_obj = $this->widget->get($this->input->get('name', 'trim'));
			if($widget_obj == null){
				if($this->input->isAjaxRequest()){
					echo json_encode(array(
						'status'=>0,
						'message'=>'Widget不存在或已被删除',
					));
				}else{
					throw new HttpException('Widget不存在或已被删除');
				}
			}
			$action = String::hyphen2case($this->input->get('action', 'trim', 'index'), false);
			if(method_exists($widget_obj, $action)){
				$widget_obj->{$action}($this->input->get());
			}else if(method_exists($widget_obj, $action.'Action')){
				$widget_obj->{$action.'Action'}($this->input->get());
			}else{
				if($this->input->isAjaxRequest()){
					echo json_encode(array(
						'status'=>0,
						'message'=>'Widget方法不存在',
					));
				}else{
					throw new HttpException('Widget方法不存在');
				}
			}
		}else{
			if($this->input->isAjaxRequest()){
				echo json_encode(array(
					'status'=>0,
					'message'=>'不完整的请求',
				));
			}else{
				throw new HttpException('不完整的请求', 500);
			}
		}
	}
}