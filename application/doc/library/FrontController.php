<?php
namespace doc\library;

use fay\core\Controller;
use fay\helpers\RequestHelper;
use fay\models\tables\SpiderLogs;

class FrontController extends Controller{
	public $layout_template = 'frontend';
	public $current_user = 0;
	
	public function __construct(){
		parent::__construct();
		
		//设置当前用户id
		$this->current_user = $this->session->get('id', 0);
		
		if($spider = RequestHelper::isSpider()){//如果是蜘蛛，记录蜘蛛日志
			SpiderLogs::model()->insert(array(
				'spider'=>$spider,
				'url'=>'http://'.(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'],
				'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
				'ip_int'=>RequestHelper::ip2int($this->ip),
				'create_time'=>$this->current_time,
			));
		}
		
		$this->layout->keywords = 'Fayfox,Fayfox文档,Fayfox手册,Fayfox二次开发,文档中心,在线手册,phpfayfox,类库参考,开发框架,php框架,PHP开发框架';
		$this->layout->description = 'Fayfox是一款基于PHP5.3+，自带轻量级MVC框架的CMS系统。完全免费、开源、提供详细技术文档。轻量、高效、架构清晰、易扩展。';
	}
}