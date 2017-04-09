<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use cms\services\CategoryService;

class CatController extends FrontController{
    public function get(){
        if($this->input->get('id')){
            $cat = CategoryService::service()->get($this->input->get('id', 'intval'), 'id,title');
            echo json_encode(array(
                'status'=>1,
                'data'=>$cat,
            ));
        }else if($this->input->get('pid')){
            $cats = CategoryService::service()->getNextLevelByParentId($this->input->get('pid', 'intval'), 'id,title');
            echo json_encode(array(
                'status'=>1,
                'data'=>$cats,
            ));
        }else{
            echo json_encode(array(
                'status'=>1,
                'data'=>array(),
            ));
        }
    }
}