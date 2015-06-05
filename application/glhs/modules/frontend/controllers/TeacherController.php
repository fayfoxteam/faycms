<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\models\Category;
use fay\models\Post;

class TeacherController extends FrontController{
	public function index(){
		$this->layout->title = '师资力量';
		
		//师资力量分类
		$this->view->cat_teacher = Category::model()->get('teacher', 'description');
		//师资力量文章
		$this->view->teachers = Post::model()->getByCat('teacher', 6, 'id,title,thumbnail');
		
		$this->view->render();
	}
}