<?php
namespace steroid\modules\frontend\controllers;

use steroid\library\FrontController;
use cms\services\OptionService;
use steroid\models\forms\LeaveMessage;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = OptionService::get('site:seo_index_title');
        $this->layout->keywords = OptionService::get('site:seo_index_keywords');
        $this->layout->description = OptionService::get('site:seo_index_description');
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        $this->form()->setModel(LeaveMessage::model());
        
        return $this->view->render();
    }
}