<?php
namespace fay\widgets\friendlinks\controllers;

use fay\core\Widget;
use fay\models\tables\Links;

class IndexController extends Widget{
	public $eval_cat_uri = '';
	
	public function index($data){
		//title
		if(empty($data['title'])){
			$data['title'] = '友情链接';
		}
		
		if(empty($data['number'])){
			$data['number'] = 5;
		}
		
		$links = Links::model()->fetchAll(array(
			'visiable'=>1,
		), '*', 'sort', $data['number']);
		
		//若内容可显示，则不显示该widget
		if(empty($links)){
			return;
		}
		
		if(empty($data['template'])){
			$this->render('template', array(
				'links'=>$links,
				'data'=>$data,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $data['template'])){
				\F::app()->view->renderPartial($data['template'], array(
					'links'=>$links,
					'data'=>$data,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$data['template'].'<?php ');
			}
		}
	}
	
}