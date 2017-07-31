<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\services\CategoryService;
use cms\services\MenuService;
use cms\services\post\PostCategoryService;
use cms\services\post\PostTagService;
use cms\services\post\PostUserCounterService;
use fay\core\Request;
use fay\core\Response;

class ResetController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'reset';
    }
    
    /**
     * 重置分类表索引
     */
    public function category(){
        $this->layout->subtitle = '重置分类表索引';
        
        if(Request::isPost()){
            CategoryService::service()->buildIndex();
            Response::json('', 1, '执行成功');
        }
        
        $this->view->render();
    }
    
    /**
     * 重置菜单表索引
     */
    public function menu(){
        $this->layout->subtitle = '重置菜单表索引';
        
        if(Request::isPost()){
            MenuService::service()->buildIndex();
            Response::json('', 1, '执行成功');
        }
        
        $this->view->render();
    }
    
    /**
     * 重置分类文章数
     */
    public function categoryPostCount(){
        $this->layout->subtitle = '重置分类文章数';
        $this->layout->current_directory = 'post-count';
        
        if(Request::isPost()){
            PostCategoryService::service()->resetPostCount();
            Response::json('', 1, '执行成功');
        }
        
        $this->view->render();
    }
    
    /**
     * 重置标签文章数
     */
    public function tagPostCount(){
        $this->layout->subtitle = '重置标签文章数';
        $this->layout->current_directory = 'post-count';
        
        if(Request::isPost()){
            PostTagService::service()->resetPostCount();
            Response::json('', 1, '执行成功');
        }
        
        $this->view->render();
    }
    
    /**
     * 重置用户文章数
     */
    public function userPostCount(){
        $this->layout->subtitle = '重置用户文章数';
        $this->layout->current_directory = 'post-count';
        
        if(Request::isPost()){
            PostUserCounterService::service()->resetCount();
            Response::json('', 1, '执行成功');
        }
        
        $this->view->render();
    }
}