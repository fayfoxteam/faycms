<?php
namespace fay\payments\weixin;

require_once __DIR__ . '/sdk/lib/WxPay.Api.php';
require_once __DIR__ . '/sdk/lib/WxPay.Notify.php';

class WeixinNotify extends \WxPayNotify{
    public function NotifyProcess($data, &$msg)
    {
        
        return true;
    }
}