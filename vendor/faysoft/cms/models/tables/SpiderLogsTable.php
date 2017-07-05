<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * Spider logs table model
 *
 * @property int $id Id
 * @property string $spider Spider
 * @property string $user_agent User Agent
 * @property int $ip_int Ip Int
 * @property string $url Url
 * @property int $create_time 创建时间
 */
class SpiderLogsTable extends Table{
    protected $_name = 'spider_logs';

    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }

    public function rules(){
        return array(
            array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('spider'), 'string', array('max'=>50)),
            array(array('user_agent', 'url'), 'string', array('max'=>255)),

            array(array('url'), 'url'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'spider'=>'Spider',
            'user_agent'=>'User Agent',
            'ip_int'=>'Ip',
            'url'=>'Url',
            'create_time'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'spider'=>'trim',
            'user_agent'=>'trim',
            'url'=>'trim',
        );
    }
}