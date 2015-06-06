<?php
namespace ncp\plugins;

use fay\core\FBase;
use ncp\models\tables\TourRoute as Table_TourRoute;
use fay\models\Category;

class TourRoute extends FBase{
	public function addBox($params){
		$travel_cat = Category::model()->getByAlias('travel', 'id');
		if(Category::model()->isChild($params['cat_id'], $travel_cat['id'])){
			\F::app()->addBox(array(
				'name'=>'tour-route',
				'title'=>'路线攻略',
			));
		}
	}
	
	public function setRoutes($params){
		$travel_cat = Category::model()->getByAlias('travel', 'id');
		if(Category::model()->isChild($params['cat_id'], $travel_cat['id'])){
			//验证规则
			\F::form()->setModel(Table_TourRoute::model());
			
			//现有的路线
			if(isset($params['post_id'])){
				\F::app()->view->routes = Table_TourRoute::model()->fetchAll(array(
					'post_id = ?'=>$params['post_id'],
				), '*', 'sort');
			}
		}
	}
	
	public function save($params){
		if($routes = \F::app()->input->post('route', 'trim')){
			$i = 0;
			foreach($routes as $k => $v){
				if(is_numeric($k)){
					Table_TourRoute::model()->update(array(
						'post_id'=>$params['post_id'],
						'route'=>$v,
						'sort'=>++$i,
					), $k);
				}else{
					Table_TourRoute::model()->insert(array(
						'post_id'=>$params['post_id'],
						'route'=>$v,
						'sort'=>++$i,
					));
				}
			}
		}
	}
}