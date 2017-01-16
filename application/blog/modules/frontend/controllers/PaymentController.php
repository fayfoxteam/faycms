<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\payments\weixin\WeixinPayment;

class PaymentController extends FrontController{
	public function wxjsapi(){
		(new WeixinPayment)->jsApi();
	}
}