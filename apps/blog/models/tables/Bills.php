<?php
namespace blog\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class Bills extends Table{
    const TYPE_OUT = 1;//支出
    const TYPE_IN = 2;//收入

    protected $_name = 'blog_bills';
    
    /**
     * @return Bills
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
    }
    
    public function rules(){
        return array(
            array(array('id', 'user_id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('type'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('description', 'note'), 'string', array('max'=>255)),
            array(array('amount', 'balance'), 'float', array('length'=>8, 'decimal'=>2)),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'user_id'=>'User Id',
            'amount'=>'金额',
            'balance'=>'余额',
            'cat_id'=>'用途分类',
            'type'=>'进出帐',
            'description'=>'描述',
            'note'=>'备注',
            'create_time'=>'Create Time',
        );
    }

    public function filters(){
        return array(
            'user_id'=>'intval',
            'amount'=>'floatval',
            'balance'=>'floatval',
            'cat_id'=>'intval',
            'type'=>'intval',
            'description'=>'trim',
            'note'=>'trim',
            'create_time'=>'strtotime',
        );
    }
}