<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\ListView;
use fay\core\Response;
use fay\core\Sql;

class ChatController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'message';
    }
    
    public function index(){
        $this->layout->subtitle = '会话';
        
        //页面设置
        $_settings = $this->settingForm('admin_chat_index', '_setting_index', array(
            'display_name'=>'username',
            'page_size'=>20,
        ));
        
        $sql = new Sql();
        $sql->from(array('m'=>'messages'))
            ->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'realname,username,nickname,avatar')
            ->joinLeft(array('u2'=>'users'), 'm.to_user_id = u2.id', 'username AS to_username,nickname AS to_nickname,realname AS to_realname')
            ->where(array(
                'm.left_value = 1',
            ))
            ->order('id DESC')
        ;
        
        if($this->input->get('deleted')){
            $sql->where(array(
                'm.delete_time > 0',
            ));
        }else if($this->input->get('status') !== null && $this->input->get('status') !== ''){
            $sql->where(array(
                'm.status = ?'=>$this->input->get('status', 'intval'),
                'm.delete_time = 0',
            ));
        }else{
            $sql->where('m.delete_time = 0');
        }
        
        $this->view->assign(array(
            'listview'=>new ListView($sql, array(
                'page_size'=>!empty($_settings['page_size']) ? $_settings['page_size'] : 20,
            )),
        ))->render();
    }
    
    public function item(){
        $this->layout_template = false;
        $id = $this->input->get('id', 'intval');
        $sql = new Sql();
        $root = $sql->from(array('m'=>'messages'))
            ->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'username,nickname,realname,avatar')
            ->joinLeft(array('u2'=>'users'), 'm.to_user_id = u2.id', 'username AS to_user_id_username,nickname AS to_user_id_nickname,realname AS to_user_id_realname')
            ->where(array(
                'm.id = ?'=>$id,
            ))
            ->fetchRow()
        ;
        
        $replies = $sql->from(array('m'=>'messages'))
            ->joinLeft(array('u'=>'users'), 'm.user_id = u.id', 'username,nickname,realname,avatar')
            ->joinLeft(array('m2'=>'messages'), 'm.parent = m2.id')
            ->joinLeft(array('u2'=>'users'), 'm2.user_id = u2.id', 'username AS parent_username,nickname AS parent_nickname,realname AS parent_realname')
            ->where(array(
                'm.root = '.$root['id'],
                'm.delete_time = 0',
            ))
            ->order('id DESC')
            ->fetchAll()
        ;
        
        return Response::json(array(
            'root'=>$root,
            'replies'=>$replies,
        ));
    }
    
}