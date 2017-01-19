<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Trades table model
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property string $subject 支付说明
 * @property string $body 支付描述
 * @property float $total_fee 付款金额
 * @property float $paid_fee 已付金额
 * @property int $trade_payment_id 支付记录ID（付成功的那条）
 * @property float $refund_fee 退款金额
 * @property int $status 支付状态
 * @property int $create_time 创建时间
 * @property int $expire_time Expire Time
 * @property int $pay_time 付款时间
 * @property int $create_ip 创建IP
 */
class TradesTable extends Table{
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
	
	protected $_name = 'trades';
	
	/**
	 * @param string $class_name
	 * @return TradesTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('create_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'user_id', 'trade_payment_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('subject', 'body'), 'string', array('max'=>255)),
			array(array('total_fee', 'paid_fee', 'refund_fee'), 'float', array('length'=>8, 'decimal'=>2)),
			array(array('expire_time', 'pay_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'subject'=>'支付说明',
			'body'=>'支付描述',
			'total_fee'=>'付款金额',
			'paid_fee'=>'已付金额',
			'trade_payment_id'=>'支付记录ID（付成功的那条）',
			'refund_fee'=>'退款金额',
			'status'=>'支付状态',
			'create_time'=>'创建时间',
			'expire_time'=>'Expire Time',
			'pay_time'=>'付款时间',
			'create_ip'=>'创建IP',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'subject'=>'trim',
			'body'=>'trim',
			'total_fee'=>'floatval',
			'paid_fee'=>'floatval',
			'trade_payment_id'=>'intval',
			'refund_fee'=>'floatval',
			'status'=>'intval',
			'expire_time'=>'trim',
			'pay_time'=>'trim',
			'create_ip'=>'intval',
		);
	}
}