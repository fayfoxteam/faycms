<?php
namespace fay\widgets\page_item\controllers;

use fay\core\Widget;
use fay\models\Page;
use fay\core\HttpException;
use fay\models\tables\Pages;

class IndexController extends Widget{
	public function index($config){
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			$page = Page::model()->get($this->input->get($config['id_key'], 'intval'));
			if(!$page){
				throw new HttpException('您访问的页面不存在');
			}
		}else if(!empty($config['alias_key']) && $this->input->get($config['alias_key'])){
			$page = Page::model()->get($this->input->get($config['alias_key'], 'trim'));
			if(!$page){
				throw new HttpException('您访问的页面不存在');
			}
		}else if($config['default_page_id']){
			$page = Page::model()->get($config['default_page_id']);
			if(!$page){
				return '';
			}
		}
		
		if($config['inc_views']){
			Pages::model()->inc($page['id'], 'views', 1);
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'page'=>$page,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'page'=>$page,
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$config['template'].'<?php ');
			}
		}
		
	}
}