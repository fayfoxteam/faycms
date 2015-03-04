<?php
namespace fay\core;

/**
 * 抛出一个异常
 */
class Exception extends \Exception{
	public $description;
	
	public function __construct($message, $description = '', $code = E_USER_ERROR, \Exception $previous = null){
		parent::__construct($message, $code, $previous);
		$this->description = $description;
	}
	
	/**
	 * 判断是否为致命错误
	 */
	public static function isFatalError($error){
		return isset($error['type']) && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, E_USER_ERROR));
	}
	
	/**
	 * 返回一个比较友好的报错描述
	 */
	public function getLevel(){
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
	
		return isset($levels[$this->getCode()]) ? $levels[$this->getCode()] : 'Error';
	}
}