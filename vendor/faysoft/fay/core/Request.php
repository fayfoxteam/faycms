<?php
namespace fay\core;

class Request{
    /**
     * 返回当前请求方式，例如：GET, POST（纯大写）
     * @return string
     */
    public static function getMethod(){
        return strtoupper(
            self::getServer(
                'HTTP_X_HTTP_METHOD_OVERRIDE',
                self::getServer(
                    'REQUEST_METHOD',
                    'GET'
                )
            )
        );
    }

    /**
     * 判断是不是POST请求
     * @return bool
     */
    public static function isPost(){
        return self::getMethod() == 'POST';
    }
    
    /**
     * 从$_SERVER数组中获取一个值
     * @param string $key
     * @param null $default
     * @return string
     */
    public static function getServer($key, $default = null){
        if(null === $key){
            return $_SERVER;
        }

        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

    /**
     * 判断是否为ajax访问
     * @return bool
     */
    public static function isAjax(){
        //若在参数中指定为ajax，则直接返回true（一般用于调试）
        if(\F::input()->request('ajax')){
            return true;
        }else{
            if(self::getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest' ||
                self::getServer('HTTP_POSTMAN_TOKEN')//postman发起的请求视为ajax请求
            ){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 是否https访问
     * @return boolean
     */
    public static function isSecure(){
        return self::getScheme() === 'https';
    }

    /**
     * 获取请求协议
     * @return string
     */
    public static function getScheme(){
        return self::getServer('HTTPS') == 'on' ? 'https' : 'http';
    }

    private static $_rawBody;

    /**
     * 获取php://input（中文不太好翻译）
     * @return string
     */
    public static function getRawBody(){
        if (self::$_rawBody === null) {
            self::$_rawBody = file_get_contents('php://input');
        }

        return self::$_rawBody;
    }

    /**
     * 一般用于模拟测试php://input
     * @param string $rawBody
     */
    public static function setRawBody($rawBody){
        self::$_rawBody = $rawBody;
    }

    /**
     * 获取Host
     * @return string
     */
    public static function getHost(){
        return self::getServer(
            'HTTP_X_FORWARDED_HOST',
            self::getServer(
                'HTTP_HOST'
            )
        );
    }

    /**
     * 猜测$base_url
     * @return string
     */
    public static function getBaseUrl(){
        $document_root = $_SERVER['DOCUMENT_ROOT'];
        $document_root = rtrim($document_root, '\\/');//由于服务器配置不同，有的DOCUMENT_ROOT末尾带斜杠，有的不带，这里统一去掉末尾斜杠
        $folder = dirname(str_replace($document_root, '', $_SERVER['SCRIPT_FILENAME']));
        //所有斜杠都以正斜杠为准
        $folder = str_replace('\\', '/', $folder);
        if(substr($folder, -7) == '/public'){
            $folder = substr($folder, 0, -7);
        }
        if($folder == '/'){
            //仅剩一根斜杠的时候（把根目录设到public目录下的情况），设为空
            $folder = '';
        }
        $base_url = self::getScheme() .
            '://' .
            self::getHost() .
            $folder .
            '/';
        if(defined('NO_REWRITE')){
            $base_url .= 'index.php/';
        }

        return $base_url;
    }

    /**
     * 获取当前请求的url
     * @return string
     */
    public static function getCurrentUrl(){
        return self::getScheme() . '://'. self::getHost() . self::getServer('REQUEST_URI');
    }

    /**
     * 获取用户IP
     * 此方法会选择信任HTTP_X_FORWARDED_FOR字段，也就是说，得到的IP可能是伪造的
     * @return string|null
     */
    public static function getUserIP(){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $arr = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach($arr as $ip){
                $ip = trim($ip);
                //此验证器会判断必须是ip格式，且非内网IP
                if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                    return $ip;
                }
            }
        }
        
        return self::getServer('REMOTE_ADDR');
    }

    /**
     * 获取来源
     * @return string 
     */
    public static function getReferrer(){
        return self::getServer('HTTP_REFERER', '');
    }

    /**
     * 获取User Agent
     * @return string
     */
    public static function getUserAgent(){
        return self::getServer('HTTP_USER_AGENT', '');
    }
}