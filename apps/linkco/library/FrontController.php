<?php
namespace linkco\library;

use fay\core\Controller;
use fay\core\Request;
use fay\helpers\DeviceHelper;
use cms\models\tables\SpiderLogsTable;

class FrontController extends Controller{
    public $layout_template = 'frontend';
    
    public function __construct(){
        parent::__construct();
        
        //设置当前用户id
        $this->current_user = \F::session()->get('user.id', 0);
        
        //如果是蜘蛛，记录蜘蛛日志
        if($spider = DeviceHelper::getSpider()){
            SpiderLogsTable::model()->insert(array(
                'spider'=>$spider,
                'url'=>Request::getCurrentUrl(),
                'user_agent'=>Request::getServer('HTTP_USER_AGENT', ''),
                'http_referer'=>Request::getServer('HTTP_REFERER', ''),
                'ip_int'=>$this->ip_int,
                'create_time'=>$this->current_time,
            ));
        }
    }
}