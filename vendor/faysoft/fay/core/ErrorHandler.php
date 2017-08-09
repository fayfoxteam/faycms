<?php
namespace fay\core;

use fay\log\Logger;

class ErrorHandler{
    public $app;
    
    public function __construct(){
        $this->app = \F::app();
        $this->app || $this->app = new Controller();
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
     * @param Exception $exception
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
        
        $exception = new ErrorException($message, '', $code, $file, $line, $code);
        
        //错误日志
        \F::logger()->log((string)$exception, Logger::LEVEL_WARNING, 'php_error');
        
        $this->renderPHPError($exception);
    }
    
    /**
     * 处理致命错误
     */
    public function handleFatalError(){
        $error = error_get_last();
        
        if(ErrorException::isFatalError($error)){
            Response::setStatusHeader(500);
            
            $exception = new ErrorException($error['message'], '', $error['type'], $error['file'], $error['line'], $error['type']);
            //错误日志
            \F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'php_error');
            \F::logger()->flush();
            
            if(\F::config()->get('environment') == 'production'){
                $this->render500($exception);
            }else{
                $this->renderDebug($exception);
            }
            die;
        }
    }

    /**
     * 输出异常
     * @param \Exception $exception
     */
    protected function renderException($exception){
        if(\F::config()->get('environment') == 'production'){
            
        }else{
            $response = new Response();
            $response->setData(array(
                'status'=>0,
                'data'=>'',
                'message'=>$exception->getMessage(),
                'code'=>'',
            ))
                ->setStatusCode(isset($exception->status_code) ? $exception->status_code : 500)
                ->setContent($this->app->view->renderPartial('errors/debug', array(
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
     * @param ErrorException $exception
     */
    protected function renderDebug($exception){
        //清空缓冲区
        $this->clearOutput();
        
        if(\F::input()->isAjaxRequest()){
            if($exception instanceof HttpException && $exception->status_code == 404){
                return Response::json('', 0, $exception->getMessage(), !empty($exception->description) ? $exception->description : 'http_error:404:not_found');
            }else{
                return Response::json('', 0, $exception->getMessage(), !empty($exception->description) ? $exception->description : 'http_error:500:internal_server_error');
            }
        }else{
            $this->app->view->renderPartial('errors/debug', array(
                'exception'=>$exception,
            ));
        }
        die;
    }

    /**
     * @param ErrorException $exception
     */
    protected function renderPHPError($exception){
        $this->app->view->renderPartial('errors/php', array(
            'level'=>$exception->getLevel(),
            'message'=>$exception->getMessage(),
            'file'=>$exception->getFile(),
            'line'=>$exception->getLine(),
        ));
    }
    
    /**
     * 显示404页面（不包含错误信息）
     * @param HttpException $exception
     */
    protected function render404($exception){
        //清空缓冲区
        $this->clearOutput();
        
        if(\F::input()->isAjaxRequest()){
            return Response::json('', 0, $exception->getMessage(), $exception->description ? $exception->description : 'http_error:404:not_found');
        }else{
            $this->app->view->renderPartial('errors/404', array(
                'message'=>$exception->getMessage(),
            ));
        }
        die;
    }
    
    /**
     * 显示500页面（不包含错误信息）
     * @param ErrorException $exception
     */
    protected function render500($exception){
        //清空缓冲区
        $this->clearOutput();
        
        if(\F::config()->get('environment') == 'production'){
            if(\F::input()->isAjaxRequest()){
                return Response::json(
                    '',
                    0,
                    $exception instanceof db\Exception ? '数据库错误' : $exception->getMessage(),
                    $exception->getDescription() ? $exception->getDescription() : 'http_error:500:internal_server_error'
                );
            }else{
                $this->app->view->renderPartial('errors/500', array(
                    'message'=>$exception instanceof db\Exception ? '数据库错误' : $exception->getMessage(),
                ));
            }
        }else{
            if(\F::input()->isAjaxRequest()){
                return Response::json(
                    '',
                    0,
                    $exception->getMessage(),
                    $exception->getDescription() ? $exception->getDescription() : 'http_error:500:internal_server_error'
                );
            }else{
                $this->app->view->renderPartial('errors/500', array(
                    'message'=>$exception->getMessage(),
                ));
            }
        }
        die;
    }
    
    /**
     * 清楚所有未输出的缓冲区
     */
    public function clearOutput()
    {
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
}