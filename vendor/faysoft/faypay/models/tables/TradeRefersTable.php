<?php
namespace faypay\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

/**
 * 交易引用关系表
 * 
 * @property int $id Id
 * @property int $trade_id 交易ID
 * @property int $type 交易类型
 * @property int $refer_id 关联ID
 */
class TradeRefersTable extends Table{
    protected $_name = 'trade_refers';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'trade_id', 'refer_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('type'), 'int', array('min'=>0, 'max'=>255)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'trade_id'=>'交易ID',
            'type'=>'交易类型',
            'refer_id'=>'关联ID',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'trade_id'=>'intval',
            'type'=>'intval',
            'refer_id'=>'intval',
        );
    }
}