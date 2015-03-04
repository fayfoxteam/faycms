<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\models\Post;
use fay\models\tables\Posts;
use fay\core\db\Intact;
use fay\models\Page;
class PostController extends FrontController{
    public function item(){
        $id = $this->input->get('id','intval');
        
        $content = Post::model()->get($id);
        
       Posts::model()->update(array(
            'last_view_time'  => $this->current_time,
            'views'   => new Intact('views + 1'),
       ), $id);
       
       $this->layout->title = $content['title'];
      
       $this->view->content = $content;
       
       $this->view->render();
    }
}