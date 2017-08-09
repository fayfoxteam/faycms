<?php
namespace cddx2\modules\frontend\controllers;

use cddx2\library\FrontController;
use fay\core\Sql;
use fay\common\ListView;
use cms\models\tables\PostsTable;

class SearchController extends FrontController{
    public function index(){
        $keywords = $this->input->get('q', 'trim');
        
        $sql = new Sql();
        $sql->from(array('p'=>'posts'), 'id,title,abstract,thumbnail,publish_time')
            ->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'alias AS cat_alias')
            ->where(array(
                'p.delete_time = 0',
                'p.status = '.PostsTable::STATUS_PUBLISHED,
                'p.publish_time < '.$this->current_time,
                'p.title LIKE ?'=>'%'.$keywords.'%',
            ))
            ->order('p.is_top DESC, p.sort DESC, p.publish_time DESC');
        
        return $this->view->assign(array(
            'listview'=>new ListView($sql, array(
                'reload'=>$this->view->url('search/'.$keywords),
                'page_size'=>10,
            )),
            'keywords'=>$keywords,
        ))->render();
    }
}