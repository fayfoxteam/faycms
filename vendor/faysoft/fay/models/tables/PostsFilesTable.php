<?php
namespace fay\models\tables;

use fay\core\db\Table;

class PostsFilesTable extends Table{
	protected $_name = 'posts_files';
	protected $_primary = array('post_id', 'file_id');
	
	/**
	 * @param string $class_name
	 * @return PostsFilesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'file_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('description'), 'string', array('max'=>255)),
			array(array('is_image'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'文章ID',
			'file_id'=>'文件ID',
			'description'=>'附件描述',
			'is_image'=>'是否为图片',
			'sort'=>'排序值',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'file_id'=>'intval',
			'description'=>'trim',
			'is_image'=>'intval',
			'sort'=>'intval',
		);
	}
}