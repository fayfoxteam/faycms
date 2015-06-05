<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Posts extends Table{
	protected $_name = 'posts';

	/**
	 * 文章状态-草稿
	 * @var int
	 */
	const STATUS_DRAFT = 0;
	/**
	 * 文章状态-已发布
	 * @var int
	 */
	const STATUS_PUBLISHED = 1;
	/**
	 * 文章状态-待审核
	 * @var int
	 */
	const STATUS_PENDING = 2;
	/**
	 * 文章状态-待复审
	 * @var int
	 */
	const STATUS_REVIEWED = 3;
	
	/**
	 * 文本类型 - 可视化编辑器
	 * @var int
	 */
	const CONTENT_TYPE_VISUAL_EDITOR = 1;
	/**
	 * 文本类型 - 文本域
	 * @var int
	 */
	const CONTENT_TYPE_TEXTAREA = 2;
	/**
	 * 文本类型 - Markdown
	 * @var int
	 */
	const CONTENT_TYPE_MARKDOWN = 3;

	/**
	 * @return Posts
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'create_time', 'last_modified_time', 'user_id', 'thumbnail', 'comments'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id', 'views'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('likes'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title', 'abstract'), 'string', array('max'=>500)),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array(array('seo_title', 'seo_keywords'), 'string', array('max'=>100)),
			array(array('seo_description'), 'string', array('max'=>255)),
			array(array('is_top', 'deleted'), 'range', array('range'=>array('0', '1'))),
			array(array('publish_time'), 'datetime'),

			array(array('status'), 'range', array('range'=>array(self::STATUS_PUBLISHED, self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_REVIEWED))),
			array('alias', 'unique', array('table'=>'posts', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/post/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'cat_id'=>'分类ID',
			'title'=>'标题',
			'alias'=>'别名',
			'content'=>'正文',
			'content_type'=>'正文类型（普通文本，符文本，markdown）',
			'create_time'=>'后台添加时间',
			'last_modified_time'=>'最后修改时间',
			'publish_date'=>'发布日期',
			'publish_time'=>'发布时间',
			'last_view_time'=>'最后访问时间',
			'user_id'=>'作者',
			'is_top'=>'是否置顶',
			'status'=>'文章状态',
			'deleted'=>'Deleted',
			'thumbnail'=>'缩略图',
			'abstract'=>'摘要',
			'comments'=>'评论数量',
			'sort'=>'排序',
			'views'=>'阅读数',
			'likes'=>'点赞数',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
		);
	}

	public function filters(){
		return array(
			'cat_id'=>'intval',
			'title'=>'trim',
			'alias'=>'trim',
			'content'=>'',
			'content_type'=>'intval',
			'create_time'=>'',
			'last_modified_time'=>'',
			'publish_date'=>'',
			'publish_time'=>'trim',
			'last_view_time'=>'',
			'user_id'=>'intval',
			'is_top'=>'intval',
			'status'=>'intval',
			'deleted'=>'intval',
			'thumbnail'=>'intval',
			'abstract'=>'trim',
			'comments'=>'intval',
			'sort'=>'intval',
			'views'=>'intval',
			'likes'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
		);
	}
}