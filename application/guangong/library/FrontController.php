<?php
namespace guangong\library;

use fay\core\Controller;
use fay\core\Http;
use fay\helpers\RequestHelper;
use fay\models\tables\SpiderLogsTable;
use fay\services\oauth\OAuthException;
use fay\services\oauth\OauthService;
use fay\services\OptionService;

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
	
	/**
	 * 判断是否已登录
	 * @return bool
	 */
	protected function isLogin(){
		return !!$this->current_user;
	}
	
	/**
	 * 检查用户是否登录
	 * 若未登录，尝试获取用户openid（用户无感知），判断用户是否已注册
	 * 若为注册，进行微信登录（需要用户授权）
	 */
	protected function checkLogin(){
		if(!$this->isLogin()){
			return true;
		}
		
		$key = 'oauth:weixin';
		$config = OptionService::getGroup($key);
		if(!$config){
			throw new OAuthException("{{$key}} Oauth参数未设置");
		}
		
		if(empty($config['enabled'])){
			throw new OAuthException("{{$key}} Oauth登录已禁用");
		}
		
		OauthService::getInstance('weixin', $config['app_id'], $config['app_secret'])
			->getAccessToken()//获取Access Token
			->getUser()//获取第三方用户
			->createLocalUser()//通过第三方用户，生成本地用户
		;
		
		return false;
	}
}