<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use fay\models\Category;

class CatController extends FrontController{
	public function get(){
		if($this->input->get('id')){
			$cat = Category::model()->get($this->input->get('id', 'intval'), 'id,title');
			echo json_encode(array(
				'status'=>1,
				'data'=>$cat,
			));
		}else if($this->input->get('pid')){
			$cats = Category::model()->getNextLevelByParentId($this->input->get('pid', 'intval'), 'id,title');
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