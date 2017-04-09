<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use cms\services\CategoryService;
use cms\services\post\PostService;

class TeacherController extends FrontController{
    public function index(){
        $this->layout->title = '师资力量';
        
        //师资力量分类
        $this->view->cat_teacher = CategoryService::service()->get('teacher', 'description');
        //师资力量文章
        $this->view->teachers = \cms\services\post\CategoryService::service()->getPosts('teacher', 6, 'id,title,thumbnail');
        
        $this->view->render();
    }
}