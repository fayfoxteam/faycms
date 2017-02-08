# 第三方支付模块

## 调用代码
先放调用代码，因为调用代码很简单。如果不扩展支付方式的话，后面那一大段都不需要看。
### 创建交易
```php
//创建交易
$trade_id = TradeService::service()->create(
    1,//金额，单位：分
    '测试订单',
    array(
        array(
            'type'=>1,//类型，在业务中定义
            'refer_id'=>123,//关联id，可以是用户Id，订单Id等，具体看业务逻辑
        )
    ),
    array(//这里是可选参数，可以根据业务逻辑指定需要用到的字段
        'return_url'=>UrlHelper::createUrl('test/pay-result'),//支付回调页
    )
);
```

### 发起支付
直接通过api调用发起支付。该接口根据支付方式不同，可能返回json数据，也可能直接跳转到第三方支付页面。

{$base_url}api/payment/pay?trade_id={$交易ID}&payment_id={$支付方式ID}


## 概念陈述
- 支付方式（PaymentMethod）：微信支付、支付宝等。
- 交易（Trade）：一笔支付对应一笔交易。
- 交易支付记录（TradePayment）：每次支付都会产生一笔支付记录。

## 支付方式（PaymentMethod）
支付方式是一个独立的模块。支付方式是独立于交易（Trade）的，可以通过其他方式构造支付参数，发起支付。本系统出于业务逻辑考虑，都是由交易（Trade）模块调起支付。

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

//获取交易信息
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


## 交易（Trade）
### 订单实例（TradeItem）
实现`ArrayAccess`接口和`__get()`/`__set()`方法。支持以数组或对象属性的方式获取或修改trades表对应字段信息。

- `save()`：字段被修改后调用此方法可以保存到数据库
- `getRefers()`：获取交易关联信息，对应trade_refers表字段
- `getTrade()`：获取交易信息。对应trades表字段
- `pay()`：发起支付

### 交易服务（TradeService）
与数据库打交道的服务类。用于创建、获取交易信息。

- `get()`：根据交易ID，获取`TradeItem`实例
- `create()`：创建一笔交易。参数含义如下：
 * `$total_fee`：交易金额（单位：分）
 * `$body`：交易描述
 * `$refers`：关联信息。二维数组，每项必须包含`type`和`refer_id`字段
 * `$extra`：键值数组，可选择包含字段：`subject`, `expire_time`, `return_url`, `show_url`
 * `$user_id`：用户ID，若为null，则默认为当前登录用户

### 交易状态模式
交易状态变更采用状态模式设计。每个状态对应state文件夹下的状态类。状态类均继承自`StateInterface`接口。`StateInterface`接口含以下方法：

- `pay()`：执行支付
- `refund()`：执行退款
- `close()`：交易关闭

## 交易支付记录（TradePayment）
### 支付记录实例（TradePaymentItem）
实现`ArrayAccess`接口和`__get()`/`__set()`方法。支持以数组或对象属性的方式获取或修改trades表对应字段信息。

- `save()`：字段被修改后调用此方法可以保存到数据库
- `getTrade()`：获取交易信息，返回TradeItem实例
- `setTrade()`：在实例化TradePayment时，并不会马上根据交易记录里的trade_id去初始化trade属性。为了节省开销，可以将已经实例化的TradeItem实例设置进去。若不设置，在调用`getTrade()`时会根据trade_id自动初始化。
- `getPaymentMethod(TradeItem $trade)`：获取支付方式信息，返回`fay\services\payment\methods\PaymentMethodService::get()`的结果
- `setPaymentMethod(array $payment_method)`：在实例化TradePayment时，并不会马上根据交易记录里的payment_method_id去初始化payment_method属性。为了节省开销，可以将已获取的支付方式信息设置进去。若不设置，在调用`getPaymentMethod()`时会根据payment_method_id自动初始化。
- `getOutTradeNo()`：生成一个唯一的外部订单号
- `onPaid()`：支付完成处理
- `pay()`：发起支付

