<?php
namespace glhs\modules\frontend\controllers;

use fay\exceptions\NotFoundHttpException;
use fay\exceptions\ValidationException;
use glhs\library\FrontController;
use cms\services\post\PostService;
use fay\core\Validator;
use cms\services\CategoryService;
use fay\core\Sql;
use fay\common\ListView;
use cms\models\tables\PostsTable;

class PostController extends FrontController{
    public function cat(){
        $validator = new Validator();
        if($validator->check(array(
            array('alias', 'required'),
        )) !== true){
            throw new ValidationException('异常的请求');
        }
        
        $cat = CategoryService::service()->get($this->input->get('alias'));
        if(!$cat){
            throw new NotFoundHttpException('文章不存在');
        }

        $this->layout->title = $cat['title'];
        $this->layout->keywords = $cat['seo_keywords'];
        $this->layout->description = $cat['seo_keywords'];
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id,title,abstract,thumbnail,publish_time')
            ->where(array(
                'p.cat_id = '.$cat['id'],
                'p.delete_time = 0',
                'p.status = '.PostsTable::STATUS_PUBLISHED,
                'p.publish_time < '.$this->current_time,
            ))
            ->order('is_top DESC, sort DESC, publish_time DESC');
        return $this->view->assign(array(
            'cat'=>$cat,
            'listview'=>new ListView($sql, array(
                'reload'=>$this->view->url($cat['alias']),
                'page_size'=>10,
            )),
        ))->render();
    }
    
    public function item(){
        $validator = new Validator();
        if($validator->check(array(
            array(array('id', 'cat'), 'required'),
            array(array('id'), 'numeric'),
        )) !== true){
            throw new ValidationException('异常的请求');
        }
        
        $cat = CategoryService::service()->get($this->input->get('cat'));
        
        $post = PostService::service()->get($this->input->get('id', 'intval'), 'nav.id,nav.title', $cat);
        if(!$post){
            throw new NotFoundHttpException('文章不存在');
        }
        $this->view->post = $post;
        
        //设置页面SEO信息
        $this->layout->title = $post['post']['seo_title'];
        $this->layout->keywords = $post['post']['seo_keywords'];
        $this->layout->description = $post['post']['seo_description'];
        
        $this->layout->canonical = $this->view->url("{$cat['alias']}-{$post['post']['id']}");
        
        return $this->view->render();
    }
}