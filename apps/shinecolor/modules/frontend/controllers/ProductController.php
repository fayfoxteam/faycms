<?php
namespace shinecolor\modules\frontend\controllers;

use fay\core\exceptions\NotFoundHttpException;
use shinecolor\library\FrontController;
use fay\core\Sql;
use cms\services\CategoryService;
use cms\models\tables\PostsTable;
use fay\common\ListView;
use cms\services\post\PostService;
use fay\helpers\HtmlHelper;

class ProductController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
    
        $this->layout->current_header_menu = 'service';

        $sql = new Sql();
        $this->view->pages = $sql->from(array('pc'=>'pages_categories'), '')
            ->joinLeft(array('p'=>'pages'), 'pc.page_id = p.id', 'alias,title')
            ->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id')
            ->where(array(
                "c.alias = 'service'",
                'p.delete_time = 0',
            ))
            ->order('p.sort')
            ->fetchAll();
    }
    
    public function index(){
        $cat_product = CategoryService::service()->get('product');

        $this->layout->title = $cat_product['seo_title'];
        $this->layout->keywords = $cat_product['seo_keywords'];
        $this->layout->description = $cat_product['seo_description'];
        
        $this->layout->breadcrumbs = array(
            array(
                'label'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'label'=>'产品展示',
            ),
        );
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id,title,thumbnail')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id')
            ->where(array(
                'c.left_value >= '.$cat_product['left_value'],
                'c.right_value <= '.$cat_product['right_value'],
            ))
            ->where(PostsTable::getPublishedConditions('p'))
            ->order('p.is_top DESC, p.sort DESC, p.publish_time DESC')
        ;
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>9,
            'reload'=>$this->view->url('product'),
        ));
        return $this->view->render();
    }
    
    public function item(){
        $id = $this->input->get('id', 'intval');
        $post = PostService::service()->get($id);
        
        if(!$post){
            throw new NotFoundHttpException('文章不存在');
        }
        
        $this->layout->breadcrumbs = array(
            array(
                'label'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'label'=>'产品展示',
                'link'=>$this->view->url('product'),
            ),
            array(
                'label'=>HtmlHelper::encode($post['title']),
            ),
        );

        $this->layout->title = $post['seo_title'];
        $this->layout->keywords = HtmlHelper::encode($post['seo_keywords']);
        $this->layout->description = $post['seo_description'];
        
        $this->view->post = $post;
        
        return $this->view->render();
    }
}