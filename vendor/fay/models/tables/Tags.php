<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Tags model
 * 
 * @property int $id Id
 * @property string $title 标签
 * @property int $sort 排序值
 * @property int $user_id 用户ID
 * @property int $create_time 创建时间
 * @property int $status 状态
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 */
class Tags extends Table{
	/**
	 * 状态-禁用
	 */
	const STATUS_DISABLED = 0;
	
	/**
	 * 状态-启用
	 */
	const STATUS_ENABLED = 1;
	
	protected $_name = 'tags';
	
	/**
	 * @return Tags
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>50)),
			array(array('seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
			
			array(array('title'), 'unique', array('table'=>'tags', 'except'=>'id', 'ajax'=>array('api/tag/is-tag-not-exist'))),
			array(array('title'), 'required'),
			array(array('status'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标签',
			'sort'=>'排序值',
			'user_id'=>'用户ID',
			'create_time'=>'创建时间',
			'status'=>'状态',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'title'=>'trim',
			'sort'=>'intval',
			'user_id'=>'intval',
			'create_time'=>'',
			'status'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
		);
	}
}