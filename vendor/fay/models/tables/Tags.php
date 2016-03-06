<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Tags model
 * 
 * @property int $id Id
 * @property string $title 标签
 * @property int $sort 排序值
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 * @property int $post_count 文章数
 * @property int $feed_count 动态数
 */
class Tags extends Table{
	protected $_name = 'tags';
	
	/**
	 * @return Tags
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('post_count', 'feed_count'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>50)),
			array(array('seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
			
			array(array('title'), 'unique', array('table'=>'tags', 'except'=>'id', 'ajax'=>array('api/tag/is-tag-not-exist'))),
			array(array('title'), 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标签',
			'sort'=>'排序值',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
			'post_count'=>'文章数',
			'feed_count'=>'动态数',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'title'=>'trim',
			'sort'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
			'post_count'=>'intval',
			'feed_count'=>'intval',
		);
	}
}