<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PostsCategoriesTable extends Table{
	protected $_name = 'posts_categories';
	protected $_primary = array('post_id', 'cat_id');
	
	/**
	 * @param string $class_name
	 * @return PostsCategoriesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'Post Id',
			'cat_id'=>'Cat Id',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'cat_id'=>'intval',
		);
	}
}