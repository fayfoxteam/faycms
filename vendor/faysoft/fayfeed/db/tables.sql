ALTER TABLE `{{$prefix}}user_counter`
ADD COLUMN `feeds`  smallint UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布动态数' AFTER `real_messages`;

DROP TABLE IF EXISTS `{{$prefix}}feeds`;
CREATE TABLE `{{$prefix}}feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text COMMENT '内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `publish_date` date NOT NULL COMMENT '发布日期',
  `timeline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序值',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `delete_time` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除时间',
  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}} COMMENT='动态表';

DROP TABLE IF EXISTS `{{$prefix}}feeds_files`;
CREATE TABLE `{{$prefix}}feeds_files` (
  `feed_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '动态ID',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  PRIMARY KEY (`feed_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='动态图片';

DROP TABLE IF EXISTS `{{$prefix}}feeds_tags`;
CREATE TABLE `{{$prefix}}feeds_tags` (
  `feed_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Tag Id',
  PRIMARY KEY (`feed_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='动态标签关联关系';