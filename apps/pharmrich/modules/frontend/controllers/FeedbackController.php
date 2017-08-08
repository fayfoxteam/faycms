<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use fay\common\ListView;
use fay\core\Sql;
use pharmrich\models\forms\LeaveMessage;

class FeedbackController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->current_header_menu = 'feedback';
    }
    
    public function index(){
        $sql = new Sql();
        $sql->from('contacts')
            //->where("reply != ''")
            ->order('id DESC');
        
        $this->form()->setModel(LeaveMessage::model());
        
        $this->view->listview = new ListView($sql, array(
            'page_size'=>10,
            'empty_text'=>'',
        ));
        return $this->view->render();
    }
}