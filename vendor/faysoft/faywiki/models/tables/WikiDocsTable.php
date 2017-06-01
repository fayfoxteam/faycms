<?php
namespace faywiki\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 百科文档
 *
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $cat_id 分类ID
 * @property string $title 标题
 * @property string $abstract 摘要
 * @property int $thumbnail 缩略图
 * @property int $status 状态
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $delete_time 删除时间
 * @property int $write_lock 编辑锁
 */
class WikiDocsTable extends Table{
    /**
     * 状态 - 已发布
     */
    const STATUS_PUBLISHED = 1;

    /**
     * 状态 - 待审核
     */
    const STATUS_PENDING = 2;

    /**
     * 状态 - 草稿
     */
    const STATUS_DRAFT = -1;
    
    protected $_name = 'wiki_docs';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('user_id', 'thumbnail'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('write_lock'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('title'), 'string', array('max'=>100)),
            
            array('status', 'range', array('range'=>array(
                self::STATUS_PUBLISHED, self::STATUS_PENDING, self::STATUS_DRAFT
            )))
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'cat_id'=>'分类ID',
            'title'=>'标题',
            'abstract'=>'摘要',
            'thumbnail'=>'缩略图',
            'status'=>'状态',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
            'delete_time'=>'删除时间',
            'write_lock'=>'编辑锁',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'cat_id'=>'intval',
            'title'=>'trim',
            'abstract'=>'',
            'thumbnail'=>'intval',
            'status'=>'intval',
            'write_lock'=>'intval',
        );
    }

    /**
     * 获取已发布条件
     * @param string $alias 表别名
     * @return array
     */
    public static function getPublishedConditions($alias = ''){
        return array(
            ($alias ? "{$alias}." : '') . 'delete_time = 0',
        );
    }
}