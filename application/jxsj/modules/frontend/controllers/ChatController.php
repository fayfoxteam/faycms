<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\models\tables\Messages;
use fay\core\Sql;
use fay\common\ListView;
use fay\services\MessageService;
use fay\models\tables\Users;
use fay\core\Response;
use fay\services\UserService;
use fay\helpers\StringHelper;

class ChatController extends FrontController{
	public function index(){
		$this->layout->subtitle = '会话';
		$this->form()->setData($this->input->get());
		
		$sql = new Sql();
		$sql->from(array('m'=>'messages'))
			->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'realname,username,avatar,nickname')
			->joinLeft(array('u2'=>'users'), 'm.target = u2.id', 'username AS target_username,nickname AS target_nickname')
			->where(array(
				'm.type = '.Messages::TYPE_USER_MESSAGE,
				'm.root = 0',
				'm.deleted = 0',
				'm.status = '.Messages::STATUS_APPROVED,
			))
			->order('id DESC')
		;
		
		$listview = new ListView($sql, array(
			'page_size'=>30,
		));
		$this->view->listview = $listview;			
		
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post('realname', 'trim') && $this->input->post('content', 'trim')){
			//虚构一个用户
			$user_id = UserService::service()->create(array(
				'status'=>Users::STATUS_NOT_VERIFIED,
				'nickname'=>$this->input->post('realname', 'trim'),
				'realname'=>$this->input->post('realname', 'trim'),
				'username'=>StringHelper::random('uuid'),
			));
			
			$content = $this->input->post('content', 'trim', '');
			$type = Messages::TYPE_USER_MESSAGE;
			$parent = $this->input->post('parent', 'intval', 0);
			$message_id = MessageService::service()->create(2, $content, $type, $parent, Messages::STATUS_APPROVED, $user_id);
			
			$message = MessageService::service()->get($message_id);
			Response::notify('success', array(
				'message'=>'回复留言成功',
				'data'=>$message,
			));
		}else{
			Response::notify('error', '信息不完整');
		}
	}
}