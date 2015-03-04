<?php
namespace cms\library;

/**
 * 继承自系统Db类，该类不使用单例模式
 */
class Db extends \fay\core\Db{
	public function __construct($config){
		$this->init($config);
	}
}