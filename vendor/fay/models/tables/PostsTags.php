<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PostsTags extends Table{
	protected $_name = 'posts_tags';
	protected $_primary = array('post_id', 'tag_id');
	
	/**
	 * @param string $class_name
	 * @return PostsTags
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'tag_id'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'Post Id',
			'tag_id'=>'Tag Id',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'tag_id'=>'intval',
		);
	}
}