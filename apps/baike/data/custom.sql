DROP TABLE IF EXISTS `{{$prefix}}baike_domain_suffixes`;
CREATE TABLE `{{$prefix}}baike_domain_suffixes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `suffix` varchar(30) NOT NULL DEFAULT '' COMMENT '域名后缀（带点号）',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '65535' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='域名后缀';