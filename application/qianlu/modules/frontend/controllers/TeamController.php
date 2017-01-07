<?php
namespace qianlu\modules\frontend\controllers;

use qianlu\library\FrontController;
use fay\services\CategoryService;
use fay\core\Sql;
use fay\models\tables\PostsTable;
use fay\services\OptionService;
use fay\core\HttpException;

class TeamController extends FrontController{
	public $layout_template = 'inner';
	
	public function index(){
		$this->layout->subtitle = '团队简介';
		$this->layout->banner = 'team-banner.jpg';
		$this->layout->current_directory = 'team';
		
		//团队
		$cat_team = CategoryService::service()->getByAlias('team', '*');
		//SEO
		$this->layout->title = $cat_team['seo_title'];
		$this->layout->keywords = $cat_team['seo_keywords'];
		$this->layout->description = $cat_team['seo_description'];
		
		$this->layout->submenu = array(
			array(
				'title'=>'团队简介',
				'link'=>$this->view->url('team'),
				'class'=>'sel',
			),
		);
		
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'团队介绍',
				'link'=>$this->view->url('team'),
			),
		);

		$sql = new Sql();
		$this->view->team_members = $sql->from(array('p'=>'posts'))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->where(array(
				'p.cat_id = '.$cat_team['id'],
			))
			->where(PostsTable::getPublishedConditions('p'))
			->fetchAll();
		;
	
		$this->view->render();
	}
	
	public function item(){
		if($this->input->get('id')){
			$member = PostsTable::model()->find($this->input->get('id', 'intval'));
			if($member){
				$this->view->member = $member;
				
				//SEO
				$this->layout->title = $member['seo_title'] ? $member['seo_title'] : $member['title'] . ' | ' . OptionService::get('site:seo_team_title');
				$this->layout->keywords = $member['seo_keywords'] ? $member['seo_keywords'] : $member['title'];
				$this->layout->description = $member['seo_description'] ? $member['seo_description'] : $member['abstract'];
			}else{
				throw new HttpException('您请求的页面不存在');
			}
		}else{
			throw new HttpException('参数不完整');
		}
		$this->layout->submenu = array(
			array(
				'title'=>'团队简介',
				'link'=>$this->view->url('team'),
				'class'=>'sel',
			),
		);
		$this->layout->subtitle = '团队简介';
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'团队介绍',
				'link'=>$this->view->url('team'),
			),
			array(
				'title'=>$member['title'],
			),
		);
		$this->layout->current_directory = 'team';
		
		$this->view->render();
	}
}