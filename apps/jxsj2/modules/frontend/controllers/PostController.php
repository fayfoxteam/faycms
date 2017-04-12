<?php
namespace jxsj2\modules\frontend\controllers;

use jxsj2\library\FrontController;
use cms\services\post\PostService;
use fay\core\HttpException;
use cms\models\tables\PostMetaTable;

class PostController extends FrontController{
    public function item(){
        $post = PostService::service()->get($this->input->get('id', 'intval'), 'nav.id,nav.title,files.*,category.title,meta.views');
        
        if(!$post){
            throw new HttpException('页面不存在');
        }
        //阅读数
        PostMetaTable::model()->incr($post['post']['id'], 'views', 1);
        
        //seo
        $this->layout->title = $post['post']['seo_title'];
        $this->layout->keywords = $post['post']['seo_keywords'];
        $this->layout->description = $post['post']['seo_description'];
        
        $this->view->post = $post;
        $this->view->render();
    }
    
}