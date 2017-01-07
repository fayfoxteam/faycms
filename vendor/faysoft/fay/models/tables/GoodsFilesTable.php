<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods Files model
 * 
 * @property int $goods_id
 * @property int $file_id
 * @property string $description
 * @property int $sort
 * @property int $create_time
 */
class GoodsFilesTable extends Table{
	protected $_name = 'goods_files';
	protected $_primary = array('goods_id', 'file_id');
	
	/**
	 * @param string $class_name
	 * @return GoodsFilesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('goods_id', 'file_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('description'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'goods_id'=>'商品Id',
			'file_id'=>'文件Id',
			'description'=>'描述',
			'sort'=>'排序',
			'create_time'=>'创建时间',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'file_id'=>'intval',
			'description'=>'trim',
			'sort'=>'intval',
		);
	}
}