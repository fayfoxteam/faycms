<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use cms\services\OptionService;
use fay\exceptions\NotFoundHttpException;
use fay\core\Sql;
use cms\models\tables\PostsTable;
use fay\common\ListView;
use cms\services\CategoryService;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = OptionService::get('site:seo_index_title');
        $this->layout->keywords = OptionService::get('site:seo_index_keywords');
        $this->layout->description = OptionService::get('site:seo_index_description');
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        $sql = new Sql();
        $sql->from(array('p'=>'posts'))
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
            ->where(PostsTable::getPublishedConditions('p'))
            ->order('p.is_top DESC, p.sort DESC, p.publish_time DESC')
        ;
        
        $reload = $this->view->url();
        if($this->input->get('cat')){
            $cat = CategoryService::service()->get($this->input->get('cat', 'intval'));
            if(!$cat){
                throw new NotFoundHttpException('分类不存在');
            }
            $sql->where(array(
                'c.left_value >= '.$cat['left_value'],
                'c.right_value <= '.$cat['right_value'],
            ));
            $this->view->subtitle = '分类目录归档： '.$cat['title'];
            $this->layout->title = $cat['title'];
            $reload = $this->view->url('cat/'.$cat['id']);
        }
        
        if($this->input->get('type')){
            if($this->input->get('type') == 'post'){
                $reload = $this->view->url('post');
                $cat = CategoryService::service()->get('_blog');
                $this->layout->title = '博文';
                $this->layout->current_directory = 'blog';
            }else if($this->input->get('type') == 'work'){
                $reload = $this->view->url('work');
                $cat = CategoryService::service()->get('_work');
                $this->layout->title = '作品';
                $this->layout->current_directory = 'work';
            }
            $sql->where(array(
                'c.left_value >= '.$cat['left_value'],
                'c.right_value <= '.$cat['right_value'],
            ));
        }
        
        $this->view->listview = new ListView($sql, array(
            'reload'=>$reload,
        ));
        $this->view->listview->init();
        
        if($this->view->listview->current_page > 1){
            $this->layout->canonical = $reload.'?page='.$this->view->listview->current_page;
        }else{
            $this->layout->canonical = $reload;
        }
        
        $this->view->work_cat = CategoryService::service()->get('_work');
        
        return $this->view->render();
    }
    
}