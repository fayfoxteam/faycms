<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/7
 * Time: 下午4:32
 */
namespace hq\modules\frontend\controllers;

use fay\core\db\Intact;
use fay\core\Response;
use fay\models\Post;
use fay\models\tables\Posts;
use hq\library\FrontController;

class PostController extends  FrontController
{
    public function item()
    {
        $id = $this->input->get('id', 'intval');

        $post = Post::model()->get($id);

        if (empty($post))
        {
            Response::show404();
        }

        Posts::model()->update([
            'last_view_time' => $this->current_time,
            'views'  => new Intact('views + 1'),
        ], $id);

        $this->layout->title = $post['title'];

        $this->view->post = $post;

        $this->view->render();
    }
}