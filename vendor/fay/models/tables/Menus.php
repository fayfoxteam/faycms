<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Menus model
 *
 * @property int $id
 * @property int $parent
 * @property int $sort
 * @property int $left_value
 * @property int $right_value
 * @property string $alias
 * @property string $title
 * @property string $sub_title
 * @property string $css_class
 * @property int $enabled
 * @property string $link
 * @property string $target
 */
class Menus extends Table{
	/**
	 * 后台菜单集合
	 */
	const ITEM_ADMIN_MENU = 1;

	/**
	 * 用户自定义菜单集合
	 */
	const ITEM_USER_MENU = 2;
	
	protected $_name = 'menus';
	
	/**
	 * @return Menus
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('left_value', 'right_value'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('enabled'), 'range', array('range'=>array(0, 1))),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array(array('css_class'), 'string', array('max'=>50, 'format'=>'alias_space')),
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
			'css_class'=>'CSS Class',
			'enabled'=>'是否启用',
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
			'css_class'=>'trim',
			'enabled'=>'intval',
			'link'=>'trim',
			'target'=>'trim',
		);
	}
}