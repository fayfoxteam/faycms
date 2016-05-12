<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Messages;
use fay\models\tables\Actionlogs;
use fay\models\Post;
use fay\models\Message;
use fay\core\Response;
use fay\helpers\Html;
use fay\services\Message as MessageService;

class MessageController extends AdminController{
	public function approve(){
		$id = $this->input->request('id', 'intval');
		
		MessageService::model()->approve($id);
		
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '批准了一条留言', $id);
		
		$message = Messages::model()->find($id, 'status');
		
		Response::notify('success', array(
			'data'=>array(
				'id'=>$id,
				'status'=>$message['status'],
			),
		));
	}
	
	public function unapprove(){
		$id = $this->input->get('id', 'intval');
		
		MessageService::model()->disapprove($id);
		
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '驳回了一条留言', $id);
		
		$message = Messages::model()->find($id, 'status');
		
		Response::notify('success', array(
			'data'=>array(
				'id'=>$id,
				'status'=>$message['status'],
			),
		));
	}

	public function delete(){
		$id = $this->input->get('id', 'intval');
		
		MessageService::model()->delete($id);
		
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '将留言移入回收站', $id);
		
		Response::notify('success', array(
			'data'=>array(
				'id'=>$id,
			),
			'message'=>'一条留言被移入回收站 - '.Html::link('撤销', array('admin/message/undelete', array(
				'id'=>$id,
			)))
		));
	}

	public function undelete(){
		$id = $this->input->get('id', 'intval');
		
		MessageService::model()->undelete($id);
		
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '还原一条留言', $id);
		
		Response::notify('success', array(
			'data'=>array(
				'id'=>$id,
			),
			'message'=>'一条留言被还原',
		));
	}
	
	public function remove(){
		$id = $this->input->get('id', 'intval');

		$message = Messages::model()->find($id, 'to_user_id');
		
		Message::model()->remove($id);
		$this->actionlog(Actionlogs::TYPE_MESSAGE, '将留言永久删除', $id);
		
		if($message){
			Post::model()->refreshComments($message['to_user_id']);
		}
		
		Response::notify('success', array(
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
			Response::notify('error', array(
				'message'=>'该留言非会话根留言',
			));
		}else{
			Response::notify('success', array(
				'data'=>array(
					'id'=>$id,
				),
				'message'=>'会话删除成功',
			));
		}
	}
	
	public function create(){
		$to_user_id = $this->input->post('to_user_id', 'intval');
		if(!$to_user_id){
			Response::notify('error', array(
				'message'=>'信息不完整',
			));
		}
		$content = $this->input->post('content', null, '');
		$parent = $this->input->post('parent', 'intval', 0);
		$message_id = MessageService::model()->create($to_user_id, $content, $parent);
			
		$message = Message::model()->get($message_id);
		
		Response::notify('success', array(
			'data'=>$message,
			'message'=>'留言添加成功',
		));
	}
}