<?php
namespace guangong\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\ListView;
use fay\core\Loader;
use fay\core\Response;
use fay\core\Sql;
use cms\models\tables\ActionlogsTable;
use cms\models\tables\ContactsTable;
use guangong\models\tables\GuangongMessagesTable;

class GMessageController extends AdminController{
    public function index(){
        $this->layout->subtitle = '联系我们';
        
        $sql = new Sql();
        $sql->from(array('m'=>'guangong_messages'))
            ->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'nickname,mobile')
            ->order('id DESC');
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>$this->form('setting')->getData('page_size', 20),
        ));
        
        //引入IP地址库
        Loader::vendor('IpLocation/IpLocation.class');
        $this->view->iplocation = new \IpLocation();
        
        return $this->view->render();
    }
    
    public function reply(){
        $id = $this->input->request('id', 'intval');
        $reply = $this->input->request('reply', 'trim');
        GuangongMessagesTable::model()->update(array(
            'reply'=>$reply,
        ), $id);
        
        $contact = GuangongMessagesTable::model()->find($id, 'id,reply');
        
        Response::notify(Response::NOTIFY_SUCCESS, array(
            'message'=>'回复成功',
            'data'=>array(
                'id'=>$contact['id'],
                'reply'=>$contact['reply'],
            )
        ));
    }
}