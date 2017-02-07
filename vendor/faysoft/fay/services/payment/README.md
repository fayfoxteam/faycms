# 第三方支付模块

## 概念陈述
- 支付方式（PaymentMethod）：微信支付、支付宝等。
- 交易（Trade）：一笔支付对应一笔交易。
- 交易支付记录（TradePayment）：每次支付都会产生一笔支付记录。

## 支付方式（PaymentMethod）
- `PaymentMethodConfigModel`：支付方式配置信息（在构建支付的时候传入此类实例）
 * `code`：支付方式编码。例如：`weixin:jsapi`（微信支付:jsapi支付）【必选】
 * `sign_type`：签名方式。有些字符方式有这个选项【可选】
 * `app_id`：对应微信支付：公众号ID（`app_id`）；支付宝：合作者身份ID（`partner`）；银联没有这个值
 * `mch_id`：对应微信支付：商户号（`mch_id`）；支付宝：卖家支付宝用户好（`seller_id`）；银联：商户号（`merId`）
 * `key`：商户支付密钥。对应微信支付：商户支付密钥（key）
 * `app_secret`：公众帐号`app_secret`（目前只有微信jsapi支付有这个参数）
- `PaymentTradeModel`：交易信息（在构建支付的时候传入此类实例）
 * `notify_url`：服务器异步通知页面路径。微信和支付宝对应：`notify_url`；银联对应：`backUrl`
 * `return_url`：页面跳转同步通知页面路径（网页支付会有这个地址，app支付一般没有这个地址）。支付宝对应：`return_url`；银联对应：`frontUrl`
 * `out_trade_no`：商户订单号。所有支付方式都有这个字段（但不同的支付方式对格式有一定的要求）。第三方支付方式视为唯一标识，同一个商户订单号不能重复支付。
 * `total_fee`：支付金额（以“分”为单位的整数，如果支付方式需要传入以“元”为单位的值，需要转化）。微信对应：`total_fee`，单位：分；支付宝对应：`total_fee`，单位：元；银联对应：`txnAmt`：单位：分
 * `body`：交易描述。微信支付：简要描述（`body`）；对应支付宝：商品描述（`body`）；银联：订单描述（`orderDesc`）
 * `subject`：交易标题。对应支付宝：订单标题（`subject`）
 * `show_url`：对应支付宝：商品展示网址（`show_url`）
 * `it_b_pay`：对应支付宝：超时时间（`it_b_pay`）
 * `attach`：透传字段。对应微信支付：附加数据（`attach`）；银联：请求方保留域（`reqReserved`）
 * `time_start`：订单生成时间（strtotime能识别的时间格式都行），一般默认为当前时间即可，不需要填写
 * `time_expire`：订单失效时间（strtotime能识别的时间格式都行）
 * `trade_payment_id`：交易支付记录ID（并不属于支付需要用到的字段，但是做微信支付OAuth认证的时候需要做跳转，要用到这个字段）


示例代码：
```php
//从数据库获取支付方式配置信息
$payment_method = $trade_payment->getPaymentMethod();
//实例化用于支付的支付方式配置模型
$payment_config = new PaymentMethodConfigModel($payment_method['code']);
//设置相关属性
$payment_config->setMchId($payment_method['config']['mch_id'])
    ->setAppId($payment_method['config']['app_id'])
    ->setAppSecret($payment_method['config']['app_secret'])
    ->setKey($payment_method['config']['key'])
;

$trade = $trade_payment->getTrade();
//实例化用于支付的交易数据模型
$payment_trade = new PaymentTradeModel();
//设置相关属性
$payment_trade->setOutTradeNo($trade_payment->getOutTradeNo())
    ->setTotalFee($trade_payment->total_fee)
    ->setNotifyUrl(UrlHelper::createUrl('api/payment/notify/code/'.$payment_method['code']))
    ->setBody($trade->body)
    ->setTradePaymentId($trade_payment->id)
    ->setReturnUrl($trade->return_url)
    ->setShowUrl($trade->show_url)
;

//调用支付模块。根据支付方式不同，此方法可能返回json数据，也可能直接跳转到支付页面
PaymentMethodService::service()->buildPay($payment_trade, $payment_config);
```