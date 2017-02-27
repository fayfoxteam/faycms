<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\helpers\RequestHelper;
use guangong\models\tables\GuangongMessagesTable;
use guangong\models\tables\GuangongUserExtraTable;

class MessageController extends ApiController{
	public function create(){
		//登录检查
		$this->checkLogin();
		
		$extra = GuangongUserExtraTable::model()->find($this->current_user, 'military');
		if($extra['military'] < 99){
			Response::notify('error', '请先参军');
		}
		
		//表单验证
		$this->form()->setModel(GuangongMessagesTable::model())->check();
		
		$type = $this->form()->getData('type');
		$content = $this->form()->getData('content');
		
		$message_id = GuangongMessagesTable::model()->insert(array(
			'type'=>$type,
			'content'=>$content,
			'user_id'=>$this->current_user,
			'create_time'=>$this->current_time,
			'ip_int'=>RequestHelper::ip2int($this->ip),
		));
		
		$message = GuangongMessagesTable::model()->find($message_id, 'id,content,create_time');
		
		Response::json(array(
			'message'=>$message,
		));
	}
}