<?php
namespace fay\widgets\page_item\controllers;

use fay\widget\Widget;
use fay\services\Page;
use fay\core\HttpException;
use fay\models\tables\Pages;

class IndexController extends Widget{
	public function index($config){
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//根据页面ID访问
			$page = Page::service()->get($this->input->get($config['id_key'], 'intval'));
			if(!$page){
				throw new HttpException('您访问的页面不存在');
			}
		}else if(!empty($config['alias_key']) && $this->input->get($config['alias_key'])){
			//根据页面别名访问
			$page = Page::service()->get($this->input->get($config['alias_key'], 'trim'));
			if(!$page){
				throw new HttpException('您访问的页面不存在');
			}
		}else if($config['default_page_id']){
			//默认显示页面（若默认页面不存在，则返回空，不报错）
			$page = Page::service()->get($config['default_page_id']);
			if(!$page){
				return '';
			}
		}else{
			//若未设置默认显示页面，则返回空，不报错
			return '';
		}
		
		if($config['inc_views']){
			Pages::model()->incr($page['id'], 'views', 1);
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'page'=>$page,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
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