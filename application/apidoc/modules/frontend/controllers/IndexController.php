<?php
namespace apidoc\modules\frontend\controllers;

use apidoc\library\FrontController;
use fay\services\OptionService;
use apidoc\models\tables\ModelsTable;

class IndexController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $seo_options = OptionService::mget(array(
            'site:seo_index_title', 'site:seo_index_keywords', 'site:seo_index_description',
        ));
        $this->layout->title = $seo_options['site:seo_index_title'];
        $this->layout->keywords = $seo_options['site:seo_index_keywords'];
        $this->layout->description = $seo_options['site:seo_index_description'];
        
        $this->layout->current_directory = 'home';
    }
    
    public function index(){
        $this->layout->assign(array(
            'title'=>OptionService::get('site:sitename'),
            'canonical'=>$this->view->url(),
        ));
        
        $this->view->assign(array(
            'models'=>ModelsTable::model()->fetchAll('id < 1000', 'name,description,sample', 'id')
        ))->render();
    }
}