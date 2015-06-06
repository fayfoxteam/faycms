-- 创建账单表
DROP TABLE IF EXISTS `{{$prefix}}blog_bills`;
CREATE TABLE `{{$prefix}}blog_bills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 基础分类
INSERT INTO `{{$prefix}}categories` (id, parent, title, alias, is_system) VALUES ('1000', '1', '博文', '_blog', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, title, alias, is_system) VALUES ('1001', '1', '作品', '_work', '1');

-- 两个widget
INSERT INTO `{{$prefix}}widgets` VALUES ('1', 'categories', '{\"hierarchical\":0,\"top\":1000,\"title\":\"\\u5206\\u7c7b\",\"uri\":\"cat\\/{$id}\",\"template\":\"frontend\\/widget\\/categories\"}', 'fay/categories', '文章分类列表', '1');
INSERT INTO `{{$prefix}}widgets` VALUES ('2', 'recent_posts', '{\"subclassification\":1,\"top\":1,\"title\":\"\\u8fd1\\u671f\\u6587\\u7ae0\",\"number\":5,\"uri\":\"post\\/{$id}\",\"template\":\"frontend\\/widget\\/recent_posts\",\"date_format\":\"\",\"thumbnail\":0,\"order\":\"publish_time\"}', 'fay/category_post', '最新发布', '1');

-- 记账分类
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('2000', '0', '_system_bill', '记账', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('2001', '2000', 'bill_in', '收入', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('2002', '2000', 'bill_out', '支出', '1');

-- 记账用户角色
INSERT INTO `{{$prefix}}roles` VALUES ('2', '记账用户', '定制需求', '0', '1');