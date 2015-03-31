<?php
namespace milk\modules\frontend\controllers;

use milk\library\FrontendController;
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
            $page = Page::model()->get($id);
        }
        else 
        {
            $page = Page::model()->getByAlias($id);
        }
        
        
        if (empty($page))
        {
           Response::show404();
        }
        
        $this->layout->title = $page['title'];
        $this->layout->section = $page['alias'];//向layout进行输出以确定导航current
        $this->view->page = $page;

        $this->view->render();
        
    }
}