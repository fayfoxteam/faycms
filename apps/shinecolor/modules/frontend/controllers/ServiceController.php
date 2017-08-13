<?php
namespace shinecolor\modules\frontend\controllers;

use fay\exceptions\NotFoundHttpException;
use fay\exceptions\ValidationException;
use shinecolor\library\FrontController;
use cms\models\tables\PagesTable;
use fay\helpers\HtmlHelper;
use fay\core\Sql;

class ServiceController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
        
        $this->layout->current_header_menu = 'service';
    }
    
    public function item(){
        $alias = $this->input->get('alias');
        if(!$alias){
            throw new ValidationException('未设置别名');
        }
        $page = PagesTable::model()->fetchRow(array(
            'alias = ?'=>$alias,
        ));
        if(!$page){
            throw new NotFoundHttpException('别名不存在');
        }
        
        $this->view->page = $page;
        
        $this->layout->title = HtmlHelper::encode($page['title']);
        
        $sql = new Sql();
        $this->view->pages = $sql->from(array('pc'=>'pages_categories'))
            ->joinLeft(array('p'=>'pages'), 'pc.page_id = p.id', 'alias,title')
            ->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id')
            ->where(array(
                "c.alias = 'service'",
                'p.delete_time = 0',
            ))
            ->order('p.sort')
            ->fetchAll();
        
        $this->layout->breadcrumbs = array(
            array(
                'label'=>'首页',
                'link'=>$this->view->url(),
            ),
            array(
                'label'=>HtmlHelper::encode($page['title']),
            ),
        );
        return $this->view->render();
    }
}