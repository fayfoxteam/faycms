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
	 * @param ErrorException|Exception|HttpException $exception
	 */
	public function handleException($exception){
		if($exception instanceof HttpException){//http错误，一般都是404
			//错误日志
			if($exception->status_code == 404){//如文章不存在等
				\F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'app_access');
			}else{//如用户参数异常，报500
				\F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'app_error');
			}
			
			//自定义Http异常
			Response::setStatusHeader($exception->status_code);
			//404, 500等http错误
			if(\F::config()->get('environment') == 'production'){
				if($exception->status_code == 404){
					$this->render404($exception);
				}else{
					$this->render500($exception);
				}
			}else{
				//环境非production，显示debug页面
				$this->renderDebug($exception);
			}
		}else if($exception instanceof ErrorException){//php报错
			//错误日志
			\F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'php_error');
			
			//自定义Http异常
			Response::setStatusHeader(500);
			
			//自定义异常
			if(\F::config()->get('environment') == 'production'){
				$this->render500($exception);
			}else{
				$this->renderDebug($exception);
			}
		}else if($exception instanceof Exception){//业务逻辑报错
			//错误日志
			\F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'app_error');
			
			//自定义Http异常
			Response::setStatusHeader(500);
			
			//自定义异常
			if(\F::config()->get('environment') == 'production'){
				$this->render500($exception);
			}else{
				$this->renderDebug($exception);
			}
		}else{//默认为php报错
			//错误日志
			\F::logger()->log((string)$exception, Logger::LEVEL_ERROR, 'php_error');
			
			//自定义Http异常
			Response::setStatusHeader(500);
			
			//其它（php或者其他一些类库）抛出的异常
			if(\F::config()->get('environment') == 'production'){
				$this->render500($exception);
			}else{
				$this->renderDebug($exception);
			}
		}
	}
	
	/**
	 * 处理php报错
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
				$this->render500();
			}else{
				$this->renderDebug($exception);
			}
			die;
		}
	}
	
	/**
	 * @param ErrorException $exception
	 */
	protected function renderDebug($exception){
		//清空缓冲区
		$this->clearOutput();
		
		if(\F::input()->isAjaxRequest()){
			if($exception instanceof HttpException && $exception->status_code == 404){
				Response::json('', 0, $exception->getMessage(), $exception->description ? $exception->description : 'http_error:404:not_found');
			}else{
				Response::json('', 0, $exception->getMessage(), $exception->description ? $exception->description : 'http_error:500:internal_server_error');
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
	 */
	protected function render404($exception){
		//清空缓冲区
		$this->clearOutput();
		
		if(\F::input()->isAjaxRequest()){
			Response::json('', 0, $exception->getMessage(), $exception->description ? $exception->description : 'http_error:404:not_found');
		}else{
			$this->app->view->renderPartial('errors/404', array(
				'message'=>$exception->getMessage(),
			));
		}
		die;
	}
	
	/**
	 * 显示500页面（不包含错误信息）
	 */
	protected function render500($exception){
		//清空缓冲区
		$this->clearOutput();
		
		if(\F::input()->isAjaxRequest()){
			Response::json('', 0, $exception->getMessage(), $exception->description ? $exception->description : 'http_error:500:internal_server_error');
		}else{
			$this->app->view->renderPartial('errors/500', array(
				'message'=>$exception->getMessage(),
			));
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