<?php
namespace fay\log;

class Logger{
    /**
     * 致命错误，此类错误会导致程序停止运行，绝不能出现
     */
    const LEVEL_ERROR = 0x01;
    
    /**
     * 警告，一般不影响程序继续运行，但尽量也不要出现
     */
    const LEVEL_WARNING = 0x02;
    
    /**
     * 消息，供开发者查看的一些消息
     */
    const LEVEL_INFO = 0x04;
    
    /**
     * 日志信息，二维数组
     * [
     *   [0] => message 描述
     *   [1] => level 等级，由Logger::LEVEL_*系列常量定义
     *   [2] => category 日志分类
     *   [3] => timestamp 微秒级时间戳
     *   [4] => traces 堆栈信息数组
     * ]
     */
    public $messages = array();
    
    /**
     * 当$this->message达到这个数字时，会被写入到日志文件（或别的什么容器）中
     */
    public $flush_interval = 1000;
    
    /**
     * 用于记录日志的容器实例，可以多个
     * @var array
     */
    public $targets = array();
    
    private static $_instance;
    
    private function __construct(){
        register_shutdown_function(array($this, 'flush'), true);
        
        //初始化日志容器
        $config = \F::config()->get('*', 'logs');
        foreach($config as $c){
            /**
             * @var $target \fay\log\Target
             */
            $target = new $c['class'];
            if(!empty($c['levels'])){
                $target->setLevels($c['levels']);
            }
            if(!empty($c['categories'])){
                $target->categories = $c['categories'];
            }
            if(!empty($c['options'])){
                $target->init($c['options']);
            }
            $this->targets[] = $target;
        }
    }
    
    private function __clone(){}
    
    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 记录日志
     * @param string $message 日志内容
     * @param int $level 日志级别
     * @param string $category 日志分类
     */
    public function log($message, $level = self::LEVEL_INFO, $category = 'app'){
        $time = microtime(true);
        //$traces = debug_backtrace();
        $traces = array();
        $this->messages[] = array($message, $level, $category, $time, $traces);
        if($this->flush_interval > 0 && count($this->messages) >= $this->flush_interval){
            $this->flush();
        }
    }
    
    /**
     * 将所有缓冲的日志记录到指定容器
     */
    public function flush(){
        $this->dispatch($this->messages);
        $this->messages = array();
    }
    
    /**
     * 将日志信息写入容器
     * @param array $messages 日志
     */
    private function dispatch($messages){
        foreach ($this->targets as $target) {
            if ($target->enabled) {
                $target->collect($messages);
            }
        }
    }
    
    /**
     * 获取日志级别对应的名称
     * @param int $level
     * @return string
     */
    public static function getLevelName($level){
        static $levels = array(
            self::LEVEL_ERROR => 'error',
            self::LEVEL_WARNING => 'warning',
            self::LEVEL_INFO => 'info',
        );
    
        return isset($levels[$level]) ? $levels[$level] : 'unknown';
    }
}