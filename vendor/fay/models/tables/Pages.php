<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Pages extends Table{
	const STATUS_DRAFT = 0;
	const STATUS_PUBLISHED = 1;

	protected $_name = 'pages';
	
	/**
	 * @return Pages
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('comments'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'create_time', 'last_modified_time', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('author', 'views'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title', 'abstract'), 'string', array('max'=>500)),
			array(array('seo_description'), 'string', array('max'=>255)),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array(array('seo_title', 'seo_keywords'), 'string', array('max'=>100)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
			
			array(array('status'), 'range', array('range'=>array('0', '1'))),
			array('alias', 'unique', array('table'=>'pages', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/page/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'alias'=>'别名',
			'content'=>'正文',
			'author'=>'作者',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'status'=>'状态',
			'deleted'=>'删除标记',
			'thumbnail'=>'缩略图',
			'comments'=>'评论数',
			'views'=>'阅读数',
			'sort'=>'排序值',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
			'abstract'=>'摘要',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'alias'=>'trim',
			'content'=>'',
			'author'=>'intval',
			'create_time'=>'',
			'last_modified_time'=>'',
			'status'=>'intval',
			'deleted'=>'intval',
			'thumbnail'=>'intval',
			'comments'=>'intval',
			'views'=>'intval',
			'sort'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
			'abstract'=>'trim',
		);
	}
}