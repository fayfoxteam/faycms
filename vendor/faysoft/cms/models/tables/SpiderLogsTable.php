<?php
namespace cms\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

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
            array(array('id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
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
            'ip_int'=>'IP',
            'url'=>'Url',
            'create_time'=>'创建时间',
        );
    }

    public function filters(){
        return array(
            'spider'=>'trim',
            'user_agent'=>'trim',
            'url'=>'trim',
        );
    }
}