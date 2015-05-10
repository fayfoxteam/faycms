<?php
namespace hq\library;

use fay\core\Controller;
use fay\helpers\RequestHelper;
use fay\models\tables\SpiderLogs;

class FrontController extends Controller
{
    public $layout_template = 'main';
    public $current_user = 0;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->current_user = $this->session->get('id', 0);
        
        if ($spider = RequestHelper::isSpider()){
            SpiderLogs::model()->insert(array(
            'spider'        => $spider,
            'url'           => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
            'ip_int'        => RequestHelper::ip2int($this->ip),
            'create_time'   => $this->current_time,
            ));
        }
    }
}