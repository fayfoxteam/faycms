<?php
namespace hq\modules\frontend\controllers;

use fay\common\ListView;
use fay\core\Response;
use fay\core\Sql;
use fay\models\Category;
use fay\models\tables\Posts;
use hq\library\FrontController;

class CatController extends  FrontController
{
    public function index()
    {
        $id = $this->input->get('id', 'intval');
        $this->session->set('tab', $id);
        $cat = Category::model()->get($id);
        $this->layout->title = $cat['title'];

        if (!$cat)
        {
            Response::showError('您访问的页面不存在！', 404, '404');
        }

        $this->view->cat = $cat;

        $sql = new Sql();

        $sql->from('posts','p','id,title,publish_time')
            ->joinLeft('categories', 'c', 'p.cat_id = c.id')
            ->order('p.is_top DESC, p.sort, p.publish_time DESC')
            ->where(array(
                'c.left_value >= '.$cat['left_value'],
                'c.right_value <= '.$cat['right_value'],
                'p.deleted = 0',
                'p.status = '.Posts::STATUS_PUBLISH,
                'p.publish_time < '.$this->current_time,
            ));
        $this->view->listview = new ListView($sql, array(
            'pageSize'  => 12,
            'reload'  => $this->view->url('cat/'.$cat['id']),
        ));


        $this->view->render();
    }
}