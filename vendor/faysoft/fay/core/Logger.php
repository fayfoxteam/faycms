<?php
namespace fay\core;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class Logger{
    private static $loggers = [];

    /**
     * 获取一个Monolog Logger实例
     * @param string $name
     * @param bool $file_handler 若为true，则会默认绑定一个以$name+日期为文件名的handler。仅在首次调用时有效
     * @return \Monolog\Logger
     */
    public static function get($name, $file_handler = true){
        if(empty(self::$loggers[$name])){
            $logger = new \Monolog\Logger('name');
            if($file_handler){
                //初始化一个记录到文件的handler
                $handler = new StreamHandler(APPLICATION_PATH . "runtimes/logs/{$name}-" . date('Y-m-d') . '.log');
                //默认行尾会有2个空数组JSON，这里指定一下若为空不写进日志
                $handler->setFormatter(new LineFormatter(null, null, true, true));
                $logger->pushHandler($handler);
            }

            self::$loggers[$name] = $logger;
        }

        return self::$loggers[$name];
    }
}