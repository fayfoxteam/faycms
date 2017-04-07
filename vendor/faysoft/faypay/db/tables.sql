DROP TABLE IF EXISTS `{{$prefix}}payments`;
CREATE TABLE `{{$prefix}}payments` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '支付编码',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '支付名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '支付描述',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `config` text COMMENT '配置信息JSON',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='付款方式';

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
  `show_url` varchar(255) NOT NULL DEFAULT '' COMMENT '商品展示网址',
  `return_url` varchar(255) NOT NULL DEFAULT '' COMMENT '页面跳转同步通知地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易记录';

DROP TABLE IF EXISTS `{{$prefix}}trade_refers`;
CREATE TABLE `{{$prefix}}trade_refers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `trade_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '交易类型',
  `refer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联ID',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易引用关系表';

DROP TABLE IF EXISTS `{{$prefix}}trade_payments`;
CREATE TABLE `{{$prefix}}trade_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `trade_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易ID',
  `total_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额（单位：分）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_ip` int(11) NOT NULL DEFAULT '0' COMMENT '创建IP',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `notify_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回调时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `payment_method_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式ID',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方交易号',
  `payer_account` varchar(50) NOT NULL DEFAULT '' COMMENT '付款人帐号',
  `paid_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '实付金额（单位：分）',
  `refund_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '退款金额（单位：分）',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易支付记录表';