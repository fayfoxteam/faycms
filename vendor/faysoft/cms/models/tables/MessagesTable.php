<?php
namespace cms\models\tables;

use fay\core\db\Table;

/**
 * Messages table model
 * 
 * @property int $id Id
 * @property int $to_user_id 留言给用户ID
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
class MessagesTable extends Table{
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
    
    protected $_name = 'messages';
    
    /**
     * @param string $class_name
     * @return MessagesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'to_user_id', 'user_id', 'parent', 'sockpuppet', 'root'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('left_value', 'right_value'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'to_user_id'=>'留言给用户ID',
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
            'to_user_id'=>'intval',
            'user_id'=>'intval',
            'content'=>'',
            'parent'=>'intval',
            'status'=>'intval',
            'sockpuppet'=>'intval',
            'root'=>'intval',
        );
    }
}