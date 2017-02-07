<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\HttpException;
use fay\models\tables\PaymentsTable;
use fay\services\payment\methods\PaymentMethodService;
use fay\services\payment\trade\TradeItem;
use fay\services\payment\trade\TradePaymentService;

class PaymentController extends ApiController{
	/**
	 * 付款接口
	 * @parameter int $trade_id
	 * @parameter int $payment_id
	 */
	public function pay(){
		//表单验证
		$this->form()->setRules(array(
			array(array('trade_id', 'payment_id'), 'required'),
			array(array('trade_id', 'payment_id'), 'int', array('min'=>1)),
			array(array('trade_id'), 'exist', array(
				'table'=>'trades',
				'field'=>'id',
			)),
			array(array('payment_id'), 'exist', array(
				'table'=>'trades',
				'field'=>'id',
			)),
		))->setFilters(array(
			'trade_id'=>'intval',
			'payment_id'=>'intval',
		))->setLabels(array(
			'trade_id'=>'交易ID',
			'payment_id'=>'支付方式ID',
		))->check();
		
		$trade = new TradeItem($this->form()->getData('trade_id'));
		if($trade->user_id != \F::app()->current_user){
			throw new HttpException('您不能给该笔交易付款', 403);
		}
		
		$trade->pay($this->form()->getData('payment_id'));
	}
	
	/**
	 * 针对交易支付记录付款。
	 * 像微信公众号支付这种需要先跳走再跳回来的方式，
	 * 如果重复调用api/payment/pay会重复生成trade_payments。
	 * @parameter int $id
	 */
	public function payForTradePayment(){
		//表单验证
		$this->form()->setRules(array(
			array(array('id'), 'required'),
			array(array('id'), 'int', array('min'=>1)),
			array(array('id'), 'exist', array(
				'table'=>'trade_payments',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'支付记录ID',
		))->check();
		
		//调用支付
		TradePaymentService::service()->get($this->form()->getData('id'))->pay();
	}
	
	public function notify(){
		//表单验证
		$this->form()->setRules(array(
			array(array('code'), 'required'),
			array(array('code'), 'range', array('range'=>array_keys(PaymentsTable::$codes))),
		))->setFilters(array(
			'code'=>'trim',
		))->setLabels(array(
			'code'=>'支付编码',
		))->check();
		
		PaymentMethodService::service()->notify($this->form()->getData('code'));
	}
}