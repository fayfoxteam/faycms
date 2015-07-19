<?php
namespace w\models\tables;

use fay\core\db\Table;

class Sci extends Table{
	protected $_name = 'w_sci';
	
	/**
	 * @return sci
	 */
	public static function model($className=__CLASS__){
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