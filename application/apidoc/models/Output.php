<?php
namespace apidoc\models;

use fay\core\Model;
use apidoc\models\tables\Outputs;

class Output extends Model{
	/**
	 * @return Output
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取一个响应参数
	 * @param int $id
	 * @param string $fields
	 */
	public function get($id, $fields = '*'){
		return Outputs::model()->find($id, $fields);
	}
	
	/**
	 * 根据parent字段，获取一个模型的所有属性
	 * @param int $parent
	 */
	public function getByParent($parent, $fields){
		return Outputs::model()->fetchAll(array(
			'parent = ?'=>$parent,
		), $fields);
	}
}