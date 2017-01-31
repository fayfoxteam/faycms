DROP TABLE IF EXISTS `{{$prefix}}guangong_arms`;
CREATE TABLE `{{$prefix}}guangong_arms` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `picture` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片',
  `sort` tinyint(100) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='兵种表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_attendances`;
CREATE TABLE `{{$prefix}}guangong_attendances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `create_date` date NOT NULL COMMENT '出勤日期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '出勤时间',
  `continuous` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '连续出勤天数',
  PRIMARY KEY (`id`),
  KEY `user_id-create_date` (`user_id`,`create_date`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='出勤记录表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_defence_areas`;
CREATE TABLE `{{$prefix}}guangong_defence_areas` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '防区ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '防区名称',
  `picture` int(10) unsigned NOT NULL COMMENT '图片',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='防区表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_hours`;
CREATE TABLE `{{$prefix}}guangong_hours` (
  `id` tinyint(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '名称',
  `start_hour` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '开始小时（一个时辰包含2个小时）',
  `end_hour` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '结束小时（一个时辰包含2个小时）',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `zodiac` varchar(500) NOT NULL DEFAULT '' COMMENT '生肖',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='时辰表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_ranks`;
CREATE TABLE `{{$prefix}}guangong_ranks` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '兵制',
  `captain` varchar(20) NOT NULL DEFAULT '' COMMENT '统兵官',
  `soldiers` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '统领士兵数',
  `months` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '获得军衔规则：月',
  `times` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '获得军衔规则：累计次数',
  `continuous` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '获得军衔规则：连续签到天数',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '军衔高低（值越高表示军衔越高）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='军衔表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_extra`;
CREATE TABLE `{{$prefix}}guangong_user_extra` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `birthday` date NOT NULL COMMENT '生日',
  `state` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '省',
  `city` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '市',
  `district` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '区/县',
  `arm_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '兵种',
  `defence_area_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '防区ID',
  `hour_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '时辰ID',
  `attendances` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '总出勤次数',
  `rank_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '军衔ID',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户扩展信息';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_group_users`;
CREATE TABLE `{{$prefix}}guangong_user_group_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '结盟ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成员ID',
  `accept` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否接受邀请',
  `words` varchar(255) NOT NULL DEFAULT '' COMMENT '我想对兄弟说',
  `public_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公开时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='结盟成员';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_groups`;
CREATE TABLE `{{$prefix}}guangong_user_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '称谓',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发起者',
  `vow` varchar(255) NOT NULL DEFAULT '' COMMENT '誓言',
  `count` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '结义人数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发起时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='结盟';

DROP TABLE IF EXISTS `{{$prefix}}guangong_vows`;
CREATE TABLE `{{$prefix}}guangong_vows` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '誓词',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='预定义誓词';