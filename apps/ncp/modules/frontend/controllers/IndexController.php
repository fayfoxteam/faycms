<?php
namespace ncp\modules\frontend\controllers;

use ncp\library\FrontController;
use fay\models\PropModel;
use cms\services\CategoryService;
use cms\services\post\PostService;
use ncp\models\Recommend;
use cms\services\OptionService;
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
        $areas = PropService::service()->getPropOptionsByAlias('area');
        
        $travels = array();
        $foods = array();
        $products = array();

        $travel_cat = CategoryService::service()->get('travel');
        $food_cat = CategoryService::service()->get('food');
        $product_cat = CategoryService::service()->get('product');
        
        $prop_area = PropService::service()->getIdByAlias('area');
        
        foreach($areas as $a){
            $travel_top = PostService::service()->getByProp($prop_area, $a['id'], 4, $travel_cat['id']);
            $not = ArrayHelper::column($travel_top, 'id');
            $travels[] = array(
                'top'=>$travel_top,
                'recommend'=>RecommendTable::model()->getByCatAndArea($travel_cat, 6, OptionService::get('site:index_travel_recommend_days'), $a['id'], $not),
            );
            $foods[] = PostService::service()->getByProp($prop_area, $a['id'], 4, $food_cat['id']);
            $products[] = PostService::service()->getByProp($prop_area, $a['id'], 7, $product_cat['id']);
        }
        
        return $this->view->assign(array(
            'areas'=>$areas,
            'travels'=>$travels,
            'foods'=>$foods,
            'products'=>$products,
        ))->render();
    }
    
}