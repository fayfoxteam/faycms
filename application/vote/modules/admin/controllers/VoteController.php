<?php
namespace vote\modules\admin\controllers;

use cms\library\AdminController;

class VoteController extends AdminController
{
    public function index()
    {
        
    }
    
    public function userUpload()
    {
        $this->layout->subtitle = '用户上传';
        $this->view->render();
    }
    
    public function detail()
    {
        $this->layout->subtitle = '投票详情';
        $this->view->render();
    }
}
