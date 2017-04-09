<?php
namespace fayfeed\models\tables;

use fay\core\db\Table;

/**
 * Feed Favorites model
 * 
 * @property int $user_id 用户ID
 * @property int $feed_id 动态ID
 * @property int $create_time 收藏时间
 * @property int $ip_int IP
 * @property int $sockpuppet 马甲信息
 * @property string $trackid 追踪ID
 */
class FeedFavoritesTable extends Table{
    protected $_name = 'feed_favorites';
    protected $_primary = array('user_id', 'feed_id');
    
    /**
     * @param string $class_name
     * @return FeedFavoritesTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('user_id', 'feed_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('trackid'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'feed_id'=>'动态ID',
            'user_id'=>'用户ID',
            'create_time'=>'收藏时间',
            'ip_int'=>'IP',
            'sockpuppet'=>'马甲信息',
            'trackid'=>'追踪ID',
        );
    }

    public function filters(){
        return array(
            'feed_id'=>'intval',
            'user_id'=>'intval',
            'sockpuppet'=>'intval',
            'trackid'=>'trim',
        );
    }
}