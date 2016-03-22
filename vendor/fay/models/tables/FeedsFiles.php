<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feeds Files model
 * 
 * @property int $post_id 动态ID
 * @property int $file_id 文件ID
 * @property string $description 描述
 * @property int $sort 排序值
 */
class FeedsFiles extends Table{
	protected $_name = 'feeds_files';
	protected $_primary = array('post_id', 'file_id');
	
	/**
	 * @return FeedsFiles
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'file_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('description'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'动态ID',
			'file_id'=>'文件ID',
			'description'=>'描述',
			'sort'=>'排序值',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'file_id'=>'intval',
			'description'=>'trim',
			'sort'=>'intval',
		);
	}
}