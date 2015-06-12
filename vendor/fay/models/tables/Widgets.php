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
			array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('widgetarea_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('enabled'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('alias', 'widget_name', 'description'), 'string', array('max'=>255)),
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
			'widgetarea_id'=>'小工具域ID',
			'sort'=>'排序值',
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
			'widgetarea_id'=>'intval',
			'sort'=>'intval',
		);
	}
}