<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 文献学习记录
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $post_id 文献ID
 * @property int $create_time 阅读时间
 * @property string $create_date 阅读日期
 */
class GuangongReadLogsTable extends Table{
    protected $_name = 'guangong_read_logs';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'user_id', 'post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'post_id'=>'文献ID',
            'create_time'=>'阅读时间',
            'create_date'=>'阅读日期',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'post_id'=>'intval',
            'create_date'=>'',
        );
    }
}