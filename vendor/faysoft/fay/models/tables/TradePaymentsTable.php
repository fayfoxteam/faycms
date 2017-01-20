<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Trade payments table model
 *
 * @property int $id Id
 * @property int $trade_id 交易ID
 * @property int $total_fee 支付金额（单位：分）
 * @property int $create_time 创建时间
 * @property int $create_ip 创建IP
 * @property int $paid_time 支付时间
 * @property int $status 支付状态
 * @property int $payment_id 支付方式ID
 * @property string $trade_no 第三方交易号
 * @property string $payer_account 付款人帐号
 * @property int $paid_fee 实付金额（单位：分）
 * @property int $refund_fee 退款金额（单位：分）
 */
class TradePaymentsTable extends Table{
	/**
	 * 状态 - 待付款
	 */
	const STATUS_WAIT_PAY = 1;
	
	/**
	 * 状态 - 已付款
	 */
	const STATUS_PAID = 2;
	
	/**
	 * 状态 - 交易关闭
	 */
	const STATUS_CLOSED = 3;
	
	/**
	 * 状态 - 交易关闭后发生支付（这是一种异常状态）
	 */
	const STATUS_PAID_AFTER_CLOSED = 100;
	
	protected $_name = 'trade_payments';
	
	/**
	 * @param string $class_name
	 * @return TradePaymentsTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('create_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'trade_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('total_fee', 'paid_fee', 'refund_fee'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('payment_id'), 'int', array('min'=>0, 'max'=>255)),
			array(array('trade_no'), 'string', array('max'=>255)),
			array(array('payer_account'), 'string', array('max'=>50)),
			array(array('paid_time'), 'datetime'),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'trade_id'=>'交易ID',
			'total_fee'=>'支付金额（单位：分）',
			'create_time'=>'创建时间',
			'create_ip'=>'创建IP',
			'paid_time'=>'支付时间',
			'status'=>'支付状态',
			'payment_id'=>'支付方式ID',
			'trade_no'=>'第三方交易号',
			'payer_account'=>'付款人帐号',
			'paid_fee'=>'实付金额（单位：分）',
			'refund_fee'=>'退款金额（单位：分）',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'trade_id'=>'intval',
			'total_fee'=>'intval',
			'create_ip'=>'intval',
			'paid_time'=>'trim',
			'status'=>'intval',
			'payment_id'=>'intval',
			'trade_no'=>'trim',
			'payer_account'=>'trim',
			'paid_fee'=>'intval',
			'refund_fee'=>'intval',
		);
	}
}