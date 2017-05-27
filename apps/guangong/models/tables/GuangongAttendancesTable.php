<?php
namespace guangong\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 出勤记录表
 *
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property string $create_date 出勤日期
 * @property int $create_time 出勤时间
 * @property int $continuous 连续出勤天数
 */
class GuangongAttendancesTable extends Table{
    protected $_name = 'guangong_attendances';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('user_id'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('continuous'), 'int', array('min'=>0, 'max'=>65535)),
        );
    }
    
    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'用户ID',
            'create_date'=>'出勤日期',
            'create_time'=>'出勤时间',
            'continuous'=>'连续出勤天数',
        );
    }
    
    public function filters(){
        return array(
            'id'=>'intval',
            'user_id'=>'intval',
            'create_date'=>'',
            'continuous'=>'intval',
        );
    }
}