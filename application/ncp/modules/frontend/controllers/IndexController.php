<?php
namespace ncp\modules\frontend\controllers;

use ncp\library\FrontController;
use fay\models\Prop;
use fay\services\Category;
use fay\models\Post;
use ncp\models\Recommend;
use fay\services\Option;
use fay\helpers\ArrayHelper;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'home';
	}
	
	public function index(){
		//全部地区
		$areas = Prop::service()->getPropOptionsByAlias('area');
		
		$travels = array();
		$foods = array();
		$products = array();

		$travel_cat = Category::service()->getByAlias('travel');
		$food_cat = Category::service()->getByAlias('food');
		$product_cat = Category::service()->getByAlias('product');
		
		$prop_area = Prop::service()->getIdByAlias('area');
		
		foreach($areas as $a){
			$travel_top = Post::model()->getByProp($prop_area, $a['id'], 4, $travel_cat['id']);
			$not = ArrayHelper::column($travel_top, 'id');
			$travels[] = array(
				'top'=>$travel_top,
				'recommend'=>Recommend::model()->getByCatAndArea($travel_cat, 6, Option::get('site:index_travel_recommend_days'), $a['id'], $not),
			);
			$foods[] = Post::model()->getByProp($prop_area, $a['id'], 4, $food_cat['id']);
			$products[] = Post::model()->getByProp($prop_area, $a['id'], 7, $product_cat['id']);
		}
		
		$this->view->assign(array(
			'areas'=>$areas,
			'travels'=>$travels,
			'foods'=>$foods,
			'products'=>$products,
		))->render();
	}
	
}