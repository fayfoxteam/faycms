<?php
namespace fay\widgets\friendlinks\controllers;

use fay\widget\Widget;
use fay\models\Link;

class IndexController extends Widget{
	public $eval_cat_uri = '';
	
	public function index($config){
		//title
		if(empty($config['title'])){
			$config['title'] = '友情链接';
		}
		
		if(empty($config['number'])){
			$config['number'] = 5;
		}
		
		if(empty($config['cat_id'])){
			$config['cat_id'] = 0;
		}
		
		$links = Link::model()->get($config['cat_id'], $config['number']);
		
		//若内容可显示，则不显示该widget
		if(empty($links)){
			return;
		}
		
		if(empty($config['template'])){
			$this->view->render('template', array(
				'links'=>$links,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'links'=>$links,
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