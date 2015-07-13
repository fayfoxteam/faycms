<?php
namespace fay\widgets\post_item\controllers;

use fay\core\Widget;
use fay\models\Post;

class IndexController extends Widget{
	public function index($config){
		isset($config['fields']) || $config['fields'] = array('user', 'nav');
		empty($config['type']) && $config['type'] = 'by_input';
		
		if($config['type'] == 'by_input'){
			$post = Post::model()->get($this->input->get($config['id_key']), implode(',', $config['fields']));
		}else{
			$post = Post::model()->get($config['fixed_id'], implode(',', $config['fields']));
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'post'=>$post,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'post'=>$post,
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