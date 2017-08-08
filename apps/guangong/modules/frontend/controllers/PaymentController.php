<?php
namespace guangong\modules\frontend\controllers;

use cms\models\tables\UsersTable;
use guangong\library\FrontController;

/**
 * 支付结果页
 */
class PaymentController extends FrontController{
    public function __construct(){
        parent::__construct();
        
        $this->layout->title = '天下招募令';
    }
    
    /**
     * 支付成功
     */
    public function success(){
        if($this->input->get('status') == 'success'){
            $user_count = UsersTable::model()->fetchRow(array(), 'COUNT(*)');
            $this->view->user_count = $user_count['COUNT(*)'];
            return $this->view->render();
        }else{
            return $this->view->render('cancel');
        }
    }
}