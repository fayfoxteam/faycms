<?php
namespace faypay\services\trade;

use fay\core\Exception;

/**
 * 不太重要的错误，比如重复支付通知，必然会出现，且不需要处理。
 */
class TradeException extends Exception{
    
}