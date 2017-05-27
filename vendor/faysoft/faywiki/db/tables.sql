DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_favorites`;
CREATE TABLE `{{$prefix}}wiki_doc_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `doc_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id-doc_id` (`user_id`,`doc_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='百科收藏表';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_histories`;
CREATE TABLE `{{$prefix}}wiki_doc_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `doc_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(500) NOT NULL DEFAULT '' COMMENT '标题',
  `abstract` text COMMENT '正文',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  PRIMARY KEY (`id`),
  KEY `doc_id` (`doc_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文章历史版本存档';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_likes`;
CREATE TABLE `{{$prefix}}wiki_doc_likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `doc_id` mediumint(8) unsigned NOT NULL COMMENT '文档ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id-doc_id` (`user_id`,`doc_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='百科点赞表';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_meta`;
CREATE TABLE `{{$prefix}}wiki_doc_meta` (
  `doc_id` int(10) unsigned NOT NULL COMMENT '文章ID',
  `last_view_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `real_views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实阅读数',
  `likes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `real_likes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实点赞数',
  `favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `real_favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实收藏数',
  `shares` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  `real_shares` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实分享数',
  PRIMARY KEY (`doc_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文档计数信息';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_prop_histories`;
CREATE TABLE `{{$prefix}}wiki_doc_prop_histories` (
  `id` int(11) NOT NULL,
  `history_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '历史记录id',
  `doc_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `prop_label` varchar(255) NOT NULL DEFAULT '' COMMENT '属性键',
  `prop_content` text COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文档属性历史';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_prop_int`;
CREATE TABLE `{{$prefix}}wiki_doc_prop_int` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `relation_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation_id-prop_id-content` (`relation_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文档自定义属性-int';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_prop_text`;
CREATE TABLE `{{$prefix}}wiki_doc_prop_text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `relation_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` text COMMENT '属性值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation_id-prop_id` (`relation_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文档自定义属性-text';

DROP TABLE IF EXISTS `{{$prefix}}wiki_doc_prop_varchar`;
CREATE TABLE `{{$prefix}}wiki_doc_prop_varchar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `relation_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id-prop_id` (`relation_id`,`prop_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文档自定义属性-varchar';

DROP TABLE IF EXISTS `{{$prefix}}wiki_docs`;
CREATE TABLE `{{$prefix}}wiki_docs` (
  `id` mediumint(9) NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `abstract` text COMMENT '摘要',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `write_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '编辑锁',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='百科文档';