DROP TABLE IF EXISTS `{{$prefix}}apidoc_apis`;
CREATE TABLE `{{$prefix}}apidoc_apis` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'App ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `router` varchar(100) NOT NULL DEFAULT '' COMMENT '路由',
  `description` text NOT NULL COMMENT '描述',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `http_method` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'HTTP请求方式',
  `need_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `sample_response` text NOT NULL COMMENT '响应示例',
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_id-router` (`app_id`,`router`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='接口';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_inputs`;
CREATE TABLE `{{$prefix}}apidoc_inputs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '接口ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必须',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '参数类型',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `api_id` (`api_id`)
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
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
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
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
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
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `api_id` (`api_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='接口响应参数';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_api_error_codes`;
CREATE TABLE `{{$prefix}}apidoc_api_error_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Api ID',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '错误码',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '错误描述',
  `solution` varchar(500) NOT NULL DEFAULT '' COMMENT '解决方案',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_id-code` (`api_id`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='api错误码';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_apps`;
CREATE TABLE `{{$prefix}}apidoc_apps` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '应用名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '应用描述',
  `need_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '仅登录用户可见',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='api应用信息';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_common_inputs`;
CREATE TABLE `{{$prefix}}apidoc_common_inputs` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必须',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '参数类型',
  `sample` varchar(255) NOT NULL DEFAULT '' COMMENT '示例值',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '排序值',
  `since` varchar(30) NOT NULL DEFAULT '' COMMENT '自从',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='公共请求参数';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_error_codes`;
CREATE TABLE `{{$prefix}}apidoc_error_codes` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '错误码',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '错误描述',
  `solution` varchar(500) NOT NULL DEFAULT '' COMMENT '解决方案',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='错误码';


-- API分类
INSERT INTO `{{$prefix}}categories` (`title`, `alias`, `parent`, `is_system`) VALUES ('API分类', '_system_api', '0', '1');

-- 后台菜单
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5000', '100', 'apidoc-api', 'API', 'fa fa-mobile', 'javascript:');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5001', '5000', '', 'API列表', '', 'apidoc/admin/api/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5002', '5000', '', 'APP列表', '', 'apidoc/admin/app/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5003', '5000', '', '错误码管理', '', 'apidoc/admin/error-code/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5004', '5000', '', '公共请求参数', '', 'apidoc/admin/common-input/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5100', '100', 'apidoc-model', '数据模型', 'fa fa-cubes', 'javascript:');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5101', '5100', '', '数据模型列表', '', 'apidoc/admin/model/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('5102', '5100', '', '新增数据模型', '', 'apidoc/admin/model/create');

-- 预定义特殊对象
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('1', 'String', '\"Hello World\"', '字符串');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('2', 'Int', '10000', '数字');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('3', 'sInt', '\"10000\"', '字符串类型的整数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('4', 'Float', '12.345', '小数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('5', 'sFloat', '\"12.345\"', '字符串类型的小数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('6', 'Boolean', 'true', '布尔');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('7', 'Bit', '0', '数字0/1');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('8', 'sBit', '\"1\"', '字符串类型的0/1');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('9', 'Datetime', '\"2016-04-17 23:03:06\"', '日期时间');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('10', 'Date', '\"2016-04-17\"', '日期（不带时间）');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('11', 'Timestamp', '1460905386', '数字时间戳');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('12', 'sTimestamp', '\"1460905386\"', '字符串时间戳');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('13', 'Price', '\"112.38\"', '价格。字符串类型数字，带2位小数');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('14', 'Url', '\"http://www.faycms.com\"', '网址');
INSERT INTO `{{$prefix}}apidoc_models` (`id`, `name`, `sample`, `description`) VALUES ('15', 'Map', '{\"1\":\"已关注\",\"2\":\"未关注\"}', '键值JSON');

INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `create_time`, `update_time`, `is_system`) VALUES ('apidoc:api_uri', 'apidoc/frontend/api/item?api_id={$id}', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `create_time`, `update_time`, `is_system`) VALUES ('apidoc:model_uri', 'apidoc/frontend/model/item?model_id={$id}', '{{$time}}', '{{$time}}', '1');