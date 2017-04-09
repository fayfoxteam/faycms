<?php
namespace fay\services\oauth;

abstract class ClientAbstract{
    /**
     * @var string 应用ID
     */
    protected $app_id;
    
    /**
     * @var string 应用密钥
     */
    protected $app_secret;
    
    /**
     * @var string 回调地址
     */
    protected $redirect_uri;
    
    /**
     * @var StateManager
     */
    protected $state_manager;
    
    /**
     * @var string 授权的列表
     */
    protected $scope;
    
    /**
     * 用于第三方应用防止CSRF攻击，成功授权后回调时会原样带回
     */
    protected $state;
    
    /**
     * 构造函数
     * @param string $app_id 应用ID
     * @param string $app_secret 应用密钥
     */
    public function __construct($app_id, $app_secret){
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->state_manager = new StateManager('_oauth_state' . $this->app_id);
    }
    
    /**
     * 设置 scope
     * @param string $scope
     */
    public function setScope($scope){
        $this->scope = $scope;
    }
    
    /**
     * 设置 state
     * @param string $state
     */
    public function setState($state){
        $this->state = $state;
    }
    
    /**
     * 设置回调地址
     * @param $redirect_uri
     */
    public function setRedirectUri($redirect_uri){
        $this->redirect_uri = $redirect_uri;
    }
    
    /**
     * 通过code换取access_token
     * @param string $code
     * @param null|string $state 用于第三方应用防止CSRF攻击，成功授权后回调时会原样带回
     * @return AccessTokenAbstract
     */
    abstract public function getAccessToken($code, $state = null);
}