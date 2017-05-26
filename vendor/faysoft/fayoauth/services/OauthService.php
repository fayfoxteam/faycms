<?php
namespace fayoauth\services;

abstract class OauthService{
    private static $map = array(
        'weixin'=>'fayoauth\services\weixin\WeixinOauthService',
        'am'=>'fayoauth\services\am\AmOauthService',
    );
    
    /**
     * @var string App Id
     */
    protected $app_id;
    
    /**
     * @var string App Secret
     */
    protected $app_secret;

    /**
     * @var string 回调地址
     */
    protected $redirect_uri;
    
    public static function getInstance($code, $app_id, $app_secret){
        if(!isset(self::$map[$code])){
            throw new OAuthException('不支持的第三方登录类型');
        }
        
        /**
         * @var OauthService $instance
         */
        $instance = new self::$map[$code];
        
        $instance->setAppId($app_id);
        $instance->setAppSecret($app_secret);
        
        return $instance;
    }
    
    /**
     * 获取Access Token
     * @return AccessTokenAbstract
     * @throws OAuthException
     */
    abstract public function getAccessToken();
    
    /**
     * 获取用户信息
     * @return UserAbstract
     * @throws OAuthException
     */
    abstract public function getUser();
    
    /**
     * 获取openId
     * @return string
     * @throws OAuthException
     */
    abstract public function getOpenId();
    
    /**
     * @return string
     * @throws OAuthException
     */
    public function getAppId(){
        if(!$this->app_id){
            throw new OAuthException('未设置App Id');
        }
        return $this->app_id;
    }
    
    /**
     * @param string $app_id
     * @return $this
     */
    public function setAppId($app_id){
        $this->app_id = $app_id;
        return $this;
    }
    
    /**
     * @return string
     * @throws OAuthException
     */
    public function getAppSecret(){
        if(!$this->app_secret){
            throw new OAuthException('未设置App Secret');
        }
        return $this->app_secret;
    }
    
    /**
     * @param string $app_secret
     * @return $this
     */
    public function setAppSecret($app_secret){
        $this->app_secret = $app_secret;
        return $this;
    }

    /**
     * @param $redirect_uri
     * @return $this
     */
    public function setRedirectUri($redirect_uri){
        $this->redirect_uri = $redirect_uri;
        return $this;
    }
}