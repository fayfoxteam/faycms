<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PagesCategories extends Table{
	protected $_name = 'pages_categories';
	protected $_primary = array('page_id', 'cat_id');
	
	/**
	 * @return PagesCategories
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('page_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
		);
	}

	public function labels(){
		return array(
			'page_id'=>'Page Id',
			'cat_id'=>'Cat Id',
		);
	}

	public function filters(){
		return array(
			'page_id'=>'intval',
			'cat_id'=>'intval',
		);
	}
}