<?php
namespace jmc\modules\frontend\controllers;

use jmc\library\FrontendController;
use fay\models\Post;
use fay\core\Response;
use fay\models\tables\Posts;
use fay\core\db\Intact;
class PostController extends FrontendController
{
    public function item()
    {
        $id = $this->input->get('id', 'intval');
        
        $content = Post::model()->get($id);
        
        if (empty($content))
        {
            Response::showError('页面不存在！');
        }
        
        Posts::model()->update(array(
                'last_view_time'  => $this->current_time,
                'views'   =>  new Intact('views + 1'),
        ), $id);
        
        $this->layout->title = $content['title'];
        
        $this->view->content = $content;
        
        $this->view->render();
    }
}