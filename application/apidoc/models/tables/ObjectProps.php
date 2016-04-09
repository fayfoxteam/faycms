<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Object Props model
 * 
 * @property int $id Id
 * @property int $object_id 所属对象ID
 * @property string $name 属性名称
 * @property int $type 类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property string $since 自从
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 */
class ObjectProps extends Table{
	protected $_name = 'apidoc_object_props';
	
	/**
	 * @return ObjectProps
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('object_id', 'type'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('since'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'object_id'=>'所属对象ID',
			'name'=>'属性名称',
			'type'=>'类型',
			'sample'=>'示例值',
			'description'=>'描述',
			'since'=>'自从',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'object_id'=>'intval',
			'name'=>'trim',
			'type'=>'intval',
			'sample'=>'',
			'description'=>'',
			'since'=>'trim',
		);
	}
}