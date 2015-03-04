<?php
namespace fay\models\tables;

use fay\core\db\Table;

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
			array(array('id', 'file_id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('goods_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('position'), 'int', array('min'=>0, 'max'=>255)),
			array(array('desc'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'goods_id'=>'Goods Id',
			'file_id'=>'File Id',
			'desc'=>'Desc',
			'position'=>'Position',
			'create_time'=>'Create Time',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'file_id'=>'intval',
			'desc'=>'trim',
			'position'=>'intval',
			'create_time'=>'',
		);
	}
}