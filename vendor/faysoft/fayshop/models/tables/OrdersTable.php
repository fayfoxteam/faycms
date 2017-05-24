<?php
namespace fayshop\models\tables;

use fay\core\db\Table;

/**
 * 订单表
 *
 * @property int $id 订单ID
 * @property int $buyer_id 买家ID
 * @property string $buyer_note 买家留言
 * @property int $seller_id 卖家ID
 * @property string $seller_note 卖家留言
 * @property int $status 订单状态
 * @property float $goods_fee 商品总价
 * @property float $shipping_fee 邮费
 * @property float $adjust_fee 卖家手工调整金额（差值）
 * @property float $total_fee 订单总价
 * @property float $paid_fee 实付金额
 * @property int $seller_rate 是否评价
 * @property string $title 标题
 * @property int $create_time 订单创建时间
 * @property int $pay_time 付款时间
 * @property int $consign_time 卖家发货时间
 * @property int $confirm_time 确认收货时间
 * @property string $close_reason 交易关闭原因
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
     * 订单状态-交易关闭
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
            array(array('status', 'seller_rate'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('buyer_note', 'seller_note', 'title', 'close_reason'), 'string', array('max'=>255)),
            array(array('goods_fee', 'adjust_fee', 'total_fee', 'paid_fee'), 'float', array('length'=>8, 'decimal'=>2)),
            array(array('shipping_fee'), 'float', array('length'=>6, 'decimal'=>2)),
            array(array('pay_time', 'consign_time', 'confirm_time'), 'datetime'),
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
            'create_time'=>'订单创建时间',
            'pay_time'=>'付款时间',
            'consign_time'=>'卖家发货时间',
            'confirm_time'=>'确认收货时间',
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
            'pay_time'=>'trim',
            'consign_time'=>'trim',
            'confirm_time'=>'trim',
            'close_reason'=>'trim',
        );
    }

    public function getNotWritableFields($scene){
        switch($scene){
            case 'update':
                return array(
                    'id', 'create_time', 'buyer_id', 'seller_id'
                );
            case 'insert':
            default:
                return array(
                    'id',
                );
        }
    }
}