<?php
namespace hq\models\tables;

use fay\core\db\Table;

class Zbiaos extends Table{
    
    const TYPE_ELECTRICITY = 1;
    const TYPE_WATER = 2;
    
	protected $_name = 'zbiaos';

    /**
     * @return Zbiaos
     */
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function rules(){
        return array(
            array(array('id', 'biao_id', 'parent_id', 'zongzhi', 'times', 'created', 'updated'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
            array(array('type'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('biao_name', 't_number'), 'string', array('max'=>128)),
            array(array('address', 'shuoming'), 'string', array('max'=>512)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'biao_id'=>'Biao Id',
            'parent_id'=>'Parent Id',
            'type'=>'Type',
            'biao_name'=>'Biao Name',
            'zongzhi'=>'Zongzhi',
            'address'=>'Address',
            't_number'=>'T Nubmer',
            'shuoming'=>'Shuoming',
            'times'=>'Times',
            'data'=>'Data',
            'created'=>'Created',
            'updated'=>'Updated',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'biao_id'=>'intval',
            'parent_id'=>'intval',
            'type'=>'intval',
            'biao_name'=>'trim',
            'zongzhi'=>'intval',
            'address'=>'trim',
            't_number'=>'trim',
            'shuoming'=>'trim',
            'times'=>'intval',
            'data'=>'',
            'created'=>'intval',
            'updated'=>'intval',
        );
    }

    public static function getTypeName()
    {
        return [
            self::TYPE_ELECTRICITY => '电表',
            self::TYPE_WATER => '水表'
        ];
    }

}