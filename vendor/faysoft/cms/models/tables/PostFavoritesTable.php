<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Post Favorites model
 * 
 * @property int $user_id 用户ID
 * @property int $post_id 文章ID
 * @property int $create_time 收藏时间
 * @property int $ip_int IP
 * @property int $sockpuppet 马甲信息
 * @property string $trackid 追踪ID
 */
class PostFavoritesTable extends Table{
    protected $_name = 'post_favorites';
    protected $_primary = array('user_id', 'post_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('user_id', 'post_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('trackid'), 'string', array('max'=>50)),
        );
    }

    public function labels(){
        return array(
            'user_id'=>'用户ID',
            'post_id'=>'文章ID',
            'create_time'=>'收藏时间',
            'ip_int'=>'IP',
            'sockpuppet'=>'马甲信息',
            'trackid'=>'追踪ID',
        );
    }

    public function filters(){
        return array(
            'user_id'=>'intval',
            'post_id'=>'intval',
            'sockpuppet'=>'intval',
            'trackid'=>'trim',
        );
    }
}