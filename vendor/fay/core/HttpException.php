<?php
namespace fay\core;

class HttpException extends \Exception{
	/**
	 * 这个字段在这里并没有什么用，只是为了与ErrorException一致
	 */
	public $description;
	
	/**
	 * @var integer HTTP 状态码, 例如403, 404, 500等
	 */
	public $status_code;

	public function __construct($message = null, $http_status = 404, $code = 0, \Exception $previous = null){
		$this->status_code = $http_status;
		$message || $message = $this->getLevel();
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return 返回一个状态码描述
	 */
	public function getLevel(){
		if (isset(Response::$httpStatuses[$this->status_code])) {
			return Response::$httpStatuses[$this->status_code];
		} else {
			return 'Error';
		}
	}
}
