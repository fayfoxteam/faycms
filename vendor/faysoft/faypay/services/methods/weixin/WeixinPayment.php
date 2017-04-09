<?php
namespace faypay\services\methods\weixin;

use fay\helpers\UrlHelper;
use faypay\models\PaymentMethodConfigModel;
use faypay\models\PaymentTradeModel;
use faypay\services\methods\PaymentMethodException;
use faypay\services\methods\PaymentMethodInterface;
use faypay\services\trade\TradePaymentService;
use fay\services\user\UserOauthService;

class WeixinPayment implements PaymentMethodInterface{
    /**
     * jsapi支付方式
     * @param PaymentTradeModel $trade
     * @param PaymentMethodConfigModel$config
     * @throws PaymentMethodException
     * @throws \WxPayException
     */
    public function jsapi(PaymentTradeModel $trade, PaymentMethodConfigModel $config){
        //判断字段是否有值
        $trade->checkRequiredField(array(
            'body', 'out_trade_no', 'total_fee', 'notify_url',
        ), '微信JSAPI支付');
        
        $config->checkRequiredField(array(
            'app_id', 'mch_id', 'key', 'app_secret',
        ), '微信JSAPI支付');
        
        require_once __DIR__ . '/sdk/example/WxPay.JsApiPay.php';
        
        //初始化一下微信sdk的变态配置方式
        \WxPayConfig::$KEY = $config->getKey();
        \WxPayConfig::$APPID = $config->getAppId();
        \WxPayConfig::$APPSECRET = $config->getAppSecret();
        \WxPayConfig::$MCHID = $config->getMchId();
        
        //①、获取用户openid
        $tools = new \JsApiPay($config->getAppId(), $config->getAppSecret());
        if(\F::app()->current_user && $openId = UserOauthService::service()->getOpenId($config->getAppId())){
            //尝试根据当前用户，在本地获取用户的open id。若无法获取，则通过页面跳转去微信获取。
        }else{
            $openId = $tools->GetOpenid(UrlHelper::createUrl('faypay/api/payment/pay-for-trade-payment', array(
                'id'=>$trade->getTradePaymentId()
            )));
        }

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetTrade_type('JSAPI');
        $input->SetOpenid($openId);
        
        //必填字段
        $input->SetBody($trade->getBody());
        $input->SetOut_trade_no($trade->getOutTradeNo());
        $input->SetTotal_fee($trade->getTotalFee());
        $input->SetNotify_url($trade->getNotifyUrl());
        
        //选填字段
        if($attach = $trade->getAttach()){
            $input->SetAttach($attach);
        }
        if($time_start = $trade->getTimeStart()){
            $input->SetTime_start(date('YmdHis', strtotime($time_start)));
        }
        if($time_expire = $trade->getTimeExpire()){
            $input->SetTime_expire(date('YmdHis', strtotime($time_expire)));
        }
        
        $order = \WxPayApi::unifiedOrder($input);
        //dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        
        require __DIR__ . '/views/jsapi.php';
    }
    
    /**
     * 第三方支付同步跳转
     * @return mixed
     */
    public function callback(){
        
    }
    
    /**
     * 第三方支付异步回调
     * @return mixed
     */
    public function notify(){
        //微信官方的sdk太恶心了。。有时间找找第三方的看
        require_once __DIR__ . '/sdk/lib/WxPay.Api.php';
        require_once __DIR__ . '/sdk/lib/WxPay.Notify.php';
        
        $notify_data = simplexml_load_string(file_get_contents('php://input', 'r'), 'SimpleXMLElement', LIBXML_NOCDATA);
        $trade_payment = TradePaymentService::service()->getByOutTradeNo($notify_data->out_trade_no);
        $payment_method = $trade_payment->getPaymentMethod();
        \WxPayConfig::$KEY = $payment_method['config']['key'];
        \WxPayConfig::$APPID = $payment_method['config']['app_id'];
        \WxPayConfig::$APPSECRET = $payment_method['config']['app_secret'];
        \WxPayConfig::$MCHID = $payment_method['config']['mch_id'];
        
        $notify = new WeixinNotify();
        $notify->Handle(false);
    }
    
    /**
     * 交易查询
     * @return mixed
     */
    public function query(){
        // TODO: Implement query() method.
    }
    
    /**
     * 退款
     * @return mixed
     */
    public function refund(){
        // TODO: Implement refund() method.
    }
}