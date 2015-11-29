<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Options;
use fay\helpers\ArrayHelper;

class Option extends Model{
	/**
	 * @param string $className
	 * @return Option
	 */
	public static function model($class_name = __CLASS__){
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
	 * 一次性获取多个参数
	 * @param string|array $names 允许是逗号分割的字符串，或者数组
	 * @return 返回以name项作为key的数组，若name不存在则不返回对应的key
	 */
	public static function mget($names){
		if(is_string($names)){
			$names = explode(',', str_replace(' ', '', $names));
		}
		$options = Options::model()->fetchAll(array('option_name IN (?)'=>$names), 'option_name,option_value');
		$return = ArrayHelper::column($options, 'option_value', 'option_name');
		foreach($names as $n){
			if($n && !isset($return[$n])){
				$return[$n] = null;
			}
		}
		return $return;
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