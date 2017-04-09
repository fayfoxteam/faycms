<?php
namespace guangong\modules\frontend\controllers;

use fay\common\ListView;
use fay\core\Sql;
use cms\models\tables\UsersTable;
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
    
    public function index(){
        $this->view->user = UsersTable::model()->find($this->current_user, 'mobile');
        
        $sql = new Sql();
        $sql->from(array('m'=>'guangong_messages'))
            ->where(array(
                'm.type = ' . GuangongMessagesTable::TYPE_BINGJIAN,
                'delete_time = 0',
            ))
            ->order('id DESC')
        ;
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>2
        ));
        $this->view->render();
    }
}