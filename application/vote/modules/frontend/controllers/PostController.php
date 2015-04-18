<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
use fay\models\Post;
use fay\core\Response;
use fay\core\db\Intact;
use fay\models\tables\Posts;
class PostController extends FrontendController
{
    public function item()
    {
        $id = $this->input->get('id', 'intval');
        $posts = Post::model()->get($id);
        
        if (empty($posts))
        {
            Response::jump('页面不存在');
        }
        
        Posts::model()->update(array(
            'last_view_time' => $this->current_time,
            'views'          => new Intact('views + 1'),
        ), $id);
        
        $this->layout->title = $posts['title'];
        $this->view->posts = $posts;
        $this->view->render();
    }
}