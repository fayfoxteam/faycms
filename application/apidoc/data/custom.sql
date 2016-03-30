DROP TABLE IF EXISTS `{{$prefix}}apidoc_apis`;
CREATE TABLE `{{$prefix}}apidoc_apis` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `router` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `router` (`router`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='接口';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_inputs`;
CREATE TABLE `{{$prefix}}apidoc_inputs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `sample` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='接口输入参数';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_models`;
CREATE TABLE `{{$prefix}}apidoc_models` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COMMENT='数据模型';

DROP TABLE IF EXISTS `{{$prefix}}apidoc_outputs`;
CREATE TABLE `{{$prefix}}apidoc_outputs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `model_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  `sample` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='接口输出参数';