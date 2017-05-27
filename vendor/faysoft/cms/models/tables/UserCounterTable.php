<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * User Counter model
 * 
 * @property int $user_id 用户ID
 * @property int $posts 文章数
 * @property int $follows 关注数
 * @property int $fans 粉丝数
 * @property int $messages 留言数
 * @property int $real_messages 真实留言数
 */
class UserCounterTable extends Table{
    protected $_name = 'user_counter';
    protected $_primary = 'user_id';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('follows', 'fans', 'messages', 'real_messages'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('posts'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }

    public function labels(){
        return array(
            'user_id'=>'用户ID',
            'posts'=>'文章数',
            'follows'=>'关注数',
            'fans'=>'粉丝数',
            'messages'=>'留言数',
            'real_messages'=>'真实留言数',
        );
    }

    public function filters(){
        return array(
            'user_id'=>'intval',
        );
    }
}