<?php
namespace fayshop\models\tables;

use fay\core\db\Table;

/**
 * 订单收件人信息
 * 
 * @property int $order_id Order Id
 * @property int $state 省
 * @property int $city 市
 * @property int $district 县
 * @property string $address 详细地址
 * @property string $name 姓名
 * @property string $mobile 手机号码
 * @property int $address_id 下单时选择的地址id
 */
class OrderAddressTable extends Table{
    protected $_name = 'order_address';
    protected $_primary = 'order_id';
    
    /**
     * @param string $class_name
     * @return OrderAddressTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('order_id', 'address_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('state', 'city', 'district'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('address'), 'string', array('max'=>255)),
            array(array('name'), 'string', array('max'=>50)),
            array(array('mobile'), 'mobile'),
        );
    }

    public function labels(){
        return array(
            'order_id'=>'Order Id',
            'state'=>'省',
            'city'=>'市',
            'district'=>'县',
            'address'=>'详细地址',
            'name'=>'姓名',
            'mobile'=>'手机号码',
            'address_id'=>'下单时选择的地址id',
        );
    }

    public function filters(){
        return array(
            'order_id'=>'intval',
            'state'=>'intval',
            'city'=>'intval',
            'district'=>'intval',
            'address'=>'trim',
            'name'=>'trim',
            'mobile'=>'trim',
            'address_id'=>'intval',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'update':
                return array(
                    'order_id',
                );
            case 'insert':
            default:
                return array(
                    'order_id',
                );
        }
    }
}