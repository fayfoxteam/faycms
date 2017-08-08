<?php
namespace guangong\modules\frontend\controllers;

use cms\models\tables\PostsTable;
use cms\services\post\PostService;
use fay\core\Sql;
use guangong\library\FrontController;
use guangong\models\tables\GuangongReadLogsTable;

class PostController extends FrontController{
    public function item(){
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array(array('id'), 'exist', array(
                'table'=>'posts',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'文章ID',
        ))->check();
        
        $post_id = $this->form()->getData('id');
        if($this->current_user){
            //若是登录用户访问此页面，记录阅读
            if(!GuangongReadLogsTable::model()->fetchRow(array(
                'user_id = ' . $this->current_user,
                'post_id = ?'=>$post_id,
            ))){
                GuangongReadLogsTable::model()->insert(array(
                    'user_id'=>$this->current_user,
                    'post_id'=>$post_id,
                    'create_time'=>$this->current_time,
                    'create_date'=>date('Y-m-d'),
                ));
            }
        }
        
        $this->view->renderPartial(null, array(
            'post'=> PostService::service()->get($post_id),
            'title'=>'资料库',
        ));
    }

    public function index(){
        $sql = new Sql();
        $posts = $sql->from(array('p'=>PostsTable::model()->getTableName()), 'id,title')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
            ->joinLeft(array('r'=>GuangongReadLogsTable::model()->getTableName()), "r.user_id = {$this->current_user} AND p.id = r.post_id", 'id AS read_id,create_date AS read_date')
            ->where(PostsTable::getPublishedConditions())
            ->order('p.sort DESC')
            ->fetchAll()
        ;

        $this->view->posts = $posts;
        return $this->view->render();
    }

    public function item2(){
        //表单验证
        $this->form()->setRules(array(
            array(array('id'), 'required'),
            array(array('id'), 'int', array('min'=>1)),
            array(array('id'), 'exist', array(
                'table'=>'posts',
                'field'=>'id',
            )),
        ))->setFilters(array(
            'id'=>'intval',
        ))->setLabels(array(
            'id'=>'文章ID',
        ))->check();

        $post_id = $this->form()->getData('id');

        $this->view->renderPartial(null, array(
            'post'=> PostService::service()->get($post_id),
        ));
    }
}