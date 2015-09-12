<?php
namespace fay\core;

class HttpException extends \Exception
{
	/**
	 * @var integer HTTP 状态码, 例如403, 404, 500等
	 */
	public $statusCode;

	public function __construct($message = null, $http_status = 404, $code = 0, \Exception $previous = null){
		$this->statusCode = $http_status;
		$message || $message = $this->getLevel();
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return 返回一个状态码描述
	 */
	public function getLevel(){
		if (isset(Response::$httpStatuses[$this->statusCode])) {
			return Response::$httpStatuses[$this->statusCode];
		} else {
			return 'Error';
		}
	}
}
