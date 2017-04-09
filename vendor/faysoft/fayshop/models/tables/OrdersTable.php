<?php
namespace fayshop\models\tables;

use fay\core\db\Table;

/**
 * Orders table model
 * 
 * @property int $id
 * @property int $buyer_id
 * @property string $buyer_note
 * @property int $seller_id
 * @property string $seller_note
 * @property int $status
 * @property float $goods_fee
 * @property float $shipping_fee
 * @property float $adjust_fee
 * @property float $total_fee
 * @property float $paid_fee
 * @property int $seller_rate
 * @property string $title
 * @property int $receiver_state
 * @property int $receiver_city
 * @property int $receiver_district
 * @property string $receiver_address
 * @property string $receiver_name
 * @property string $receiver_mobile
 * @property string $receiver_phone
 * @property int $create_time
 * @property int $pay_time
 * @property int $consign_time
 * @property int $comfirm_time
 * @property string $close_reason
 */
class OrdersTable extends Table{
    /**
     * 订单状态-等待买家付款
     */
    const STATUS_WAIT_BUYER_PAY = 1;
    /**
     * 订单状态-等待卖家发货,即:买家已付款
     */
    const STATUS_WAIT_SELLER_SEND_GOODS = 2;
    /**
     * 订单状态-等待买家确认收货,即:卖家已发货
     */
    const STATUS_WAIT_BUYER_CONFIRM_GOODS = 3;
    /**
     * 订单状态-交易完成
     */
    const STATUS_FINISHED = 4;
    /**
     * 订单状态-交易关闭x
     */
    const STATUS_CLOSED = -1;
    
    protected $_name = 'orders';
    
    /**
     * @param string $class_name
     * @return OrdersTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('buyer_id', 'seller_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('receiver_state', 'receiver_city', 'receiver_district'), 'int', array('min'=>0, 'max'=>65535)),
            array(array('status', 'seller_rate'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('buyer_note', 'seller_note', 'title', 'receiver_address', 'close_reason'), 'string', array('max'=>255)),
            array(array('receiver_name'), 'string', array('max'=>50)),
            array(array('receiver_mobile', 'receiver_phone'), 'string', array('max'=>30)),
            array(array('goods_fee', 'adjust_fee', 'total_fee', 'paid_fee'), 'float', array('length'=>8, 'decimal'=>2)),
            array(array('shipping_fee'), 'float', array('length'=>6, 'decimal'=>2)),
            array(array('pay_time', 'consign_time', 'comfirm_time'), 'datetime'),
        );
    }

    public function labels(){
        return array(
            'id'=>'订单ID',
            'buyer_id'=>'买家ID',
            'buyer_note'=>'买家留言',
            'seller_id'=>'卖家ID',
            'seller_note'=>'卖家留言',
            'status'=>'订单状态',
            'goods_fee'=>'商品总价',
            'shipping_fee'=>'邮费',
            'adjust_fee'=>'卖家手工调整金额（差值）',
            'total_fee'=>'订单总价',
            'paid_fee'=>'实付金额',
            'seller_rate'=>'是否评价',
            'title'=>'标题',
            'receiver_state'=>'收货人所在省',
            'receiver_city'=>'收货人所在市',
            'receiver_district'=>'收货人所在区',
            'receiver_address'=>'收货人详细地址',
            'receiver_name'=>'收货人姓名',
            'receiver_mobile'=>'收货人的手机号码',
            'receiver_phone'=>'收货人的电话号码',
            'create_time'=>'订单创建时间',
            'pay_time'=>'付款时间',
            'consign_time'=>'卖家发货时间',
            'comfirm_time'=>'确认收货时间',
            'close_reason'=>'交易关闭原因',
        );
    }

    public function filters(){
        return array(
            'id'=>'intval',
            'buyer_id'=>'intval',
            'buyer_note'=>'trim',
            'seller_id'=>'intval',
            'seller_note'=>'trim',
            'status'=>'intval',
            'goods_fee'=>'floatval',
            'shipping_fee'=>'floatval',
            'adjust_fee'=>'floatval',
            'total_fee'=>'floatval',
            'paid_fee'=>'floatval',
            'seller_rate'=>'intval',
            'title'=>'trim',
            'receiver_state'=>'intval',
            'receiver_city'=>'intval',
            'receiver_district'=>'intval',
            'receiver_address'=>'trim',
            'receiver_name'=>'trim',
            'receiver_mobile'=>'trim',
            'receiver_phone'=>'trim',
            'pay_time'=>'trim',
            'consign_time'=>'trim',
            'comfirm_time'=>'trim',
            'close_reason'=>'trim',
        );
    }
}