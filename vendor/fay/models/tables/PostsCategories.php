<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PostsCategories extends Table{
	protected $_name = 'posts_categories';
	protected $_primary = array('post_id', 'cat_id');
	
	/**
	 * @return PostsCategories
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
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