<?php
namespace jmc\modules\frontend\controllers;

use jmc\library\FrontendController;
use fay\models\Page;
use fay\core\Response;
class PageController extends FrontendController
{
    public function item()
    {
        $id = $this->input->get('id');
        if (is_numeric($id))
        {
            $id = intval($id);
            $content = Page::model()->get($id);
        }
        else 
        {
            $content = Page::model()->getByAlias($id);
        }
        
        if (empty($content))
        {
            Response::showError('页面不存在！', 404, '404');
        }
        
        $this->layout->title = $content['title'];
        
        $this->view->content = $content;
            
        $this->view->render();
    }
}