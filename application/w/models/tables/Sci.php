<?php
namespace w\models\tables;

use fay\core\db\Table;

class Sci extends Table{
	protected $_name = 'sci';
	
	/**
	 * @return sci
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
		);
	}

	public function labels(){
		return array(
		);
	}

	public function filters(){
		return array(
		);
	}
}