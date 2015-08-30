<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Widgets extends Table{
	protected $_name = 'widgets';
	
	/**
	 * @return Widgets
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('enabled', 'ajax', 'cache'), 'range', array('range'=>array('0', '1'))),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('alias', 'widget_name', 'description'), 'string', array('max'=>255)),
			array(array('widgetarea'), 'string', array('max'=>50)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'alias'=>'别名',
			'options'=>'实例参数',
			'widget_name'=>'小工具名称',
			'description'=>'小工具描述',
			'enabled'=>'是否启用',
			'widgetarea'=>'小工具域',
			'sort'=>'排序值',
			'ajax'=>'是否ajax引入',
			'cache'=>'是否缓存',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'alias'=>'trim',
			'options'=>'',
			'widget_name'=>'trim',
			'description'=>'trim',
			'enabled'=>'intval',
			'widgetarea'=>'trim',
			'sort'=>'intval',
			'ajax'=>'intval',
			'cache'=>'intval',
		);
	}
}