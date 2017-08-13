<?php
namespace jxsj\modules\frontend\controllers;

use fay\core\exceptions\NotFoundHttpException;
use jxsj\library\FrontController;
use cms\services\CategoryService;
use fay\core\Sql;
use cms\models\tables\PostsTable;
use fay\common\ListView;

class CatController extends FrontController{
    public function index(){
        $cat = CategoryService::service()->get($this->input->get('id', 'intval'));
        
        if(!$cat){
            throw new NotFoundHttpException('页面不存在');
        }
        
        $this->layout->title = $cat['seo_title'] ? $cat['seo_title'] : $cat['title'];
        $this->layout->keywords = $cat['seo_keywords'] ? $cat['seo_keywords'] : $cat['title'];
        $this->layout->description = $cat['seo_description'];
        
        $this->view->cat = $cat;
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id,title,publish_time,thumbnail,content')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id')
            ->order('p.is_top DESC, p.sort DESC, p.publish_time DESC')
            ->where(array(
                'c.left_value >= '.$cat['left_value'],
                'c.right_value <= '.$cat['right_value'],
                'p.delete_time = 0',
                'p.status = '.PostsTable::STATUS_PUBLISHED,
                'p.publish_time < '.$this->current_time,
            ))
        ;
        $this->view->listview = new ListView($sql, array(
            'page_size'=>12,
            'reload'=>$this->view->url('cat/'.$cat['id']),
            'item_view'=>$cat['description'] == 'gallery' ? '_gallery_item' : '_list_item',
        ));
                
        return $this->view->render();
    }
    
}