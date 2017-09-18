<?php
namespace fay\core;

use cms\services\user\UserService;
use fay\helpers\IPHelper;
use fay\helpers\StringHelper;

/**
 * @property View $view 视图
 * @property Layout $layout 模版
 */
class Controller{
    /**
     * 模板文件
     * @var string
     */
    public $layout_template;

    /**
     * 当前时间时间戳
     * @var int
     */
    public $current_time = 0;

    /**
     * 当前登录用户ID
     */
    public $current_user = 0;

    /**
     * 当前用户IP
     * @var string
     */
    public $ip = '';

    /**
     * 当前用户IP（已经转化成int类型）
     * @var int
     */
    public $ip_int = 0;

    /**
     * @var Response
     */
    public $response;
    
    /**
     * 检查过被阻止的路由
     */
    protected $_denied_routers = array();
    
    /**
     * @var \fay\core\Input
     */
    protected $input;
    
    /**
     * @var \fay\core\Config
     */
    protected $config;

    /**
     * 随机token，用于防止重复请求（并不一定用到）
     */
    private $token;
    
    /**
     * @var Controller
     */
    private static $_instance;
    
    public function __construct(){
        $this->input = Input::getInstance();
        $this->config = Config::getInstance();
        $this->current_time = time();
        $this->response = new Response();
        //当前用户IP
        $this->ip = Request::getUserIP();
        $this->ip_int = IPHelper::ip2int($this->ip);
        
        self::$_instance = $this;
    }
    
    public function __get($key){
        if($key == 'view'){
            $this->view = new View();
            return $this->view;
        }else if($key == 'layout'){
            $this->layout = new Layout();
            return $this->layout;
        }else{
            return null;
        }
    }
    
    public static function getInstance(){
        return self::$_instance;
    }
    
    /**
     * 获取一个表单实例，若name为null，返回第一个被实例化的表单。
     *    若没有表单被实例化，实例化一个default
     * @param null|string $name 默认为第一个被实例化的表单
     * @return Form
     */
    public function form($name = 'default'){
        return \F::form($name);
    }

    /**
     * 根据路由做权限检查
     * 这里必然是对当前登录用户做检查
     * @param string $router
     * @return bool
     */
    public function checkPermission($router){
        return UserService::service()->checkPermission($router);
    }
    
    /**
     * 生成一个token并返回。
     * 一次http请求只会生成一个token。
     */
    public function getToken(){
        if(!$this->token){
            //设置token
            $this->token = StringHelper::random();
            \F::session()->set('_token', $this->token);
        }
        return $this->token;
    }
    
    /**
     * 检查token防重复提交，每次校验都会重新生成一个token
     * @return bool
     * @throws \Exception
     */
    protected function checkToken(){
        $token = $this->input->request('_token');
        $last_token = \F::session()->get('_token');
        $this->getToken();
        if($token && $token == $last_token){
            return true;
        }else{
            throw new \Exception('Token校验失败');
        }
    }
    
    /**
     * 检查http method，若不符合，直接返回错误提示
     * @param string $method
     */
    protected function checkMethod($method){
        $method = strtoupper($method);
        if(Request::getMethod() != $method){
            Response::notify(Response::NOTIFY_FAIL, array(
                'message'=>"请以{$method}方式发起请求",
                'code'=>'http-method-error',
            ));
        }
    }
}