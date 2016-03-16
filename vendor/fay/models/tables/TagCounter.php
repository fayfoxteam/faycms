<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Tag Counter model
 * 
 * @property int $tag_id 标签ID
 * @property int $posts 文章数
 * @property int $feeds 动态数
 */
class TagCounter extends Table{
	protected $_name = 'tag_counter';
	protected $_primary = 'tag_id';
	
	/**
	 * @return TagCounter
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('tag_id', 'posts', 'feeds'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'tag_id'=>'标签ID',
			'posts'=>'文章数',
			'feeds'=>'动态数',
		);
	}

	public function filters(){
		return array(
			'tag_id'=>'intval',
			'posts'=>'intval',
			'feeds'=>'intval',
		);
	}
}