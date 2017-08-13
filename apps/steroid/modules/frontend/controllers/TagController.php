<?php
namespace steroid\modules\frontend\controllers;

use fay\exceptions\NotFoundHttpException;
use steroid\library\FrontController;
use cms\models\tables\TagsTable;

class TagController extends FrontController{
    public $layout_template = 'post';
    
    public function item(){
        $tag_title = $this->input->get('tag_title', 'trim');
        if(!$tag_title || !$tag = TagsTable::model()->fetchRow(array(
                'title = ?'=>$tag_title
            ))){
            throw new NotFoundHttpException('您请求的页面不存在');
        }
        
        $this->layout->assign(array(
            'title'=>$tag['title'],
            'subtitle'=>'',
        ));
        
        return $this->view->render();
    }
}