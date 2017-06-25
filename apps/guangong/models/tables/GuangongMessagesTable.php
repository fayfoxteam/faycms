<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Guangong messages table model
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property string $title 标题
 * @property string $content 内容
 * @property int $type 类型
 * @property int $create_time 留言时间
 * @property string $reply 管理员回复
 * @property int $reply_time 回复时间
 * @property int $ip_int IP
 * @property int $delete_time 删除标记
 */
class GuangongMessagesTable extends Table{
    /**
     * 类型 - 兵谏 - 攻城
     */
    const TYPE_BINGJIAN_GONGCHENG = 11;
    /**
     * 类型 - 兵谏 - 守城
     */
    const TYPE_BINGJIAN_SHOUCHENG = 12;
    /**
     * 类型 - 兵谏 - 兵器
     */
    const TYPE_BINGJIAN_BINGQI = 13;
    /**
     * 类型 - 兵谏 - 服饰
     */
    const TYPE_BINGJIAN_FUSHI = 14;
    
    /**
     * 类型 - 公民学者
     */
    const TYPE_GONGMINXUEZHE = 2;
    
    /**
     * 类型 - 正义联盟
     */
    const TYPE_ZHENGYILIANMENG = 3;
    
    protected $_name = 'guangong_messages';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('delete_time'), 'range', array('range'=>array(0, 1))),
            array(array('reply_time'), 'datetime'),
            array(array('title'), 'string', array('max'=>255)),
            
            array(array('type', 'content', 'title'), 'required'),
            array(array('type'), 'int', array('min'=>1, 'max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'title'=>'标题',
            'content'=>'内容',
            'type'=>'类型',
            'create_time'=>'留言时间',
            'reply'=>'管理员回复',
            'reply_time'=>'回复时间',
            'ip_int'=>'IP',
            'delete_time'=>'删除标记',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'title'=>'trim',
            'content'=>'',
            'type'=>'intval',
            'reply'=>'',
            'reply_time'=>'trim',
            'delete_time'=>'intval',
        );
    }
}