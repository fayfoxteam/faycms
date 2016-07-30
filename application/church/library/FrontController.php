<?php
namespace church\library;

use fay\core\Controller;
use fay\helpers\Request;
use fay\models\tables\SpiderLogs;

class FrontController extends Controller{
	public $layout_template = 'frontend';
	
	public function __construct(){
		parent::__construct();
		
		$this->layout->show_banner = true;
		
		if($spider = Request::isSpider()){//如果是蜘蛛，记录蜘蛛日志
			SpiderLogs::model()->insert(array(
				'spider'=>$spider,
				'url'=>'http://'.(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'],
				'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
				'ip_int'=>Request::ip2int($this->ip),
				'create_time'=>$this->current_time,
			));
		}
	}
}