<?php
namespace guangong\modules\api\controllers;

use fay\core\Response;
use fay\helpers\UrlHelper;
use faypay\services\trade\TradeService;
use guangong\models\PaymentModel;

class PaymentController extends \faypay\modules\api\controllers\PaymentController{
	/**
	 * 缴纳军费
	 */
	public function military(){
		$this->checkLogin();
		
		//创建交易
		$trade_id = TradeService::service()->create(
			1100,//测试完成后改成1100
			'网络体验/军籍存档/义结金兰技术服务费',
			array(
				array(
					'type'=>PaymentModel::TYPE_MILITARY,
					'refer_id'=>\F::app()->current_user,
				)
			),
			array(
				'subject'=>'关羽军团军费',
				'return_url'=>UrlHelper::createUrl('payment/success'),
			)
		);
		
		//跳转去支付
		Response::redirect('api/payment/pay', array(
			'trade_id'=>$trade_id,
			'payment_id'=>1,//这个系统，写死就好了
		), false);
	}
}