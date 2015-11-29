<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Tags extends Table{
	protected $_name = 'tags';
	
	/**
	 * @return Tags
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('count'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>50)),
			array(array('seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
			
			array(array('title'), 'unique', array('table'=>'tags', 'except'=>'id', 'ajax'=>array('admin/tag/is-tag-not-exist'))),
			array(array('title',), 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标签',
			'count'=>'Count',
			'sort'=>'Sort',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'count'=>'intval',
			'sort'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
		);
	}
}