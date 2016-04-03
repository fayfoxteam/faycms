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
  `version` varchar(30) NOT NULL DEFAULT '' COMMENT '版本',
  PRIMARY KEY (`id`),
  UNIQUE KEY `router` (`router`)
) ENGINE=MyISAM  DEFAULT CHARSET={{$charset}} COMMENT='接口';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_apis_outputs`;
CREATE TABLE `{{$prefix}}apidoc_apis_outputs` (
  `api_id` smallint(5) unsigned NOT NULL COMMENT 'API ID',
  `output_id` mediumint(8) unsigned NOT NULL COMMENT '输出参数ID',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序值',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `version` varchar(30) NOT NULL DEFAULT '' COMMENT '版本',
  PRIMARY KEY (`api_id`,`output_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}apidoc_inputs`;
CREATE TABLE `{{$prefix}}apidoc_inputs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '接口ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必须',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '参数类型',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `version` varchar(30) NOT NULL DEFAULT '' COMMENT '版本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='接口输入参数';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_outputs`;
CREATE TABLE `{{$prefix}}apidoc_outputs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `type` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `sample` text NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父节点',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `version` varchar(30) NOT NULL DEFAULT '' COMMENT '版本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET={{$charset}} COMMENT='接口输出参数';