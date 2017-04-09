<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feed Comments model
 * 
 * @property int $id Id
 * @property int $feed_id 动态ID
 * @property int $user_id 用户ID
 * @property string $content 内容
 * @property int $parent 父ID
 * @property int $status 状态
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $ip_int IP
 * @property int $sockpuppet 马甲信息
 * @property int $delete_time 删除时间
 * @property int $root 根评论ID
 * @property int $left_value 左值
 * @property int $right_value 右值
 */
class FeedCommentsTable extends Table{
    /**
     * 状态-待审核
     */
    const STATUS_PENDING = 1;
    /**
     * 状态-通过审核
     */
    const STATUS_APPROVED = 2;
    /**
     * 状态-未通过审核
     */
    const STATUS_UNAPPROVED = 3;
    
    protected $_name = 'feed_comments';
    
    /**
     * @param string $class_name
     * @return FeedCommentsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'feed_id', 'user_id', 'parent', 'sockpuppet', 'root'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('left_value', 'right_value'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('delete_time'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'feed_id'=>'动态ID',
            'user_id'=>'用户ID',
            'content'=>'内容',
            'parent'=>'父ID',
            'status'=>'状态',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',
            'ip_int'=>'IP',
            'sockpuppet'=>'马甲信息',
            'delete_time'=>'删除时间',
            'root'=>'根评论ID',
            'left_value'=>'左值',
            'right_value'=>'右值',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'feed_id'=>'intval',
            'user_id'=>'intval',
            'content'=>'',
            'parent'=>'intval',
            'status'=>'intval',
            'sockpuppet'=>'intval',
            'delete_time'=>'intval',
            'root'=>'intval',
        );
    }
}