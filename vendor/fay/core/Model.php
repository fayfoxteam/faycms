<?php
namespace fay\core;

use fay\core\FBase;

class Model extends FBase{
	/**
	 * @var Db
	 */
	public $db = null;
	
	private static $_models = array();
	
	public function __construct(){
		$this->db = Db::getInstance();
	}
	
	/**
	 * 获取一个model实例（单例模式）
	 * @param string $class_name
	 */
	public static function model($class_name = __CLASS__){
		if(isset(self::$_models[$class_name])){
			return self::$_models[$class_name];
		}else{
			return self::$_models[$class_name] = new $class_name();
		}
	}
	
	/**
	 * 返回验证规则
	 * @return array
	 */
	public function rules(){
		return array();
	}
	
	/**
	 * 返回字段描述
	 * @return array
	 */
	public function labels(){
		return array();
	}
	
	/**
	 * 返回验证器
	 * @return array
	 */
	public function filters(){
		return array();
	}
}