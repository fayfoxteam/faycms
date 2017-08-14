<?php
namespace fay\core;

use fay\log\Logger;

class ErrorHandler{
    /**
     * @var View
     */
    protected $view;
    
    public function __construct(){
        $app = \F::app();
        $this->view = $app ? $app->view : new View();
    }
    
    /**
     * 接管PHP自带报错
     */
    public function register(){
        ini_set('display_errors', false);
        set_exception_handler(array($this, 'handleException'));
        set_error_handler(array($this, 'handleError'));
        register_shutdown_function(array($this, 'handleFatalError'));
    }
    
    /**
     * 处理未捕获的异常
     * @param \Exception $exception
     */
    public function handleException($exception){
        $this->reportException($exception);
        $this->renderException($exception);
    }
    
    /**
     * 处理php报错
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function handleError($code, $message, $file, $line){
        if(error_reporting() == 0){
            //例如@屏蔽报错的时候，error_reporting()会返回0
            return;
        }

        //这里都是些notice之类的报错，直接输出
        echo $this->view->renderPartial('errors/php', array(
            'level'=>self::getErrorLevel($code),
            'message'=>$message,
            'file'=>$file,
            'line'=>$line,
        ));
    }
    
    /**
     * 处理致命错误
     */
    public function handleFatalError(){
        $error = error_get_last();
        if($error && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING))){
            //致命错误，当成异常处理
            $exception = new \ErrorException($error['message'], $error['type'], $error['file'], $error['line'], $error['type']);
            $this->handleException($exception);
        }
    }

    /**
     * 输出异常
     * @param \Exception $exception
     */
    protected function renderException($exception){
        //清空缓冲区
        $response = new Response();
        $response->clearOutput();
        if(\F::config()->get('environment') == 'production'){
            //线上环境
            $response->setData(array(
                'status'=>0,
                'data'=>'',
                'message'=>$exception instanceof db\DBException ? '数据库错误' : $exception->getMessage(),
                'code'=>$exception->getDescription() ? $exception->getDescription() : 'http_error:500:internal_server_error',
            ))
                ->setStatusCode(isset($exception->status_code) ? $exception->status_code : 500)
                ->setContent($this->view->renderPartial('errors/500', array(
                    'exception'=>$exception,
                )))
            ;
            if(Request::isAjax()){
                $response->setFormat(Response::FORMAT_JSON);
            }

            $response->send();
        }else{
            //开发环境
            $response->setData(array(
                'status'=>0,
                'data'=>'',
                'message'=>$exception->getMessage(),
                'code'=>'',
            ))
                ->setStatusCode(isset($exception->status_code) ? $exception->status_code : 500)
                ->setContent($this->view->renderPartial('errors/debug', array(
                    'exception'=>$exception,
                )))
            ;
            if(Request::isAjax()){
                $response->setFormat(Response::FORMAT_JSON);
            }
            
            $response->send();
        }
    }

    /**
     * 记录异常
     * @param \Exception $exception
     */
    protected function reportException($exception){
        \F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'app_error');
    }

    /**
     * 获取错误级别描述
     * @param int $code
     * @return string
     */
    public static function getErrorLevel($code){
        $levels = array(
            E_ERROR => 'PHP Fatal Error',
            E_PARSE => 'PHP Parse Error',
            E_CORE_ERROR => 'PHP Core Error',
            E_COMPILE_ERROR => 'PHP Compile Error',
            E_USER_ERROR => 'PHP User Error',
            E_WARNING => 'PHP Warning',
            E_CORE_WARNING => 'PHP Core Warning',
            E_COMPILE_WARNING => 'PHP Compile Warning',
            E_USER_WARNING => 'PHP User Warning',
            E_STRICT => 'PHP Strict Warning',
            E_NOTICE => 'PHP Notice',
            E_RECOVERABLE_ERROR => 'PHP Recoverable Error',
            E_DEPRECATED => 'PHP Deprecated Warning',
        );
        
        return isset($levels[$code]) ? $levels[$code] : 'Error';
    }
}