### 支付记录服务（TradePaymentService）
与数据库打交道的服务类。用于创建、获取交易支付记录。

- `create()`：创建一条交易支付记录
 * `$trade_id`：交易ID
 * `$total_fee`：支付金额（单位：分）
 * `$payment_method_id`：支付方式ID
- `get()`：根据支付记录ID，获取一个支付记录实例（`TradePaymentItem`）
- `getByOutTradeNo()`：根据外部订单号，获取支付记录实例（`TradePaymentItem`）

### 支付记录状态模式
支付记录状态变更采用状态模式设计。每个状态对应payment_state文件夹下的状态类。状态类均继承自`PaymentStateInterface`接口。`PaymentStateInterface`接口含以下方法：

- `pay()`：执行支付
- `onPaid()`：接受支付回调
- `refund()`：交易支付记录执行退款（有可能是重复支付产生的退款，并不一定就是对交易进行退款）
- `close()`：交易支付记录关闭

> 交易（Trade）与交易支付记录（TradePayment）是一对多的关系。每发生一次支付行为，就会产生一条支付记录。当有一条支付记录变为已付款后，其他同交易的支付记录都会变为已关闭。

## 附录一：交易相关表结构
### 支付方式表
```sql
DROP TABLE IF EXISTS `{{$prefix}}payments`;
CREATE TABLE `{{$prefix}}payments` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '支付编码',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '支付名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '支付描述',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `config` text COMMENT '配置信息JSON',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后编辑时间',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='付款方式';
```

### 交易记录表
```sql
DROP TABLE IF EXISTS `{{$prefix}}trades`;
CREATE TABLE `{{$prefix}}trades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '支付说明',
  `body` varchar(255) NOT NULL DEFAULT '' COMMENT '支付描述',
  `total_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '付款金额（单位：分）',
  `paid_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '已付金额（单位：分）',
  `trade_payment_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付记录ID（付成功的那条）',
  `refund_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '退款金额（单位：分）',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `create_ip` int(11) NOT NULL DEFAULT '0' COMMENT '创建IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易记录';
```

### 交易引用关系表
```sql
DROP TABLE IF EXISTS `{{$prefix}}trade_refers`;
CREATE TABLE `{{$prefix}}trade_refers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `trade_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '交易类型',
  `refer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联ID',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易引用关系表';
```

### 交易支付记录表
```sql
DROP TABLE IF EXISTS `{{$prefix}}trade_payments`;
CREATE TABLE `{{$prefix}}trade_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `trade_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易ID',
  `total_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额（单位：分）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_ip` int(11) NOT NULL DEFAULT '0' COMMENT '创建IP',
  `paid_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `payment_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式ID',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方交易号',
  `payer_account` varchar(50) NOT NULL DEFAULT '' COMMENT '付款人帐号',
  `paid_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '实付金额（单位：分）',
  `refund_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '退款金额（单位：分）',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易支付记录表';
```

## 附录二：几种主流支付方式的主要配置参数列表
### 支付宝
- `out_trade_no`: 外部订单号
- `subject`: 标题
- `body`: 简要描述
- `total_fee`: 金额
- `it_b_pay`: 有效期（取值比较奇特，参照官方文档）
- `notify_url`: 回调地址
- `return_url`: 同步跳转地址
- `show_url`: 商户交易信息地址

### 微信支付
- `out_trade_no`: 外部订单号
- `body`: 简要描述
- `total_fee`: 金额
- `notify_url`: 回调地址
- `attach`: 透传字段
- `trade_type`：交易类型：JSAPI，NATIVE，APP

### 银联支付
- `frontUrl`: 同步跳转地址
- `backUrl`: 异步回调地址
- `orderId`: 外部订单号
- `txnAmt`: 金额
- `orderDesc`: 简要描述
- `reqReserved`: 透传字段