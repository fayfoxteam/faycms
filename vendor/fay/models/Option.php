<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Options;

class Option extends Model{
	/**
	 * @param string $className
	 * @return Option
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 获取一个参数
	 * @param string $name 参数名
	 * @param string $default 若不存在，返回默认值
	 * @return mixed
	 */
	public static function get($name, $default = null){
		$option = Options::model()->fetchRow(array('option_name = ?'=>$name), 'option_value');
		if($option){
			return $option['option_value'];
		}else{
			return $default;
		}
	}
	
	/**
	 * 设置一个参数
	 * @param string $name 参数名
	 * @param mixed $value 参数值
	 */
	public static function set($name, $value){
		$option = Options::model()->fetchRow(array('option_name = ?'=>$name), 'option_value');
		if($option){
			Options::model()->update(array(
				'option_value'=>$value,
				'last_modified_time'=>\F::app()->current_time,
			), array(
				'option_name = ?'=>$name,
			));
		}else{
			Options::model()->insert(array(
				'option_name'=>$name,
				'option_value'=>$value,
				'create_time'=>\F::app()->current_time,
			));
		}
	}
	
	/**
	 * 根据配置项前缀获取配置（返回数组的key不会包含前缀部分）
	 * @param 配置项前缀 $name
	 */
	public static function getTeam($name){
		$options = Options::model()->fetchAll(array('option_name LIKE ?'=>$name.'%'), 'option_name,option_value');
		$return = array();
		foreach($options as $o){
			$return[substr($o['option_name'], strlen($name) + 1)] = $o['option_value'];
		}
		
		return $return;
	}
}