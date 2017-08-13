<?php
namespace siwi\modules\user\controllers;

use fay\exceptions\NotFoundHttpException;
use fay\exceptions\ValidationException;
use siwi\library\UserController;
use fay\helpers\StringHelper;

class WidgetController extends UserController{
    //加载一个widget
    public function load(){
        if($this->input->get('name')){
            $widget_obj = \F::widget()->get($this->input->get('name', 'trim'));
            if($widget_obj == null){
                if($this->input->isAjaxRequest()){
                    echo json_encode(array(
                        'status'=>0,
                        'message'=>'Widget不存在或已被删除',
                    ));
                }else{
                    throw new NotFoundHttpException('Widget不存在或已被删除', 404);
                }
            }
            $action = StringHelper::hyphen2case($this->input->get('action', 'trim', 'index'), false);
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
                    throw new NotFoundHttpException('Widget方法不存在', 404);
                }
            }
        }else{
            if($this->input->isAjaxRequest()){
                echo json_encode(array(
                    'status'=>0,
                    'message'=>'不完整的请求',
                ));
            }else{
                throw new ValidationException('不完整的请求');
            }
        }
    }
}