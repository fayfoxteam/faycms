<?php
namespace jxsj2\modules\frontend\controllers;

use jxsj2\library\FrontController;
use fay\core\Sql;
use fay\common\ListView;

class FriendlinkController extends FrontController{
    public function index(){
        $this->layout->title = '友情链接';
        
        $sql = new Sql();
        
        $sql->from('links');
        
        $this->view->listview = new ListView($sql);
        
        $this->view->render();
    }
}