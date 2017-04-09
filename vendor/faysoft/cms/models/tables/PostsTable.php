<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * Posts table model
 *
 * @property int $id Id
 * @property int $cat_id 分类ID
 * @property string $title 标题
 * @property string $alias 别名
 * @property string $content 正文
 * @property int $content_type 正文类型（普通文本，符文本，markdown）
 * @property int $create_time 添加时间
 * @property int $update_time 更新时间
 * @property string $publish_date 发布日期
 * @property int $publish_time 发布时间
 * @property int $user_id 作者ID
 * @property int $is_top 是否置顶
 * @property int $status 文章状态
 * @property int $delete_time 删除时间
 * @property int $thumbnail 缩略图
 * @property string $abstract 摘要
 * @property int $sort 排序
 */
class PostsTable extends Table{
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
     * @param string $class_name
     * @return PostsTable
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
            array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
            array(array('is_top', 'delete_time'), 'range', array('range'=>array(0, 1))),
            array(array('publish_time'), 'datetime'),

            array(array('status'), 'range', array('range'=>array(self::STATUS_PUBLISHED, self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_REVIEWED))),
            array(array('content_type'), 'range', array('range'=>array(self::CONTENT_TYPE_MARKDOWN, self::CONTENT_TYPE_TEXTAREA, self::CONTENT_TYPE_VISUAL_EDITOR))),
            array('alias', 'unique', array('table'=>'posts', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('cms/admin/post/is-alias-not-exist'))),
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
            'update_time'=>'更新时间',
            'publish_date'=>'发布日期',
            'publish_time'=>'发布时间',
            'user_id'=>'作者ID',
            'is_top'=>'是否置顶',
            'status'=>'文章状态',
            'delete_time'=>'删除时间',
            'thumbnail'=>'缩略图',
            'abstract'=>'摘要',
            'sort'=>'排序',
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
            'publish_date'=>'',
            'publish_time'=>'trim',
            'user_id'=>'intval',
            'is_top'=>'intval',
            'status'=>'intval',
            'delete_time'=>'intval',
            'thumbnail'=>'intval',
            'abstract'=>'trim',
            'sort'=>'intval',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array('id');
                break;
            case 'update':
                return array(
                    'id', 'create_time', 'delete_time'
                );
                break;
            default:
                return array();
        }
    }
    
    /**
     * 获取已发布条件
     * @param string $alias 表别名
     * @return array
     */
    public static function getPublishedConditions($alias = ''){
        return array(
            ($alias ? "{$alias}." : '') . 'delete_time = 0',
            ($alias ? "{$alias}." : '') . 'publish_time < '.\F::app()->current_time,
            ($alias ? "{$alias}." : '') . 'status = '.PostsTable::STATUS_PUBLISHED,
        );
    }
}