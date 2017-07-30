<?php
namespace jxsj2\library;

use fay\core\Controller;
use fay\helpers\DeviceHelper;
use cms\models\tables\SpiderLogsTable;

class FrontController extends Controller{
    public $layout_template = 'frontend';
    public $current_user = 0;
    
    public function __construct(){
        parent::__construct();
        
        //设置当前用户id
        $this->current_user = \F::session()->get('user.id', 0);
        
        if($spider = DeviceHelper::getSpider()){//如果是蜘蛛，记录蜘蛛日志
            SpiderLogsTable::model()->insert(array(
                'spider'=>$spider,
                'url'=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                'ip_int'=>$this->ip_int,
                'create_time'=>$this->current_time,
            ));
        }
    }
}