<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Tags table model
 * 
 * @property int $id Id
 * @property string $title 标签
 * @property int $sort 排序值
 * @property int $user_id 用户ID
 * @property int $create_time 创建时间
 * @property int $status 状态
 * @property string $seo_title SEO Title
 * @property string $seo_keywords SEO Keywords
 * @property string $seo_description SEO Description
 */
class TagsTable extends Table{
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
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
            array(array('title'), 'string', array('max'=>50)),
            array(array('seo_title', 'seo_keywords', 'seo_description'), 'string', array('max'=>255)),
            
            array(array('title'), 'unique', array('table'=>'tags', 'except'=>'id', 'ajax'=>array('cms/api/tag/is-tag-not-exist'))),
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
            'seo_title'=>'SEO Title',
            'seo_keywords'=>'SEO Keywords',
            'seo_description'=>'SEO Description',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'title'=>'trim',
            'sort'=>'intval',
            'user_id'=>'intval',
            'status'=>'intval',
            'seo_title'=>'trim',
            'seo_keywords'=>'trim',
            'seo_description'=>'trim',
        );
    }
    
    public function getNotWritableFields($scene){
        switch($scene){
            case 'insert':
                return array(
                    'id'
                );
            case 'update':
            default:
                return array(
                    'id', 'user_id', 'create_time',
                );
        }
    }
}