<?php
namespace fayoauth\services;

abstract class AccessTokenAbstract{
    /**
     * 应用ID
     */
    protected $app_id;
    
    /**
     * 其他参数
     */
    protected $params;
    
    /**
     * @param string $app_id 应用ID
     * @param array $params 其他参数
     */
    public function __construct($app_id, $params){
        $this->app_id = $app_id;
        
        $this->params = empty($params) ? array() : $params;
    }
    
    /**
     * 获取应用ID
     * @return string
     */
    public function getAppId(){
        return $this->app_id;
    }
    
    /**
     * 获取openId
     * QQ、微信、微博字段名都叫openid。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return array
     */
    public function getOpenId(){
        return $this->getParam('openid');
    }
    
    /**
     * 获取Access Token。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return string
     */
    public function getAccessToken(){
        return $this->getParam('access_token');
    }
    
    /**
     * 获取Access Token过期时间时间戳。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return string
     */
    public function getExpires(){
        $expires = $this->getParam('expires_in');
        return $expires ? intval($expires) + \F::app()->current_time : '';
    }
    
    /**
     * 获取Refresh Token。
     * 若第三方字段名特殊，可在子类中重写此方法。
     * @return string
     */
    public function getRefreshToken(){
        return $this->getParam('refresh_token');
    }
    
    /**
     * 获取$this->params参数
     * @param string $key
     * @return string
     */
    public function getParam($key){
        return isset($this->params[$key]) ? $this->params[$key] : '';
    }
    
    /**
     * 拉取用户信息
     * @return UserAbstract
     */
    abstract public function getUser();
    
    /**
     * 刷新access_token
     * @return $this
     */
    abstract public function refresh();
    
    /**
     * 检验授权凭证（access_token）是否有效
     * @return bool
     */
    abstract public function check();
}