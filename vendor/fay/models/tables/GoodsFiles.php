<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods Files model
 *
 * @property int $id
 * @property int $goods_id
 * @property int $file_id
 * @property string $description
 * @property int $sort
 * @property int $create_time
 */
class GoodsFiles extends Table{
	protected $_name = 'goods_files';
	
	/**
	 * @return GoodsFiles
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'goods_id', 'file_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('description'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'goods_id'=>'商品Id',
			'file_id'=>'文件Id',
			'description'=>'描述',
			'sort'=>'排序',
			'create_time'=>'Create Time',
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