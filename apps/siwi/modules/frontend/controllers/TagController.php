<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use cms\models\tables\TagsTable;

class TagController extends FrontController{
    public function search(){
        $tags = TagsTable::model()->fetchAll(array(
            'title LIKE ?'=>'%'.$this->input->get('key', false).'%'
        ), 'id,title', 'sort, count DESC', 20);
        echo json_encode(array(
            'status'=>1,
            'data'=>$tags,
        ));
    }
}