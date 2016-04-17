DROP TABLE IF EXISTS `{{$prefix}}apidoc_apis`;
CREATE TABLE `{{$prefix}}apidoc_apis` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `router` varchar(100) NOT NULL DEFAULT '' COMMENT '路由',
  `description` text NOT NULL COMMENT '描述',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `http_method` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'HTTP请求方式',
  `need_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `sample_response` text NOT NULL COMMENT '响应示例',
  PRIMARY KEY (`id`),
  UNIQUE KEY `router` (`router`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET={{$charset}} COMMENT='接口';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_inputs`;
CREATE TABLE `{{$prefix}}apidoc_inputs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '接口ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必须',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '参数类型',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='接口输入参数';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_model_props`;
CREATE TABLE `{{$prefix}}apidoc_model_props` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '数据模型ID',
  `is_array` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是数组',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '属性名称',
  `type` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='数据模型属性';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_models`;
CREATE TABLE `{{$prefix}}apidoc_models` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '数据模型名称',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET={{$charset}} COMMENT='数据模型';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_outputs`;
CREATE TABLE `{{$prefix}}apidoc_outputs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'API ID',
  `model_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '数据模型ID',
  `is_array` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是数组',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '参数名称',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序值',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='接口响应参数';


-- API分类
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('1000', 'API分类', '_system_api', '0', '1');

-- 后台菜单
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5000', '100', 'api', 'API', 'fa fa-mobile', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5001', '5000', '', 'API列表', '', 'admin/api/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5002', '5000', '', '新增API', '', 'admin/api/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5003', '5000', '', 'API分类', '', 'admin/api/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5004', '5000', '', '数据模型列表', '', 'admin/model/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5005', '5000', '', '新增数据模型', '', 'admin/model/create');

-- 预定义特殊对象
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('1', 'String', '\"Hello World\"', '字符串');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('2', 'Int', '10000', '数字');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('3', 'sInt', '\"10000\"', '字符串类型的整数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('4', 'Float', '12.345', '小数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('5', 'sFloat', '\"12.345\"', '字符串类型的小数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('6', 'Boolean', 'true', '布尔');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('7', 'Bit', '0', '数字0/1');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('8', 'sBit', '\"1\"', '字符串类型的0/1');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('9', 'Datetime', '\"2016-04-17 19:24:14\"', '日期时间，所有日期时间不足2位均有前导0');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('10', 'Date', '\"2016-04-17\"', '日期（不带时间），月份日期不足2位均有前导0');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('11', 'Timestamp', '1460892254', '数字时间戳');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('12', 'sTimestamp', '\"1460892254\"', '字符串时间戳');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('13', 'Price', '\"112.38\"', '价格。字符串类型数字，带2位小数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('14', 'Url', '\"http://www.faycms.com\"', '网址');
