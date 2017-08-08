<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use cms\services\EmailService;
use cms\models\tables\PagesTable;
use fay\core\Response;
use cms\services\FlashService;

class ContactController extends FrontController{
    public function index(){
        $page = PagesTable::model()->fetchRow(array('alias = ?'=>'contact'));
        PagesTable::model()->incr($page['id'], 'views', 1);
        $this->view->page = $page;

        $this->layout->title = $page['seo_title'] ? $page['seo_title'] : $page['title'];
        $this->layout->keywords = $page['seo_keywords'];
        $this->layout->description = $page['seo_description'];
        
        return $this->view->render();
    }
    
    public function markmessage(){
        EmailService::send('369281831@qq.com', '网站留言', "
            称呼：{$this->input->post('name')}<br />
            联系电话：{$this->input->post('phone')}<br />
            邮箱：{$this->input->post('email')}<br />
            留言：{$this->input->post('message')}<br />
        ");
        FlashService::set('留言邮件已发送', 'success');
        Response::goback();
    }
}