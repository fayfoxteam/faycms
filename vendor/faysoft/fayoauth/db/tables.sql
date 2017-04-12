DROP TABLE IF EXISTS `{{$prefix}}oauth_apps`;
CREATE TABLE `{{$prefix}}oauth_apps` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '登录方式编码',
  `app_id` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方应用ID',
  `app_secret` varchar(50) NOT NULL DEFAULT '' COMMENT 'App Secret',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='第三方登录方式';

DROP TABLE IF EXISTS `{{$prefix}}oauth_user_connects`;
CREATE TABLE `{{$prefix}}user_connects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `oauth_app_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'oauth_apps表ID',
  `open_id` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方应用对外ID',
  `unionid` varchar(50) NOT NULL DEFAULT '' COMMENT 'Union ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'Access Token',
  `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'access_token过期时间戳',
  `refresh_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'Refresh Token',
  PRIMARY KEY (`id`),
  UNIQUE KEY `open_id` (`open_id`) USING BTREE,
  UNIQUE KEY `user_id-oauth_app_id` (`user_id`,`oauth_app_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='第三方登录信息';