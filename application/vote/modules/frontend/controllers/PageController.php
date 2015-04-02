<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
use fay\models\Page;
use fay\core\Response;
class PageController extends FrontendController
{
    public function item()
    {
        $id = $this->input->get('id', 'intval');
        
        $pages = Page::model()->get($id);
        
        if (empty($pages))
        {
            Response::jump('页面不存在!');
        }
        
        $this->view->pages = $pages;
        
        $this->view->render();
    }
}