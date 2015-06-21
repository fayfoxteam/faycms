<?php
namespace hq\models\tables;

use fay\core\db\Table;

class ZbiaoRecords extends Table{

    const TIME_DAY = 1;
    const TIME_WEEK = 2;
    const TIME_MONTH = 3;

	protected $_name = 'zbiao_records';

    /**
     * @return ZbiaoRecords
     */
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function rules(){
        return array(
            array(array('id', 'biao_id', 'parent_id', 'zongliang', 'day_use', 'created'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'biao_id'=>'Biao Id',
            'parent_id'=>'Parent Id',
            'zongliang'=>'Zongliang',
            'day_use'=>'Day Use',
            'created'=>'Created',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'biao_id'=>'intval',
            'parent_id'=>'intval',
            'zongliang'=>'intval',
            'day_use'=>'intval',
            'created'=>'intval',
        );
    }
}