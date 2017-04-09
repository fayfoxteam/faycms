<?php
namespace valentine\library;

use fay\core\Controller;
use fay\core\Http;
use fay\helpers\RequestHelper;
use cms\models\tables\SpiderLogsTable;

class FrontController extends Controller{
    public $layout_template = 'frontend';
    
    public function __construct(){
        parent::__construct();
        
        //设置当前用户id
        $this->current_user = \F::session()->get('user.id', 0);
        
        if($spider = RequestHelper::isSpider()){//如果是蜘蛛，记录蜘蛛日志
            SpiderLogsTable::model()->insert(array(
                'spider'=>$spider,
                'url'=>Http::getCurrentUrl(),
                'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'ip_int'=>RequestHelper::ip2int($this->ip),
                'create_time'=>$this->current_time,
            ));
        }
        
        $this->layout->body_class = '';
    }
    
    /**
     * @param \fay\core\Form $form
     */
    public function onFormError($form){
        $errors = $form->getErrors();
        
        if($errors){
            die($errors[0]['message']);
        }
    }
}