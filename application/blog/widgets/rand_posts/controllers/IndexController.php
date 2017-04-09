<?php
namespace blog\widgets\rand_posts\controllers;

use fay\widget\Widget;
use fay\models\tables\PostsTable;
use fay\core\Sql;

class IndexController extends Widget{
    
    public function index(){
        $sql = new Sql();
        $this->view->posts = $sql->from(array('p'=>'posts'), 'id,title,publish_time,comments')
            ->where(PostsTable::getPublishedConditions('p'))
            ->order('RAND()')
            ->limit(5)
            ->fetchAll();
        
        $this->view->render();
    }
}