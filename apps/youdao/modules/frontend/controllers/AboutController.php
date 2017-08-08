<?php
namespace youdao\modules\frontend\controllers;

use youdao\library\FrontController;
use cms\models\tables\PagesTable;
use cms\services\OptionService;
use fay\core\HttpException;

class AboutController extends FrontController{
    public $layout_template = 'inner';
    
    public function index(){
        $page = PagesTable::model()->fetchRow(array('alias = ?'=>$this->input->get('alias', 'trim', 'about')));
        if(!$page){
            throw new HttpException('您请求的页面不存在');
        }
        $this->view->page = $page;
        //SEO
        $this->layout->title = $page['seo_title'] ? $page['seo_title'] : $page['title'] . ' | ' . OptionService::get('site:seo_index_title');
        $this->layout->keywords = $page['seo_keywords'] ? $page['seo_keywords'] : $page['title'];
        $this->layout->description = $page['seo_description'] ? $page['seo_description'] : $page['abstract'];
        
        PagesTable::model()->incr($page['id'], 'views', 1);
        
        $this->layout->banner = 'about-banner.jpg';
        $this->layout->current_directory = 'about';
        $this->layout->breadcrumbs = array(
            array(
                'title'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'title'=>'关于有道',
                'link'=>$this->view->url('about'),
            ),
            array(
                'title'=>$page['title'],
            ),
        );
        $this->layout->submenu = array(
            array(
                'title'=>'关于有道',
                'link'=>$this->view->url('about'),
                'class'=>'sel',
            ),
        );
        $this->layout->subtitle = $page['title'];
        
        return $this->view->render();
    }
    
    public function abstractModel(){
        
    }
    
    public function culture(){
        
    }
}