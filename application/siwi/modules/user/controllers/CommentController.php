<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\tables\Messages;
use fay\models\Message;
use fay\helpers\Date;
use fay\models\Post;
use fay\core\Validator;

class CommentController extends UserController{
	public function create(){
		$validator = new Validator();
		$check = $validator->check(array(
			array(array('target', 'content'), 'require'),
			array(array('target', 'parent'), 'int'),
		));
		
		if($check === true){
			//插入评论
			$target = $this->input->post('target', 'intval');
			$content = $this->input->post('content');
			$type = Messages::TYPE_POST_COMMENT;
			$parent = $this->input->post('parent', 'intval', 0);
			$message_id = Message::model()->create($target, $content, $type, $parent);
			//刷新文章评论数
			Post::model()->refreshComments($target);
			
			$message = Message::model()->get($message_id);
			$message['date'] = Date::niceShort($message['create_time']);
			if($this->input->isAjaxRequest()){
				echo json_encode(array('status'=>1, 'data'=>$message));
			}
		}else{
			if($this->input->isAjaxRequest()){
				echo json_encode(array('status'=>0, 'message'=>'参数异常'));
			}
		}
	}
}