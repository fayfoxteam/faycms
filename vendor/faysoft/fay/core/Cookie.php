<?php
namespace fay\core;

class Cookie{
    private static $_instance;
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 设置cookie
     * @param string $name Cookie名，实际设置的Cookie会带上配置文件中设置的前缀
     * @param string $value Cookie值
     * @param string $expire 过期时间
     *   - 若为数字，则是相对于当前时间的偏移（负数实际上是立马过期）
     *   - 若为null则随着浏览器关闭过期
     *   - 其他类型（字符串，布尔型等）立马过期
     * @param string $path 与系统setcookie函数参数一致
     * @param bool|string $domain 与系统setcookie函数参数一致
     * @param string $secure 与系统setcookie函数参数一致
     * @param string $httponly 与系统setcookie函数参数一致
     * @param string $cookie_prefix Cookie前缀，若为null，则根据配置文件设置
     * @return bool
     */
    public function set($name, $value = null, $expire = null, $path = '/', $domain = false, $secure = null, $httponly = null, $cookie_prefix = null){
        if($cookie_prefix === null){
            $cookie_prefix = \F::config()->get('cookie_prefix');
        }
        
        if($expire !== null){
            if(is_numeric($expire)){
                $expire = \F::app()->current_time + $expire;
            }else{
                //若为字符串，布尔值等，则直接过期
                $expire = \F::app()->current_time - 86500;
            }
        }
        
        setcookie($cookie_prefix . $name, $value, $expire, $path, $domain, $secure, $httponly);
        return true;
    }
    
    /**
     * 获取cookie
     * @param string|null|array $key Cookie名，实际获取的时候会带上$cookie_prefix前缀
     *   - 若为null，则以键值数组返回所有符合前缀的Cookie，数组key为不带$cookie_prefix前缀的Cookie名
     *   - 若为数组，则以键值数组返回所有指定的Cookie，数组key为不带$cookie_prefix前缀的Cookie名
     *   - 若为字符串，则以字符串返回指定Cookie
     * @param string $filter 过滤器，与get/post参数一致
     * @param string $default 默认值，当指定Cookie不存在的时候返回默认值
     * @param string $cookie_prefix Cookie前缀，若为null，则根据配置文件设置
     * @return mixed
     */
    public function get($key = null, $filter = null, $default = null, $cookie_prefix = null){
        if($cookie_prefix === null){
            $cookie_prefix = \F::config()->get('cookie_prefix');
        }
        if($filter){
            $filters = explode('|', $filter);
        }else{
            $filters = array();
        }
        if($key === null){
            //以键值数组返回所有符合前缀的Cookie，数组key为不带$cookie_prefix前缀的Cookie名
            if($cookie_prefix){
                $cookie_prefix_len = strlen($cookie_prefix);
                $cookies = array();
                foreach($_COOKIE as $k => $c){
                    if(substr($k, 0, $cookie_prefix_len) == $cookie_prefix){
                        $cookies[substr($k, $cookie_prefix_len)] = \F::filter($filters, $c);
                    }
                }
                return $cookies;
            }else{
                return \F::filter($filters, $_COOKIE);
            }
        }
        if(is_array($key)){
            //以键值数组返回所有指定的Cookie，数组key为不带$cookie_prefix前缀的Cookie名
            $return = array();
            foreach($key as $k){
                $return[$k] = isset($_COOKIE[$cookie_prefix . $k]) ? \F::filter($filters, $_COOKIE[$cookie_prefix . $k]) : null;
            }
            return $return;
        }else if(isset($_COOKIE[$cookie_prefix . $key])){
            //则以字符串返回指定Cookie
            return \F::filter($filters, $_COOKIE[$cookie_prefix . $key]);
        }else{
            //返回默认值
            return $default;
        }
    }
    
    /**
     * 移除指定Cookie
     * @param string $name
     * @param string $path 与系统setcookie函数参数一致
     * @param bool|string $domain 与系统setcookie函数参数一致
     */
    public function remove($name = '', $path = '/', $domain = false){
        $this->set($name, '', '', $path, $domain);
    }
}