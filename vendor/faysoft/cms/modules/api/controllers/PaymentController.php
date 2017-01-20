<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\HttpException;
use fay\services\trade\TradeService;

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
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'地区ID',
		))->check();
		
		$trade = new TradeService($this->form()->getData('trade_id'));
		if($trade->user_id != \F::app()->current_user){
			throw new HttpException('您不能给该笔交易付款', 403);
		}
		
		$trade->pay($this->form()->getData('payment_id'));
	}
}