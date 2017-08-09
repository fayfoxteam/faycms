<?php
namespace doc\modules\frontend\controllers;

use doc\library\FrontController;
use cms\services\CategoryService;
use fay\core\HttpException;
use cms\services\OptionService;

class GuideController extends FrontController{
    public function index(){
        $cat = $this->input->get('cat', 'trim');
        $cat = CategoryService::service()->get($cat, '*', 'fayfox');
        
        if(empty($cat)){
            throw new HttpException('页面不存在');
        }
        
        $this->layout->page_title = $cat['description'] ? "{$cat['title']}（{$cat['description']}）" : $cat['title'];
        $this->layout->title = $cat['title'].' - '.OptionService::get('site:sitename');

        $breadcrumb = array();
        $parent_path = CategoryService::service()->getParentIDs($cat, 'fayfox');
        if($parent_path){
            foreach($parent_path as $p){
                $breadcrumb[] = array(
                    'text'=>$p['title'],
                    'href'=>$this->view->url($p['alias']),
                );
            }
        }
        $this->layout->breadcrumb = $breadcrumb;
        
        if($cat['right_value'] - $cat['left_value'] == 1){
            //叶子节点
            return $this->view->assign(array(
                'cat'=>$cat,
                'posts'=>\cms\services\post\PostCategoryService::service()->getPosts($cat, 0, 'id,title,content,content_type', false, 'is_top DESC, sort, publish_time ASC'),
            ))->render('posts');
        }else{
            //非叶子
            return $this->view->assign(array(
                'cat'=>$cat,
                'cats'=>CategoryService::service()->getNextLevel($cat['id']),
                'posts'=>\cms\services\post\PostCategoryService::service()->getPosts($cat, 0, 'id,title,content,content_type', false, 'is_top DESC, sort, publish_time ASC'),
            ))->render('cats');
        }
    }
}