<?php
namespace fay\models\forms;

use fay\core\Model;

class SettingForm extends Model{
	/**
	 * @param string $class_name
	 * @return SettingForm
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array('page_size', 'int', array('min'=>1, 'max'=>999)),
			array('_key', 'required'),
		);
	}

	public function labels(){
		return array(
			'page_size'=>'页面大小',
		);
	}

	public function filters(){
		return array(
			'page_size'=>'intval',
		);
	}
}