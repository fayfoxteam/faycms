<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Messages;
use fay\models\tables\Actionlogs;
use fay\models\Post;
use fay\models\Message;
use fay\core\Response;
use fay\helpers\Html;

class MessageController extends AdminController{
	public function approve(){
		$id = $this->input->get('id', 'intval');
		Messages::model()->update(array(
			'status'=>Messages::STATUS_APPROVED,
		), $id);
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '批准了一条留言', $id);
		
		$message = Messages::model()->find($id, 'target,status,type');
		if($message['type'] == Messages::TYPE_POST_COMMENT){
			Post::model()->refreshComments($message['target']);
		}
		
		Response::output('success', array(
			'data'=>array(
				'id'=>$id,
				'status'=>$message['status'],
			),
		));
	}
	
	public function unapprove(){
		$id = $this->input->get('id', 'intval');
		Messages::model()->update(array(
			'status'=>Messages::STATUS_UNAPPROVED,
		), $id);
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '驳回了一条留言', $id);
		
		$message = Messages::model()->find($id, 'target,status,type');
		if($message['type'] == Messages::TYPE_POST_COMMENT){
			Post::model()->refreshComments($message['target']);
		}
		
		Response::output('success', array(
			'data'=>array(
				'id'=>$id,
				'status'=>$message['status'],
			),
		));
	}

	public function delete(){
		$id = $this->input->get('id', 'intval');
	
		Messages::model()->update(array(
			'deleted'=>1,
		), $id);
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '将留言移入回收站', $id);
		
		$message = Messages::model()->find($id, 'target,type');
		if($message['type'] == Messages::TYPE_POST_COMMENT){
			Post::model()->refreshComments($message['target']);
		}
		
		Response::output('success', array(
			'data'=>array(
				'id'=>$id,
			),
			'message'=>'一条留言被移入回收站 - '.Html::link('撤销', array('admin/comment/undelete', array(
				'id'=>$id,
			)))
		));
	}

	public function undelete(){
		$id = $this->input->get('id', 'intval');
	
		Messages::model()->update(array(
			'deleted'=>0,
		), $id);
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '还原一条留言', $id);
		
		$message = Messages::model()->find($id, 'target');
		Post::model()->refreshComments($message['target']);
		
		Response::output('success', array(
			'data'=>array(
				'id'=>$id,
			),
			'message'=>'一条留言被还原',
		));
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');

		$message = Messages::model()->find($id, 'target');
		
		Message::model()->remove($id);
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '将留言永久删除', $id);
		
		if($message){
			Post::model()->refreshComments($message['target']);
		}
		
		Response::output('success', array(
			'data'=>array(
				'id'=>$id,
			),
			'message'=>'一条留言被永久删除',
		));
	}
	
	public function removeAll(){
		$id = $this->input->get('id', 'intval');
		
		$result = Message::model()->removeChat($id);
		if($result === false){
			Response::output('error', array(
				'message'=>'该留言非会话根留言',
			));
		}else{
			Response::output('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'会话删除成功',
			));
		}
	}
	
	public function create(){
		$target = $this->input->post('target', 'intval');
		if(!$target){
			Response::output('error', array(
				'message'=>'信息不完整',
			));
		}
		$content = $this->input->post('content', null, '');
		$type = Messages::TYPE_USER_MESSAGE;
		$parent = $this->input->post('parent', 'intval', 0);
		$message_id = Message::model()->create($target, $content, $type, $parent);
			
		$message = Message::model()->get($message_id, '!deleted,is_terminal');
		
		Response::output('success', array(
			'data'=>$message,
			'message'=>'留言添加成功',
		));
	}
}