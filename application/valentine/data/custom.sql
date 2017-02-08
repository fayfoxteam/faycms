DROP TABLE IF EXISTS `{{$prefix}}valentine_constellation_matchings`;
CREATE TABLE `{{$prefix}}valentine_constellation_matchings` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `constellation_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '星座ID',
  `match_constellation_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '匹配星座ID',
  `score` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '得分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='星座配对系数表';

DROP TABLE IF EXISTS `{{$prefix}}valentine_constellations`;
CREATE TABLE `{{$prefix}}valentine_constellations` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '星座名称',
  `start_month` tinyint(4) NOT NULL DEFAULT '0' COMMENT '开始月份',
  `start_date` tinyint(4) NOT NULL DEFAULT '0' COMMENT '开始日期',
  `end_month` tinyint(4) NOT NULL DEFAULT '0' COMMENT '结束月份',
  `end_date` tinyint(4) NOT NULL DEFAULT '0' COMMENT '结束日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='星座表';

DROP TABLE IF EXISTS `{{$prefix}}valentine_user_extra`;
CREATE TABLE `{{$prefix}}valentine_user_extra` (
  `user_id` int(10) unsigned NOT NULL,
  `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `constellation_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '星座',
  `birthday` date DEFAULT NULL COMMENT '生日',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户扩展信息';

DROP TABLE IF EXISTS `{{$prefix}}valentine_user_teams`;
CREATE TABLE `{{$prefix}}valentine_user_teams` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户1ID',
  `user_id2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '配对时间',
  `photo` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '合影图片文件',
  `blessing` varchar(255) NOT NULL DEFAULT '' COMMENT '对公司的祝福',
  `votes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '得票数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id-user_id2` (`user_id`,`user_id2`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户配对表';

DROP TABLE IF EXISTS `{{$prefix}}valentine_votes`;
CREATE TABLE `{{$prefix}}valentine_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` smallint(6) NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票人用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='投票记录表';

-- 星座表数据
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('1', '白羊座', '3', '21', '4', '19');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('2', '金牛座', '4', '20', '5', '20');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('3', '双子座', '5', '21', '6', '21');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('4', '巨蟹座', '6', '22', '7', '22');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('5', '狮子座', '7', '23', '8', '22');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('6', '处女座', '8', '23', '9', '22');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('7', '天秤座', '9', '23', '10', '23');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('8', '天蝎座', '10', '24', '11', '22');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('9', '射手座', '11', '23', '12', '21');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('10', '摩羯座', '12', '22', '1', '19');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('11', '水瓶座', '1', '20', '2', '18');
INSERT INTO `{{$prefix}}valentine_constellations` (`id`, `name`, `start_month`, `start_date`, `end_month`, `end_date`) VALUES ('12', '双鱼座', '2', '19', '3', '20');

-- 星座配对系数表数据
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('1', '1', '5', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('2', '1', '1', '80');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('3', '1', '2', '70');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('4', '2', '6', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('5', '2', '10', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('6', '2', '4', '90');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('7', '3', '11', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('8', '3', '7', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('9', '3', '9', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('10', '4', '12', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('11', '4', '7', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('12', '4', '10', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('13', '5', '9', '98');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('14', '5', '1', '91');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('15', '5', '11', '85');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('16', '6', '10', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('17', '6', '2', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('18', '6', '12', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('19', '7', '3', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('20', '7', '11', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('21', '7', '5', '90');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('22', '8', '12', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('23', '8', '6', '80');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('24', '8', '9', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('25', '9', '1', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('26', '9', '5', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('27', '9', '3', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('28', '10', '2', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('29', '10', '6', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('30', '10', '12', '90');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('31', '11', '7', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('32', '11', '3', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('33', '11', '5', '60');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('34', '12', '8', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('35', '12', '4', '100');
INSERT INTO `{{$prefix}}valentine_constellation_matchings` (`id`, `constellation_id`, `match_constellation_id`, `score`) VALUES ('36', '12', '10', '90');
