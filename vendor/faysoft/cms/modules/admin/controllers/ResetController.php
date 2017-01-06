<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Http;
use fay\core\Response;
use fay\services\CategoryService;
use fay\services\MenuService;
use fay\services\post\Category as PostCategory;
use fay\services\post\Tag as PostTag;
use fay\services\post\UserCounter as PostUserCounter;

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
		
		if(Http::isPost()){
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
		
		if(Http::isPost()){
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
		
		if(Http::isPost()){
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
		
		if(Http::isPost()){
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
		
		if(Http::isPost()){
			PostUserCounterService::service()->resetPostCount();
			Response::json('', 1, '执行成功');
		}
		
		$this->view->render();
	}
}