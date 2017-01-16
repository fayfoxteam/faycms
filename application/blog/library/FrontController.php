<?php
namespace blog\library;

use fay\core\Controller;
use fay\helpers\RequestHelper;
use fay\helpers\UrlHelper;
use fay\models\tables\SpiderLogsTable;
use fay\services\oauth\qq\QQClient;

class FrontController extends Controller{
	public $layout_template = 'frontend';
	public $current_user = 0;
	
	public function __construct(){
		parent::__construct();
		
		//设置当前用户id
		$this->current_user = \F::session()->get('user.id', 0);
		
		if($spider = RequestHelper::isSpider()){//如果是蜘蛛，记录蜘蛛日志
			SpiderLogsTable::model()->insert(array(
				'spider'=>$spider,
				'url'=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
				'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
				'ip_int'=>RequestHelper::ip2int($this->ip),
				'create_time'=>$this->current_time,
			));
		}
		
		$client = new QQClient('100317529', '3bb44788f9ebdd4f35aba1edda24287a');
		$client->setRedirectUri(UrlHelper::createUrl('oauth/qq-user-info'));
		
		$this->layout->assign(array(
			'qq_oauth_url'=>$client->getAuthorizeUrl(),
		));
	}
}