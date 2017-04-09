<?php
namespace qianlu\modules\frontend\controllers;

use qianlu\library\FrontController;
use cms\services\CategoryService;
use cms\models\tables\PostsTable;
use fay\helpers\StringHelper;
use fay\core\Sql;
use cms\models\tables\CategoriesTable;
use fay\common\ListView;
use fay\core\HttpException;

class PostController extends FrontController{
    public $layout_template = 'inner';
    
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'post';
        $submenu = array(
            array(
                'title'=>'最新资讯',
                'link'=>$this->view->url('post'),
                'class'=>'sel',
            ),
        );
        $cats = CategoryService::service()->getNextLevel('_system_post');
        foreach($cats as $c){
            $submenu[] = array(
                'title'=>$c['title'],
                'link'=>$this->view->url('c/'.$c['alias']),
            );
        }
        $this->layout->submenu = $submenu;
    }
    
    public function item(){
        if($this->input->get('alias')){
            $post = PostsTable::model()->fetchRow(array('alias = ?'=>$this->input->get('alias')));
        }else if($this->input->get('id')){
            $post = PostsTable::model()->fetchRow(array('id = ?'=>$this->input->get('id', 'intval')));
        }
    
        if(isset($post) && $post){
            $this->view->post = $post;
            //SEO
            $this->layout->title = $post['seo_title'] ? $post['seo_title'] : $post['title'];
            $this->layout->keywords = $post['seo_keywords'] ? $post['seo_keywords'] : $post['title'];
            $this->layout->description = $post['seo_description'] ? $post['seo_description'] : $post['abstract'];
        }else{
            throw new HttpException('页面不存在');
        }
        
        $this->layout->subtitle = '新闻中心';
        $this->layout->breadcrumbs = array(
            array(
                'title'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'title'=>'最新资讯',
                'link'=>$this->view->url('post'),
            ),
            array(
                'title'=>StringHelper::niceShort($post['title'], 20, true),
            ),
        );
        
        $this->view->render();
    }
    
    public function index(){
        $cat_post = CategoryService::service()->getByAlias('post', 'left_value,right_value');
        
        $submenu = array(
            array(
                'title'=>'最新资讯',
                'link'=>$this->view->url('post'),
                'class'=>'sel',
            ),
        );
        $cats = CategoryService::service()->getNextLevel('post');
        foreach($cats as $c){
            $submenu[] = array(
                'title'=>$c['title'],
                'link'=>$this->view->url('c/'.$c['alias']),
            );
        }
        $this->layout->submenu = $submenu;
        $this->layout->subtitle = '新闻中心';
        $breadcrumbs = array(
            array(
                'title'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'title'=>'最新资讯',
                'link'=>$this->view->url('post'),
            ),
        );
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'))
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id')
            ->order('p.is_top DESC, p.sort, p.publish_time DESC')
            ->where(array(
                'c.left_value > '.$cat_post['left_value'],
                'c.right_value < '.$cat_post['right_value'],
            ))
            ->where(PostsTable::getPublishedConditions('p'))
        ;
        
        if($this->input->get('k')){
            $sql->where(array(
                'p.title like ?'=>'%'.$this->input->get('k').'%',
            ));
        }
        
        if($this->input->get('c')){
            $cat = CategoriesTable::model()->fetchRow(array('alias = ?'=>$this->input->get('c')));
            if($cat){
                $breadcrumbs[] = array('title'=>$cat['title']);
                //SEO
                $this->layout->title = $cat['seo_title'];
                $this->layout->keywords = $cat['seo_keywords'];
                $this->layout->description = $cat['seo_description'];
            }
            $sql->where(array(
                'c.alias = ?'=>$this->input->get('c'),
            ));
        }
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>10,
        ));
        $this->layout->breadcrumbs = $breadcrumbs;
        $this->layout->banner = 'news-banner.jpg';
        
        $this->view->render();
    }
}