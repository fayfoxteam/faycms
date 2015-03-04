<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Menus extends Table{
	protected $_name = 'menus';
	
	/**
	 * @return Menus
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('left_value', 'right_value'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array(array('title', 'sub_title', 'link'), 'string', array('max'=>255)),
			array(array('target'), 'string', array('max'=>30)),
			
			array('title', 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'parent'=>'Parent',
			'sort'=>'Sort',
			'left_value'=>'Left Value',
			'right_value'=>'Right Value',
			'alias'=>'别名',
			'title'=>'标题',
			'sub_title'=>'二级标题',
			'link'=>'连接地址',
			'target'=>'打开方式',
		);
	}

	public function filters(){
		return array(
			'parent'=>'intval',
			'sort'=>'intval',
			'left_value'=>'intval',
			'right_value'=>'intval',
			'alias'=>'trim',
			'title'=>'trim',
			'sub_title'=>'trim',
			'link'=>'trim',
			'target'=>'trim',
		);
	}
}