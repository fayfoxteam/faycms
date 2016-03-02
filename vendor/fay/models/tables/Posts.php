<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Posts model
 *
 * @property int $id Id
 * @property int $cat_id 分类ID
 * @property string $title 标题
 * @property string $alias 别名
 * @property string $content 正文
 * @property int $content_type 正文类型（普通文本，符文本，markdown）
 * @property int $create_time 添加时间
 * @property int $last_modified_time 最后修改时间
 * @property string $publish_date 发布日期
 * @property int $publish_time 发布时间
 * @property int $user_id 作者ID
 * @property int $is_top 是否置顶
 * @property int $status 文章状态
 * @property int $deleted Deleted
 * @property int $thumbnail 缩略图
 * @property string $abstract 摘要
 * @property int $sort 排序
 * @property string $seo_title Seo Title
 * @property string $seo_keywords Seo Keywords
 * @property string $seo_description Seo Description
 * @property int $ip_int IP
 */
class Posts extends Table{
	/**
	 * 文章状态-草稿
	 */
	const STATUS_DRAFT = 0;
	
	/**
	 * 文章状态-已发布
	 */
	const STATUS_PUBLISHED = 1;
	
	/**
	 * 文章状态-待审核
	 */
	const STATUS_PENDING = 2;
	
	/**
	 * 文章状态-待复审
	 */
	const STATUS_REVIEWED = 3;
	
	/**
	 * 文本类型 - 可视化编辑器
	 */
	const CONTENT_TYPE_VISUAL_EDITOR = 1;
	
	/**
	 * 文本类型 - 文本域
	 */
	const CONTENT_TYPE_TEXTAREA = 2;
	
	/**
	 * 文本类型 - Markdown
	 */
	const CONTENT_TYPE_MARKDOWN = 3;
	
	protected $_name = 'posts';

	/**
	 * @return Posts
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title', 'abstract'), 'string', array('max'=>500)),
			array(array('alias'), 'string', array('max'=>50), 'format'=>'alias'),
			array(array('seo_title', 'seo_keywords'), 'string', array('max'=>100)),
			array(array('seo_description'), 'string', array('max'=>255)),
			array(array('is_top', 'deleted'), 'range', array('range'=>array(0, 1))),
			array(array('publish_time'), 'datetime'),

			array(array('status'), 'range', array('range'=>array(self::STATUS_PUBLISHED, self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_REVIEWED))),
			array(array('content_type'), 'range', array('range'=>array(self::CONTENT_TYPE_MARKDOWN, self::CONTENT_TYPE_TEXTAREA, self::CONTENT_TYPE_VISUAL_EDITOR))),
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
			'create_time'=>'添加时间',
			'last_modified_time'=>'最后修改时间',
			'publish_date'=>'发布日期',
			'publish_time'=>'发布时间',
			'user_id'=>'作者ID',
			'is_top'=>'是否置顶',
			'status'=>'文章状态',
			'deleted'=>'Deleted',
			'thumbnail'=>'缩略图',
			'abstract'=>'摘要',
			'sort'=>'排序',
			'seo_title'=>'Seo Title',
			'seo_keywords'=>'Seo Keywords',
			'seo_description'=>'Seo Description',
			'ip_int'=>'IP',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'cat_id'=>'intval',
			'title'=>'trim',
			'alias'=>'trim',
			'content'=>'',
			'content_type'=>'intval',
			'create_time'=>'',
			'last_modified_time'=>'',
			'publish_date'=>'',
			'publish_time'=>'trim',
			'user_id'=>'intval',
			'is_top'=>'intval',
			'status'=>'intval',
			'deleted'=>'intval',
			'thumbnail'=>'intval',
			'abstract'=>'trim',
			'sort'=>'intval',
			'seo_title'=>'trim',
			'seo_keywords'=>'trim',
			'seo_description'=>'trim',
			'ip_int'=>'intval',
		);
	}
}