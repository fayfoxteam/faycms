<?php
namespace amq\modules\frontend\controllers;

use amq\library\FrontController;
use fay\common\ListView;
use fay\core\Sql;

class SearchController extends FrontController{
    public function index(){
        $keywords = $this->input->get('keywords', 'trim');
        if($keywords){
            $sql = new Sql();
            $sql->from(array('p'=>'posts'), 'id,title,abstract,thumbnail,cat_id,publish_time')
                ->joinLeft(array('pe'=>'post_extra'), 'p.id = pe.post_id', 'source')
                ->orWhere(array(
                    'title LIKE ?'=>"%{$keywords}%",
                    'abstract LIKE ?'=>"%{$keywords}%"
                ));
            $listview = new ListView($sql, array(
                'page_size'=>10,
                'pager_view'=>'widget/next_pager',
            ));
            
            return $this->view->assign(array(
                'keywords'=>$keywords,
                'listview'=>$listview,
            ))->render();
        }
    }
}