<?php
namespace uuice\modules\doc\controllers;

use uuice\library\DocController;

class TestController extends DocController{
	public function index(){
		$version = '1.0';
		$merchant_id = '201401271000001093';
		$oid_billno = '2015050861452255';
		$col_custid = '201401271000001093';
		$col_amt_refund = '10';
		$col_cur_code = 'CNY';
		$url_notify = 'http://llpay.fayfox.com/';
		$merchant_id = '201401271000001093';
		$md5_key = 'yangguanghaitao';
		
		$sign = md5($version.$merchant_id.$oid_billno.$col_custid.$col_amt_refund.$col_cur_code.$md5_key);
		echo $sign;
	}
	
}