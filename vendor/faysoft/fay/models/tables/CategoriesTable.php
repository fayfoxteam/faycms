<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Categories table model
 *
 * @property int $id Id
 * @property string $title 标题
 * @property string $alias 别名
 * @property int $parent 父节点
 * @property int $file_id 插图
 * @property int $sort 排序值
 * @property string $description 描述
 * @property int $is_nav 是否导航栏显示
 * @property int $count 记录数
 * @property int $left_value Left Value
 * @property int $right_value Right Value
 * @property int $is_system Is System
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 */
class CategoriesTable extends Table{
	protected $_name = 'categories';
	
	/**
	 * @param string $class_name
	 * @return CategoriesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('file_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'parent', 'count', 'left_value', 'right_value'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title', 'seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
			array(array('description'), 'string', array('max'=>500)),
			array(array('is_nav', 'is_system'), 'range', array('range'=>array(0, 1))),
			
			array('title', 'required'),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array('alias', 'unique', array('on'=>'create', 'table'=>'categories', 'field'=>'alias', 'ajax'=>array('admin/category/is-alias-not-exist'))),
			array('alias', 'unique', array('on'=>'edit', 'table'=>'categories', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/category/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'alias'=>'别名',
			'parent'=>'父节点',
			'file_id'=>'插图',
			'sort'=>'排序值',
			'description'=>'描述',
			'is_nav'=>'是否导航栏显示',
			'count'=>'记录数',
			'left_value'=>'Left Value',
			'right_value'=>'Right Value',
			'is_system'=>'Is System',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'alias'=>'trim',
			'parent'=>'intval',
			'file_id'=>'intval',
			'sort'=>'intval',
			'description'=>'trim',
			'is_nav'=>'intval',
			'count'=>'intval',
			'is_system'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
		);
	}
}