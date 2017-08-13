<?php
namespace ncp\modules\frontend\controllers;

use fay\core\exceptions\NotFoundHttpException;
use ncp\library\FrontController;
use cms\services\CategoryService;
use fay\core\Sql;
use cms\models\tables\PostsTable;
use fay\common\ListView;
use cms\services\post\PostService;
use fay\models\PropModel;
use fay\helpers\ArrayHelper;
use cms\models\tables\PropValuesTable;
use fay\core\db\Expr;
use ncp\models\tables\TourRoute;
use ncp\models\Recommend;
use cms\services\OptionService;

class FoodController extends FrontController{
    public function __construct(){
        parent::__construct();
    
        $this->layout->current_header_menu = 'food';
    }
    
    public function index(){
        //全部地区
        $areas = PropService::service()->getPropOptionsByAlias('area');
        //全部月份
        $monthes = PropService::service()->getPropOptionsByAlias('month');
        
        //验证输入
        if($this->form()->setRules(array(
            array(array('area_id', 'month', 'cat_id', 'page'), 'int'),
            array('area_id', 'range', array('range'=>array_merge(array(0), ArrayHelper::column($areas, 'id')))),
            array('month', 'range', array('range'=>array_merge(array(0), ArrayHelper::column($monthes, 'id')))),
            array('cat_id', 'exist', array('table'=>'categories', 'field'=>'id'))
        ))->setFilters(array(
            'area_id'=>'intval',
            'month'=>'intval',
            'cat_id'=>'intval',
        ))->check()){
            if($cat_id = $this->form()->getData('cat_id', 0)){
                $cat = CategoryService::service()->get($cat_id);
            }else{
                $cat = CategoryService::service()->get('food');
            }
            $this->layout->title = $cat['title'];
            $this->layout->keywords = $cat['seo_keywords'];
            $this->layout->description = $cat['seo_description'];
            
            $area_id = $this->form()->getData('area_id', 0);
            $month_id = $this->form()->getData('month', 0);
            $keywords = $this->form()->getData('keywords', 0);
            
            $prop_area_id = PropService::service()->getIdByAlias('area');
            
            $sql = new Sql();
            $sql->from(array('p'=>'posts'), 'id,title,thumbnail,abstract,views')
                ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
                ->where(array(
                    'c.left_value >= '.$cat['left_value'],
                    'c.right_value <= '.$cat['right_value'],
                    'p.status = '.PostsTable::STATUS_PUBLISHED,
                    'p.delete_time = 0',
                    'p.publish_time < '.$this->current_time,
                ))
                ->order('p.is_top DESC, p.sort DESC, p.publish_time DESC')
                ->group('p.id')
            ;
            
            if($area_id){
                $sql->joinLeft(array('pia'=>'post_prop_int'), array(
                    'pia.prop_id = '.$prop_area_id,
                    'pia.post_id = p.id',
                ))
                ->where(array(
                    'pia.content = ?'=>$area_id,
                ));
                $area = PropValuesTable::model()->find($area_id);
                $this->layout->title .= '-'.$area['title'];
            }
            
            if($month_id){
                $prop_month_id = PropService::service()->getIdByAlias('month');
                $sql->joinLeft(array('pim'=>'post_prop_int'), array(
                    'pim.prop_id = '.$prop_month_id,
                    'pim.post_id = p.id',
                ))->where(array(
                    'pim.content = ?'=>$month_id,
                ));
                $month = PropValuesTable::model()->find($month_id);
                $this->layout->title .= '-'.$month['title'];
            }
            
            if($keywords){
                $sql->where(array(
                    'p.title LIKE ?'=>"%{$keywords}%",
                ));
                $this->layout->title .= '-'.$keywords;
            }
            
            return $this->view->assign(array(
                'areas'=>$areas,
                'monthes'=>$monthes,
                'cats'=>CategoryService::service()->getChildren('food'),
                'area_id'=>$area_id,
                'month_id'=>$month_id,
                'cat_id'=>$cat_id,
                'cat'=>$cat,
                'listview'=>new ListView($sql, array(
                    'page_size'=>16,
                )),
            ))->render();
            
        }else{
            throw new NotFoundHttpException('页面不存在');
        }
    }
    
    public function item(){
        $id = $this->input->get('id', 'intval');
        
        if(!$id || !$post = PostService::service()->get($id, '', 'food', true)){
            throw new NotFoundHttpException('页面不存在');
        }
        PostsTable::model()->update(array(
            'last_view_time'=>$this->current_time,
            'views'=>new Expr('views + 1'),
        ), $id);
        
        $this->layout->title = $post['seo_title'];
        $this->layout->keywords = $post['seo_keywords'];
        $this->layout->description = $post['seo_description'];
        
        $area = PostService::service()->getPropValueByAlias('area', $id);

        $travel_cat = CategoryService::service()->get('travel', 'id,left_value,right_value');//旅游分类根目录
        $product_cat = CategoryService::service()->get('product', 'id,left_value,right_value');//产品分类根目录
        $food_cat = CategoryService::service()->get('food', 'id,left_value,right_value');//食品分类根目录
        
        return $this->view->assign(array(
            'post'=>$post,
            'area'=>$area,
            'buy_link'=>PostService::service()->getPropValueByAlias('food_buy_link', $id),
            'routes'=>TourRoute::model()->fetchAll('post_id = '.$post['id']),
            'travel_posts'=>Recommend::model()->getByCatAndArea($travel_cat, 9, OptionService::get('site:content_recommend_days'), $area['id']),
            'product_posts'=>Recommend::model()->getByCatAndArea($product_cat, 9, OptionService::get('site:content_recommend_days'), $area['id']),
            'right_posts'=>Recommend::model()->getByCatAndArea($food_cat, 6, OptionService::get('site:right_recommend_days'), 0, $id),
            'right_top_posts'=>Recommend::model()->getByCatAndArea($product_cat, 2, OptionService::get('site:right_top_recommend_days')),
        ))->render();
    }
}