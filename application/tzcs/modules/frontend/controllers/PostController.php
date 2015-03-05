<?php
namespace tzcs\modules\frontend\controllers;

use tzcs\library\FrontController;
use fay\models\Post;
use fay\models\tables\Posts;
use fay\core\db\Intact;
use fay\models\Page;
use fay\core\Response;
class PostController extends FrontController{
    public function item(){
        $id = $this->input->get('id','intval');
        
        $content = Post::model()->get($id);
        
       if (empty($content)){
           Response::showError('页面不存在！');
       }
        
       Posts::model()->update(array(
            'last_view_time'  => $this->current_time,
            'views'   => new Intact('views + 1'),
       ), $id);
       
       $this->layout->title = $content['title'];
      
       $this->view->content = $content;
       
       $this->view->render();
    }
}