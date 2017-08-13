<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use cms\models\tables\PagesTable;
use fay\core\exceptions\NotFoundHttpException;
use fay\core\exceptions\ValidationException;
use fay\core\Validator;

class PageController extends FrontController{
    public function __construct(){
        parent::__construct();
    
        $this->layout->title = '';
        $this->layout->keywords = '';
        $this->layout->description = '';
    
        $this->layout->current_directory = 'home';
    }
    
    public function item(){
        $validator = new Validator();
        $check = $validator->check(array(
            array(array('alias'), 'required'),
        ));
        
        if($check === true){
            $page = PagesTable::model()->fetchRow(array(
                'alias = ?'=>$this->input->get('alias'),
            ));
            if($page){
                $this->view->page = $page;
                $this->layout->title = $page['seo_title'] ? $page['seo_title'] : $page['title'];
                $this->layout->keywords = $page['seo_keywords'];
                $this->layout->description = $page['seo_description'];
                $this->layout->current_directory = $page['alias'];
                return $this->view->render();
            }else{
                throw new NotFoundHttpException('别名不存在');
            }
        }else{
            throw new ValidationException('参数异常', 500);
        }
    }
}