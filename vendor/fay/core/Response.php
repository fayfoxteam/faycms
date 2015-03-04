<?php
namespace fay\core;

use fay\core\FBase;

class Response extends FBase{
	/**
	 * HTTP状态码
	 */
	public static $httpStatuses = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		118 => 'Connection timed out',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		210 => 'Content Different',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		310 => 'Too many Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested range unsatisfiable',
		417 => 'Expectation failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable entity',
		423 => 'Locked',
		424 => 'Method failure',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway ou Proxy Error',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
		507 => 'Insufficient storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
	);
	
	/**
	 * 当Exception不好用的时候，用此函数显示错误
	 * @param string $message
	 * @param int $status_code http状态码
	 * @param string $heading
	 */
	public static function showError($message, $status_code = 500, $heading = '出错啦'){
		self::setStatusHeader($status_code);
		$app = \F::app();
		$app || $app = new Controller();
		$app->view->assign(array(
			'message'=>$message,
			'status_code'=>$status_code,
			'heading'=>$heading,
		));
		$app->view->_backtrace = debug_backtrace(false);
		$app->view->renderPartial('errors/general');
		die;
	}
	
	/**
	 * 发送一个http头
	 * @param int $code
	 * @param string $text
	 */
	public static function setStatusHeader($code = 200, $text = ''){
		if ($code == '' OR ! is_numeric($code)){
			self::showError('Status codes must be numeric', 500);
		}
	
		if (isset(self::$httpStatuses[$code]) AND $text == ''){
			$text = self::$httpStatuses[$code];
		}
	
		if ($text == ''){
			self::showError('No status text available.  Please check your status code number or supply your own message text.', 500);
		}
	
		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
	
		if (substr(php_sapi_name(), 0, 3) == 'cgi'){
			header("Status: {$code} {$text}", TRUE);
		}elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0'){
			header($server_protocol." {$code} {$text}", TRUE, $code);
		}else{
			header("HTTP/1.1 {$code} {$text}", TRUE, $code);
		}
	}
	
	/**
	 * 页面跳转
	 * @param string $uri
	 * @param array $params
	 */
	public static function redirect($uri = null, $params = array(), $url_rewrite = true){
		if($uri === null){
			header('location:'.\F::app()->view->url(null));
		}else{
			header('location:'.\F::app()->view->url($uri, $params, $url_rewrite));
		}
		die;
	}
	
	/**
	 * 返回上一页
	 */
	public static function goback(){
		if(isset($_SERVER['HTTP_REFERER'])){
			header('location:'.$_SERVER['HTTP_REFERER']);
		}else{
			echo '<script>history.go(-1);</script>';
		}
		die;
	}
	
	/**
	 * 在非显示性页面调用此方法输出。
	 * 若为ajax访问，则返回json
	 * 若是浏览器访问，则设置flash后跳转
	 * @param string $status 状态success, error
	 * @param array|string $data
	 * @param bool|array $redirect 跳转地址，若为false且是浏览器访问，则返回上一页
	 */
	public static function output($status = 'error', $data = array(), $redirect = false){
		if(!is_array($data)){
			$data = array(
				'message'=>$data,
			);
		}
		if(\F::app()->input->isAjaxRequest()){
			echo json_encode(array(
				'status'=>$status == 'success' ? 1 : 0,
			)+$data);
			die;
		}else{
			if(!empty($data['message'])){
				//若设置了空 的message，则不发flash
				\F::app()->flash->set($data['message'], $status);
			}else if($status == 'success'){
				\F::app()->flash->set('操作成功', $status);
			}else{
				\F::app()->flash->set('操作失败', $status);
			}
			
			if($redirect === false){
				self::goback();
			}else{
				if(is_array($redirect)){
					$redirect = \F::app()->view->url($redirect[0],
						empty($redirect[1]) ? array() : $redirect[1],
						isset($redirect[2]) && $redirect[2] === false ? false : true);
				}
				header('location:'.$redirect);
				die;
			}
		}
	}
}