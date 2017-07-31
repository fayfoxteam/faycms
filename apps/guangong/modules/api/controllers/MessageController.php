<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use cms\services\OptionService;
use fay\core\Response;
use guangong\models\tables\GuangongMessagesTable;
use guangong\models\tables\GuangongUserExtraTable;

class MessageController extends ApiController{
    public function create(){
        //登录检查
        $this->checkLogin();
        
        $extra = GuangongUserExtraTable::model()->find($this->current_user, 'military');
        if($extra['military'] < OptionService::get('guangong:junfei', 1100)){
            Response::notify('error', '请先参军');
        }
        
        //表单验证
        $this->form()->setModel(GuangongMessagesTable::model())->check();
        
        $type = $this->form()->getData('type');
        $title = $this->form()->getData('title');
        $content = $this->form()->getData('content');
        
        $message_id = GuangongMessagesTable::model()->insert(array(
            'type'=>$type,
            'content'=>$content,
            'user_id'=>$this->current_user,
            'create_time'=>$this->current_time,
            'ip_int'=>$this->ip_int,
            'title'=>$title,
        ));
        
        $message = GuangongMessagesTable::model()->find($message_id, 'id,content,create_time');
        
        Response::redirect($this->input->request('redirect'));
    }
}