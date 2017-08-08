<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use fay\core\Sql;
use cms\models\tables\PostsTable;
use fay\common\ListView;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), PostsTable::model()->formatFields('!content'))
            ->joinLeft(array('u'=>'users'), 'p.user_id = u.id', 'nickname,avatar')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title, parent AS parent_cat_id')
            ->joinLeft(array('pc'=>'categories'), 'c.parent = pc.id', 'title AS parent_cat_title')
            ->order('is_top DESC, p.sort, p.publish_time DESC')
            ->where(array(
                'p.delete_time = 0',
                'p.status = '.PostsTable::STATUS_PUBLISHED,
                'p.publish_time < '.$this->current_time,
            ))
        ;
        
        $this->view->listview = new ListView($sql, array(
            'reload'=>$this->view->url(),
            'page_size'=>20,
        ));
        
        //由于widget在layout中，所以widget调用的时候无法引入css文件，故在此处提前引入
        $this->view->appendCss($this->view->url().'css/jquery.camera.css');
        return $this->view->render();
    }
    
}