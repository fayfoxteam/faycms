<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use fay\core\Sql;
use fay\common\ListView;

class SearchController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->current_header_menu = '';
    }
    
    public function index(){
        $keywords = $this->input->get('keywords', 'trim');
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id,title,abstract,publish_time,cat_id,thumbnail')
            ->joinLeft(array('pm'=>'post_meta'), 'p.id = pm.post_id', 'views')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title,alias AS cat_alias')
            ->where(array('p.title LIKE ?'=>'%'.$keywords.'%'))
            ->order('id DESC');
        
        return $this->view->assign(array(
            'listview'=>new ListView($sql),
            'keywords'=>$keywords,
        ))->render();
    }
}