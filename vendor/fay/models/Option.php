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
	
	public static function get($name, $default = null){
		$option = Options::model()->fetchRow(array('option_name = ?'=>$name), 'option_value');
		if($option){
			return $option['option_value'];
		}else{
			return $default;
		}
	}
	
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
}