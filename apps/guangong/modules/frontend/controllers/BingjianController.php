<?php
namespace guangong\modules\frontend\controllers;

use cms\services\user\UserService;
use fay\common\ListView;
use fay\core\HttpException;
use fay\core\Sql;
use guangong\library\FrontController;
use guangong\models\tables\GuangongMessagesTable;

/**
 * 兵谏
 */
class BingjianController extends FrontController{
    public function __construct(){
        $this->layout_template = 'forum';
        
        parent::__construct();
    }
    
    public function user(){
        $type = $this->input->get('type', 'intval', GuangongMessagesTable::TYPE_BINGJIAN_GONGCHENG);
        $user_id = $this->input->get('user_id', 'intval');
        if(!UserService::isUserIdExist($user_id)){
            throw new HttpException('您访问的页面不存在');
        }
        
        $sql = new Sql();
        $sql->from(array('m'=>'guangong_messages'))
            ->where(array(
                'm.user_id'=>$user_id,
                'm.type = ?'=>$type,
                'delete_time = 0',
            ))
            ->order('id DESC')
        ;
    
        $this->view->listview = new ListView($sql, array(
            'page_size'=>200,
        ));
        $this->view->type = $type;
        $this->view->render();
    }
    
    public function index(){
        $type = $this->input->get('type', 'intval', GuangongMessagesTable::TYPE_BINGJIAN_GONGCHENG);
        
        $sql = new Sql();
        $sql2 = new Sql();
        $sql2->from('guangong_messages')
            ->where(array(
                'type = ?'=>$type,
                'delete_time = 0',
            ))
            ->order('id DESC');
        $sql->from(array('m'=>$sql2))
            ->group('user_id')
            ->order('id DESC')
        ;
        echo $sql;die;
        $count_sql = new Sql();
        $count_sql->from('guangong_messages', 'COUNT(*)')
            ->group('user_id');
        
        $this->view->listview = new ListView($sql, array(
            'item_view'=>'_list_item2',
            'page_size'=>200,
            'count_sql'=>$count_sql
        ));
        $this->view->type = $type;
        $this->view->render('index2');
    }
    
    public function jianyan(){
        $type = $this->input->get('type', 'intval', GuangongMessagesTable::TYPE_BINGJIAN_GONGCHENG);

        $this->view->user = UserService::service()->get($this->current_user, 'nickname,mobile,id');
        $this->view->type = $type;
        $this->view->render();
    }
}