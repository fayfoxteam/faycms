<?php
namespace youdao\modules\frontend\controllers;

use youdao\library\FrontController;
use fay\models\tables\Posts;
use fay\helpers\StringHelper;
use fay\core\HttpException;

class CaseController extends FrontController{
	public $layout_template = 'inner';
	
	public function index(){
		$this->layout->submenu = array(
			array(
				'title'=>'成功案例',
				'link'=>$this->view->url('case'),
				'class'=>'sel',
			),
		);
		$this->layout->subtitle = '成功案例';
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'成功案例',
			),
		);
		$this->layout->current_directory = 'case';
		
		$this->view->cases = Posts::model()->fetchAll(array(
			'type = ?'=>5,
			'publish_time < '.$this->current_time,
			'status = ?'=>Posts::STATUS_PUBLISHED,
			'deleted = 0',
		));
		
		$this->view->render();
	}

	public function item(){
		if($this->input->get('alias')){
			$post = Posts::model()->fetchRow(array('alias = ?'=>$this->input->get('alias')));
		}else if($this->input->get('id')){
			$post = Posts::model()->fetchRow(array('id = ?'=>$this->input->get('id', 'intval')));
		}
	
		if(isset($post) && $post){
			$this->view->post = $post;
			//SEO
			$this->layout->title = $post['seo_title'] ? $post['seo_title'] : $post['title'];
			$this->layout->keywords = $post['seo_keywords'] ? $post['seo_keywords'] : $post['title'];
			$this->layout->description = $post['seo_description'] ? $post['seo_description'] : $post['abstract'];
		}else{
			throw new HttpException('页面不存在');
		}
	
		$this->layout->subtitle = '成功案例';
		$this->layout->submenu = array(
			array(
				'title'=>'成功案例',
				'link'=>$this->view->url('case'),
				'class'=>'sel',
			),
		);
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'成功案例',
				'link'=>$this->view->url('case'),
			),
			array(
				'title'=>StringHelper::niceShort($post['title'], 20, true),
			),
		);
	
		$this->view->render();
	}
}