<?php
namespace steroid\modules\frontend\controllers;

use steroid\library\FrontController;
use fay\core\HttpException;
use fay\models\tables\TagsTable;

class TagController extends FrontController{
    public $layout_template = 'post';
    
    public function item(){
        $tag_title = $this->input->get('tag_title', 'trim');
        if(!$tag_title || !$tag = TagsTable::model()->fetchRow(array(
                'title = ?'=>$tag_title
            ))){
            throw new HttpException('您请求的页面不存在');
        }
        
        $this->layout->assign(array(
            'title'=>$tag['title'],
            'subtitle'=>'',
        ));
        
        $this->view->render();
    }
}