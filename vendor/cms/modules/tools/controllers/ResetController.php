<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\services\Category;
use fay\services\Menu;
use fay\services\post\Category as PostCategory;
use fay\services\post\Tag as PostTag;
use fay\services\post\UserCounter as PostUserCounter;

class ResetController extends ToolsController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'reset';
	}
	
	/**
	 * 重置分类表索引
	 */
	public function category(){
		Category::service()->buildIndex();
	}
	
	/**
	 * 重置菜单表索引
	 */
	public function menu(){
		Menu::service()->buildIndex();
	}
	
	/**
	 * 重置分类文章数
	 */
	public function categoryPostCount(){
		PostCategory::service()->resetPostCount();
	}
	
	/**
	 * 重置标签文章数
	 */
	public function tagPostCount(){
		PostTag::service()->resetPostCount();
	}
	
	/**
	 * 重置用户文章数
	 */
	public function userPostCount(){
		PostUserCounter::service()->resetPostCount();
	}
}