<?php
namespace fay\core;

class Loader{
    /**
     * @var array
     */
    private static $_instances = array();
    
    /**
     * 自动加载类库
     * @param string $class_name 类名
     * @return bool
     */
    public static function autoload($class_name){
        $first_namespace = substr($class_name, 0, strpos($class_name, '\\'));
        if($first_namespace && file_exists(FAYSOFT_PATH . $first_namespace)){
            $file_path = str_replace('\\', '/', FAYSOFT_PATH.$class_name.'.php');
            if(file_exists($file_path)){
                require $file_path;
                return true;
            }
        }else if(strpos($class_name, APPLICATION) === 0){
            $file_path = str_replace('\\', '/', APPLICATION_PATH.substr($class_name, strlen(APPLICATION)).'.php');
            if(file_exists($file_path)){
                require $file_path;
                return true;
            }
        }
    }
    
    /**
     * 引入一个第三方文件
     * 理论上composer标准类库都是支持autoload的，但是有时候需要用到一些并不规范的类库
     * 本质上是从vendor文件夹包含一个文件进来
     * @param string $name
     * @throws \ErrorException
     */
    public static function vendor($name){
        if(file_exists(APPLICATION_PATH . "{$name}.php")){
            require_once APPLICATION_PATH . "{$name}.php";
        }else if(file_exists(VENDOR_PATH . "{$name}.php")){
            require_once VENDOR_PATH . "{$name}.php";
        }else{
            throw new \ErrorException("File '{$name}' not found");
        }
    }
    
    /**
     * 获取一个单例（Model和Service事实上最终都是在调用此方法获取单例）
     * @param string $class_name
     * @return mixed
     */
    public static function singleton($class_name = __CLASS__){
        if(isset(self::$_instances[$class_name])){
            return self::$_instances[$class_name];
        }else{
            return self::$_instances[$class_name] = new $class_name();
        }
    }
}