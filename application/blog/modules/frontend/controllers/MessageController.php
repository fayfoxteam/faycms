<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\models\tables\Messages;
use fay\core\Validator;

class MessageController extends FrontController{
	public function create(){
		$validator = new Validator();
		$check = $validator->check(array(
			array(array('target', 'content', 'type'), 'require'),
			array(array('target', 'type', 'parent'), 'int'),
		));
		
		if($check === true){
			$message_id = Messages::model()->insert(array(
				'target'=>$this->input->post('target', 'intval'),
				'content'=>$this->input->post('content'),
				'type'=>$this->input->post('type', 'intval'),
				'parent'=>$this->input->post('parent', 'intval', 0),
				'create_time'=>$this->current_time,
				'user_id'=>$this->current_user,
				'status'=>Messages::STATUS_PENDING,
			));
			$message = Messages::model()->find($message_id, 'content,create_time');
			if($this->input->isAjaxRequest()){
				echo json_encode(array('status'=>1, 'message'=>$message));
			}
		}else{
			if($this->input->isAjaxRequest()){
				echo json_encode(array('status'=>0, 'message'=>'参数异常'));
			}
		}
	}
}