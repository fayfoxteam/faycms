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
 * @property int $total_fee 付款金额（单位：分）
 * @property int $paid_fee 已付金额（单位：分）
 * @property int $trade_payment_id 支付记录ID（付成功的那条）
 * @property int $refund_fee 退款金额（单位：分）
 * @property int $status 支付状态
 * @property int $create_time 创建时间
 * @property int $expire_time 过期时间
 * @property int $pay_time 付款时间
 * @property int $create_ip 创建IP
 * @property string $show_url 商品展示网址
 * @property string $return_url 页面跳转同步通知地址
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
			array(array('total_fee', 'paid_fee', 'refund_fee'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('subject', 'body', 'show_url', 'return_url'), 'string', array('max'=>255)),
			array(array('expire_time', 'pay_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'subject'=>'支付说明',
			'body'=>'支付描述',
			'total_fee'=>'付款金额（单位：分）',
			'paid_fee'=>'已付金额（单位：分）',
			'trade_payment_id'=>'支付记录ID（付成功的那条）',
			'refund_fee'=>'退款金额（单位：分）',
			'status'=>'支付状态',
			'create_time'=>'创建时间',
			'expire_time'=>'过期时间',
			'pay_time'=>'付款时间',
			'create_ip'=>'创建IP',
			'show_url'=>'商品展示网址',
			'return_url'=>'页面跳转同步通知地址',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'subject'=>'trim',
			'body'=>'trim',
			'total_fee'=>'intval',
			'paid_fee'=>'intval',
			'trade_payment_id'=>'intval',
			'refund_fee'=>'intval',
			'status'=>'intval',
			'expire_time'=>'trim',
			'pay_time'=>'trim',
			'create_ip'=>'intval',
			'show_url'=>'trim',
			'return_url'=>'trim',
		);
	}
}