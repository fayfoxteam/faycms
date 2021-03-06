DROP TABLE IF EXISTS `{{$prefix}}actionlogs`;
CREATE TABLE `{{$prefix}}actionlogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Type',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT 'Note',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `refer` varchar(500) NOT NULL DEFAULT '0' COMMENT '关联ID',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}actions`;
CREATE TABLE `{{$prefix}}actions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '操作',
  `router` varchar(50) NOT NULL DEFAULT '' COMMENT '路由',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为公共路由',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent',
  PRIMARY KEY (`id`),
  UNIQUE KEY `router` (`router`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}analyst_caches`;
CREATE TABLE `{{$prefix}}analyst_caches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `date` date NOT NULL COMMENT 'Date',
  `hour` tinyint(3) NOT NULL DEFAULT '-1' COMMENT 'Hour',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Site',
  `pv` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Pv',
  `uv` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Uv',
  `ip` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Ip',
  `new_visitors` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'New Visitors',
  `bounce_rate` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Bounce Rate',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}analyst_macs`;
CREATE TABLE `{{$prefix}}analyst_macs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User Agent',
  `browser` varchar(30) NOT NULL DEFAULT '' COMMENT '浏览器内核',
  `browser_version` varchar(30) NOT NULL DEFAULT '' COMMENT '内核版本',
  `shell` varchar(30) NOT NULL DEFAULT '' COMMENT '浏览器套壳',
  `shell_version` varchar(30) NOT NULL DEFAULT '' COMMENT '套壳版本',
  `os` varchar(30) NOT NULL DEFAULT '' COMMENT '操作系统',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `screen_width` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '屏幕宽度',
  `screen_height` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '屏幕高度',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Url',
  `refer` varchar(255) NOT NULL DEFAULT '' COMMENT '来源url',
  `se` varchar(10) NOT NULL DEFAULT '' COMMENT 'Se',
  `keywords` varchar(50) NOT NULL DEFAULT '' COMMENT 'Keywords',
  `fmac` char(36) NOT NULL DEFAULT '' COMMENT 'FMac',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_date` date NOT NULL COMMENT '创建日期',
  `hour` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Hour',
  `trackid` varchar(30) NOT NULL DEFAULT '' COMMENT 'Trackid',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Site',
  PRIMARY KEY (`id`),
  KEY `fmac` (`fmac`),
  KEY `date` (`create_date`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}analyst_sites`;
CREATE TABLE `{{$prefix}}analyst_sites` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '站点名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}analyst_visits`;
CREATE TABLE `{{$prefix}}analyst_visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `mac` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Mac',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `refer` varchar(255) NOT NULL DEFAULT '' COMMENT 'Refer',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Url',
  `short_url` char(6) NOT NULL DEFAULT '' COMMENT 'Short Url',
  `trackid` varchar(30) NOT NULL DEFAULT '' COMMENT 'Trackid',
  `user_id` mediumint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `create_date` date NOT NULL COMMENT 'Create Date',
  `hour` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Hour',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Site',
  `views` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Views',
  `HTTP_CLIENT_IP` varchar(255) NOT NULL DEFAULT '' COMMENT 'HTTP CLIENT IP',
  `HTTP_X_FORWARDED_FOR` varchar(255) NOT NULL DEFAULT '' COMMENT 'HTTP X FORWARDED FOR',
  `REMOTE_ADDR` varchar(255) NOT NULL DEFAULT '' COMMENT 'REMOTE ADDR',
  PRIMARY KEY (`id`),
  KEY `pv` (`mac`,`short_url`,`create_time`),
  KEY `date` (`create_date`,`hour`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}categories`;
CREATE TABLE `{{$prefix}}categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父节点',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插图',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1000' COMMENT '排序值',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `is_nav` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否导航栏显示',
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '记录数',
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Value',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Value',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is System',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}cities`;
CREATE TABLE `{{$prefix}}cities` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT 'City',
  `parent` smallint(5) NOT NULL DEFAULT '0' COMMENT 'Parent',
  `spelling` varchar(50) NOT NULL DEFAULT '' COMMENT 'Spelling',
  `abbr` varchar(30) NOT NULL DEFAULT '' COMMENT '缩写',
  `short` varchar(30) NOT NULL DEFAULT '' COMMENT '单个首字母',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}contacts`;
CREATE TABLE `{{$prefix}}contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '真名',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT 'Email',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '电话',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '留言标题',
  `country` varchar(50) NOT NULL DEFAULT '' COMMENT '国家',
  `content` text NOT NULL COMMENT '留言内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `publish_time` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT '真实IP',
  `show_ip_int` int(11) NOT NULL DEFAULT '0' COMMENT '显示IP',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent',
  `reply` text NOT NULL COMMENT '回复',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '前台显示',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已读标记',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_answers`;
CREATE TABLE `{{$prefix}}exam_answers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Question Id',
  `answer` text NOT NULL COMMENT 'Answer',
  `is_right_answer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否正确答案',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  PRIMARY KEY (`id`),
  KEY `question` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_question_answer_text`;
CREATE TABLE `{{$prefix}}exam_exam_question_answer_text` (
  `exam_question_id` int(11) NOT NULL COMMENT 'Exam Question Id',
  `user_answer` text COMMENT 'User Answer',
  PRIMARY KEY (`exam_question_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_question_answers_int`;
CREATE TABLE `{{$prefix}}exam_exam_question_answers_int` (
  `exam_question_id` int(10) unsigned NOT NULL COMMENT 'Exam Question Id',
  `user_answer_id` mediumint(8) unsigned NOT NULL COMMENT 'User Answer Id',
  PRIMARY KEY (`exam_question_id`,`user_answer_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_exams`;
CREATE TABLE `{{$prefix}}exam_exams` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Paper Id',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Start Time',
  `end_time` int(10) unsigned NOT NULL COMMENT 'End Time',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Score',
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Total Score',
  `rand` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Rand',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_exams_questions`;
CREATE TABLE `{{$prefix}}exam_exams_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `exam_id` mediumint(8) unsigned NOT NULL COMMENT 'Exam Id',
  `question_id` mediumint(8) unsigned NOT NULL COMMENT 'Question Id',
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Total Score',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Score',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_paper_questions`;
CREATE TABLE `{{$prefix}}exam_paper_questions` (
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '试卷编号',
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '试题编号',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '分值',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  PRIMARY KEY (`paper_id`,`question_id`),
  KEY `question` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_papers`;
CREATE TABLE `{{$prefix}}exam_papers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '试卷名称',
  `description` text NOT NULL COMMENT '试卷描述',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `rand` tinyint(1) NOT NULL DEFAULT '100' COMMENT '随机题序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '试卷总分',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '考试开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '考试结束时间',
  `repeatedly` tinyint(1) NOT NULL DEFAULT '1' COMMENT '重复参考',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last Modified Time',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}exam_questions`;
CREATE TABLE `{{$prefix}}exam_questions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `question` text NOT NULL COMMENT '试题',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分值',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `rand` tinyint(1) NOT NULL DEFAULT '0' COMMENT '随机答案顺序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}files`;
CREATE TABLE `{{$prefix}}files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `raw_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Raw Name',
  `file_ext` varchar(10) NOT NULL DEFAULT '' COMMENT 'File Ext',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'File Size',
  `file_type` varchar(255) NOT NULL DEFAULT '' COMMENT 'File Type',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT 'File Path',
  `client_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Client Name',
  `is_image` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Image',
  `image_width` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Image Width',
  `image_height` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Image Height',
  `upload_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Upload Time',
  `user_id` int(10) unsigned NOT NULL COMMENT 'User Id',
  `downloads` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Downloads',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  `qiniu` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Qiniu',
  PRIMARY KEY (`id`),
  KEY `raw_name` (`raw_name`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}follows`;
CREATE TABLE `{{$prefix}}follows` (
  `fans_id` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `relation` tinyint(4) NOT NULL DEFAULT '1' COMMENT '单向/双向关注',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`fans_id`,`user_id`),
  KEY `fans` (`user_id`,`fans_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='关注关系';

DROP TABLE IF EXISTS `{{$prefix}}goods`;
CREATE TABLE `{{$prefix}}goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sub_stock` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '何时减库存',
  `post_fee` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新品',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热销',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '10000' COMMENT '排序值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_cat_prop_values`;
CREATE TABLE `{{$prefix}}goods_cat_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值i',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_cat_props`;
CREATE TABLE `{{$prefix}}goods_cat_props` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '编辑框类型',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '必选标记',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `is_sale_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否销售属性',
  `is_input_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可自定义属性',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_counter`;
CREATE TABLE `{{$prefix}}goods_counter` (
  `goods_id` int(11) unsigned NOT NULL COMMENT '商品ID',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `real_views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实浏览量',
  `sales` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '总销量',
  `real_sales` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实总销量',
  `reviews` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '评价数',
  `real_reviews` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实评价数',
  `favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `real_favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实收藏数',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_extra`;
CREATE TABLE `{{$prefix}}goods_extra` (
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品ID',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO Keywords',
  `seo_description` varchar(500) NOT NULL DEFAULT '' COMMENT 'SEO Description',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `weight` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '单位:kg',
  `size` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '单位:立方米',
  `sn` varchar(50) NOT NULL DEFAULT '' COMMENT '货号',
  `rich_text` text COMMENT '富文本描述',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_files`;
CREATE TABLE `{{$prefix}}goods_files` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品Id',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件Id',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`goods_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_prop_values`;
CREATE TABLE `{{$prefix}}goods_prop_values` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品Id',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '属性Id',
  `prop_value_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性值Id',
  `prop_value_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '属性别名',
  PRIMARY KEY (`goods_id`,`prop_id`,`prop_value_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}goods_skus`;
CREATE TABLE `{{$prefix}}goods_skus` (
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品ID',
  `sku_key` varchar(100) NOT NULL DEFAULT '' COMMENT 'SKU Key',
  `price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `quantity` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `tsces` varchar(50) NOT NULL DEFAULT '' COMMENT '商家编码',
  PRIMARY KEY (`goods_id`,`sku_key`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}item_prop_values`;
CREATE TABLE `{{$prefix}}item_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Title',
  `title_alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'Title Alias',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is Terminal',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}item_props`;
CREATE TABLE `{{$prefix}}item_props` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `is_input_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Input Prop',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Type',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Required',
  `parent_pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent Pid',
  `parent_vid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent Vid',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Title',
  `is_sale_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Sale Prop',
  `is_color_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Color Prop',
  `is_enum_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Enum Prop',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `multi` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Multi',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}keywords`;
CREATE TABLE `{{$prefix}}keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `keyword` varchar(50) NOT NULL DEFAULT '' COMMENT '关键词',
  `link` varchar(500) NOT NULL DEFAULT '' COMMENT '链接地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_favorites`;
CREATE TABLE `{{$prefix}}post_favorites` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `post_id` int(10) unsigned NOT NULL COMMENT '文章ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`user_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_likes`;
CREATE TABLE `{{$prefix}}post_likes` (
  `post_id` int(10) unsigned NOT NULL COMMENT '文章ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`post_id`,`user_id`),
  KEY `likes` (`user_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_meta`;
CREATE TABLE `{{$prefix}}post_meta` (
  `post_id` int(10) unsigned NOT NULL COMMENT '文章ID',
  `last_view_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `real_views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实阅读数',
  `comments` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `real_comments` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '真实评论数',
  `likes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `real_likes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实点赞数',
  `favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `real_favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实收藏数',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}links`;
CREATE TABLE `{{$prefix}}links` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '网址',
  `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT '可见',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `target` varchar(25) NOT NULL DEFAULT '' COMMENT '打开方式',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last Modified Time',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `logo` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Logo',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}logs`;
CREATE TABLE `{{$prefix}}logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Type',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT 'Code',
  `data` text NOT NULL COMMENT 'Data',
  `create_date` date NOT NULL COMMENT 'Create Date',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `user_agent` varchar(255) NOT NULL COMMENT 'User Agent',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}menus`;
CREATE TABLE `{{$prefix}}menus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Value',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Value',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `sub_title` varchar(255) NOT NULL DEFAULT '' COMMENT '二级标题',
  `css_class` varchar(50) NOT NULL DEFAULT '' COMMENT 'CSS Class',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '连接地址',
  `target` varchar(30) NOT NULL DEFAULT '' COMMENT '打开方式',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}messages`;
CREATE TABLE `{{$prefix}}messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `to_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '留言给用户ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text COMMENT '内容',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '马甲信息',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根评论ID',
  `left_value` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '左值',
  `right_value` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '右值',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `post` (`to_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}notifications`;
CREATE TABLE `{{$prefix}}notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `sender` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发件人',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `active_key` varchar(32) NOT NULL DEFAULT '' COMMENT '随机码',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `validity_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}options`;
CREATE TABLE `{{$prefix}}options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `option_name` varchar(200) NOT NULL COMMENT '参数名',
  `option_value` text NOT NULL COMMENT '参数值',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT 'Description',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last Modified Time',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is System',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}orders`;
CREATE TABLE `{{$prefix}}orders` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `buyer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买家ID',
  `buyer_note` varchar(255) NOT NULL DEFAULT '' COMMENT '买家留言',
  `seller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `seller_note` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家留言',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `goods_fee` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品总价',
  `shipping_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `adjust_fee` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '卖家手工调整金额（差值）',
  `total_fee` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '订单总价',
  `paid_fee` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  `seller_rate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评价',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `receiver_state` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '收货人所在省',
  `receiver_city` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '收货人所在市',
  `receiver_district` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '收货人所在区',
  `receiver_address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人详细地址',
  `receiver_name` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `receiver_mobile` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人的手机号码',
  `receiver_phone` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人的电话号码',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单创建时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `consign_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '卖家发货时间',
  `comfirm_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '确认收货时间',
  `close_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '交易关闭原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='订单表';

DROP TABLE IF EXISTS `{{$prefix}}order_goods`;
CREATE TABLE `{{$prefix}}order_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标题',
  `price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `sku_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'SKU Key',
  `sku_properties_name` varchar(500) NOT NULL DEFAULT '' COMMENT 'SKU的值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='订单商品表';

DROP TABLE IF EXISTS `{{$prefix}}pages`;
CREATE TABLE `{{$prefix}}pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(500) NOT NULL COMMENT '标题',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `content` text NOT NULL COMMENT '正文',
  `author` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '作者',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图',
  `comments` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `seo_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  `abstract` text NOT NULL COMMENT '摘要',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}pages_categories`;
CREATE TABLE `{{$prefix}}pages_categories` (
  `page_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Page Id',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  PRIMARY KEY (`page_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_prop_int`;
CREATE TABLE `{{$prefix}}post_prop_int` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性值',
  PRIMARY KEY (`post_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_prop_text`;
CREATE TABLE `{{$prefix}}post_prop_text` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` text NOT NULL COMMENT '属性值',
  PRIMARY KEY (`post_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_prop_varchar`;
CREATE TABLE `{{$prefix}}post_prop_varchar` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`post_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}posts`;
CREATE TABLE `{{$prefix}}posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(500) NOT NULL DEFAULT '' COMMENT '标题',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `content` text NOT NULL COMMENT '正文',
  `content_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '正文类型（普通文本，符文本，markdown）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `publish_date` date NOT NULL COMMENT '发布日期',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `user_id` int(9) unsigned NOT NULL DEFAULT '0' COMMENT '作者ID',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '文章状态',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图',
  `abstract` varchar(500) NOT NULL DEFAULT '' COMMENT '摘要',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`),
  KEY `cat` (`cat_id`),
  KEY `deleted-status-publish_time` (`deleted`,`status`,`publish_time`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}posts_categories`;
CREATE TABLE `{{$prefix}}posts_categories` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  PRIMARY KEY (`post_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_comments`;
CREATE TABLE `{{$prefix}}post_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text COMMENT '内容',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '马甲信息',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根评论ID',
  `left_value` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '左值',
  `right_value` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '右值',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}post_extra`;
CREATE TABLE `{{$prefix}}post_extra` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO Keywords',
  `seo_description` varchar(500) NOT NULL DEFAULT '' COMMENT 'SEO Descriotion',
  `markdown` text COMMENT 'Markdown文本',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}posts_files`;
CREATE TABLE `{{$prefix}}posts_files` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_image` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否为图片',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  PRIMARY KEY (`post_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}posts_tags`;
CREATE TABLE `{{$prefix}}posts_tags` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Tag Id',
  PRIMARY KEY (`post_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}prop_values`;
CREATE TABLE `{{$prefix}}prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Refer',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Title',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Default',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}props`;
CREATE TABLE `{{$prefix}}props` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Refer',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Type',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名称',
  `element` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Element',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '必选标记',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is Show',
  PRIMARY KEY (`id`),
  KEY `refer-type-deleted` (`refer`,`type`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}regions`;
CREATE TABLE `{{$prefix}}regions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent Id',
  `name` varchar(120) NOT NULL DEFAULT '' COMMENT 'Name',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'Type',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}roles`;
CREATE TABLE `{{$prefix}}roles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `admin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否管理员角色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}roles_actions`;
CREATE TABLE `{{$prefix}}roles_actions` (
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Role Id',
  `action_id` smallint(5) unsigned NOT NULL COMMENT 'Action Id',
  PRIMARY KEY (`role_id`,`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}roles_cats`;
CREATE TABLE `{{$prefix}}roles_cats` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT 'Role Id',
  `cat_id` mediumint(8) unsigned NOT NULL COMMENT 'Cat Id',
  PRIMARY KEY (`role_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}spider_logs`;
CREATE TABLE `{{$prefix}}spider_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `spider` varchar(50) NOT NULL DEFAULT '' COMMENT 'Spider',
  `user_agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User Agent',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Url',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}tag_counter`;
CREATE TABLE `{{$prefix}}tag_counter` (
  `tag_id` int(10) unsigned NOT NULL COMMENT '标签ID',
  `posts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章数',
  `feeds` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '动态数',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='标签计数器';

DROP TABLE IF EXISTS `{{$prefix}}tags`;
CREATE TABLE `{{$prefix}}tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标签',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}templates`;
CREATE TABLE `{{$prefix}}templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(500) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  `description` text NOT NULL COMMENT '对模版的描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}user_addresses`;
CREATE TABLE `{{$prefix}}user_addresses` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `state` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所在省',
  `city` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所在市',
  `district` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所在区',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(30) NOT NULL DEFAULT '' COMMENT '手机号码',
  `phone` varchar(30) NOT NULL DEFAULT '' COMMENT '电话号码',
  `zipcode` varchar(30) NOT NULL DEFAULT '' COMMENT '邮编',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认收货地址',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}user_counter`;
CREATE TABLE `{{$prefix}}user_counter` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `posts` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '文章数',
  `feeds` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '动态数',
  `follows` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `fans` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `messages` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '留言数',
  `real_messages` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '真实留言数',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户计数器';

DROP TABLE IF EXISTS `{{$prefix}}user_logins`;
CREATE TABLE `{{$prefix}}user_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `mac` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '唯一标识',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户登录记录';

DROP TABLE IF EXISTS `{{$prefix}}user_profile`;
CREATE TABLE `{{$prefix}}user_profile` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册IP',
  `login_times` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_time_online` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后在线时间',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  `refer` varchar(255) NOT NULL DEFAULT '' COMMENT '来源URL',
  `se` varchar(30) NOT NULL DEFAULT '' COMMENT '搜索引擎',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '搜索关键词',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}user_prop_int`;
CREATE TABLE `{{$prefix}}user_prop_int` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性值',
  PRIMARY KEY (`user_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}user_prop_text`;
CREATE TABLE `{{$prefix}}user_prop_text` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` text NOT NULL COMMENT '属性值',
  PRIMARY KEY (`user_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}user_prop_varchar`;
CREATE TABLE `{{$prefix}}user_prop_varchar` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`user_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}user_settings`;
CREATE TABLE `{{$prefix}}user_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `setting_key` varchar(50) NOT NULL DEFAULT '' COMMENT 'Setting Key',
  `setting_value` text NOT NULL COMMENT 'Setting Value',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id-setting_key` (`user_id`,`setting_key`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}users`;
CREATE TABLE `{{$prefix}}users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '登录名',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(30) NOT NULL DEFAULT '' COMMENT '手机号码',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(5) NOT NULL DEFAULT '' COMMENT '五位随机数',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `avatar` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户审核状态',
  `block` tinyint(1) NOT NULL DEFAULT '0' COMMENT '屏蔽用户',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父节点',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为管理员',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}users_notifications`;
CREATE TABLE `{{$prefix}}users_notifications` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收件人',
  `notification_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID',
  `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已读状态',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除状态',
  `processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否处理',
  `ignored` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否忽略',
  `option` varchar(255) NOT NULL DEFAULT '' COMMENT '附加参数',
  PRIMARY KEY (`user_id`,`notification_id`),
  KEY `unread` (`user_id`,`read`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}users_roles`;
CREATE TABLE `{{$prefix}}users_roles` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `role_id` smallint(5) unsigned NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id-user_id` (`role_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}vouchers`;
CREATE TABLE `{{$prefix}}vouchers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `sn` varchar(30) NOT NULL DEFAULT '' COMMENT 'Sn',
  `amount` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额/折扣',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `counts` smallint(5) NOT NULL DEFAULT '1' COMMENT '剩余次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}widgets`;
CREATE TABLE `{{$prefix}}widgets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `options` text NOT NULL COMMENT '实例参数',
  `widget_name` varchar(255) NOT NULL DEFAULT '' COMMENT '小工具名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '小工具描述',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `widgetarea` varchar(50) NOT NULL DEFAULT '' COMMENT '小工具域',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序值',
  `ajax` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否ajax引入',
  `cache` int(11) NOT NULL DEFAULT '-1' COMMENT '是否缓存',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}feed_comments`;
CREATE TABLE `{{$prefix}}feed_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `feed_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text COMMENT '内容',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '马甲信息',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根评论ID',
  `left_value` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '左值',
  `right_value` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '右值',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}} COMMENT='动态评论表';

DROP TABLE IF EXISTS `{{$prefix}}feed_extra`;
CREATE TABLE `{{$prefix}}feed_extra` (
  `feed_id` int(10) unsigned NOT NULL COMMENT '动态ID',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `longitude` float(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `latitude` float(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  PRIMARY KEY (`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='动态表扩展信息';

DROP TABLE IF EXISTS `{{$prefix}}feed_favorites`;
CREATE TABLE `{{$prefix}}feed_favorites` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `feed_id` int(10) unsigned NOT NULL COMMENT '动态ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`user_id`,`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};

DROP TABLE IF EXISTS `{{$prefix}}feed_likes`;
CREATE TABLE `{{$prefix}}feed_likes` (
  `feed_id` int(10) unsigned NOT NULL COMMENT '文章ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `sockpuppet` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '马甲信息',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT '追踪ID',
  PRIMARY KEY (`feed_id`,`user_id`),
  KEY `likes` (`user_id`,`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='动态点赞记录表';

DROP TABLE IF EXISTS `{{$prefix}}feed_meta`;
CREATE TABLE `{{$prefix}}feed_meta` (
  `feed_id` int(10) unsigned NOT NULL COMMENT 'Feed Id',
  `comments` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `real_comments` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '真实评论数',
  `likes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `real_likes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实点赞数',
  `favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `real_favorites` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '真实收藏数',
  PRIMARY KEY (`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='动态扩展信息表';

DROP TABLE IF EXISTS `{{$prefix}}feeds`;
CREATE TABLE `{{$prefix}}feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text COMMENT '内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `publish_date` date NOT NULL COMMENT '发布日期',
  `timeline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序值',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET={{$charset}} COMMENT='动态表';

DROP TABLE IF EXISTS `{{$prefix}}feeds_files`;
CREATE TABLE `{{$prefix}}feeds_files` (
  `feed_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '动态ID',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  PRIMARY KEY (`feed_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='动态图片';

DROP TABLE IF EXISTS `{{$prefix}}feeds_tags`;
CREATE TABLE `{{$prefix}}feeds_tags` (
  `feed_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Tag Id',
  PRIMARY KEY (`feed_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='动态标签关联关系';

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

DROP TABLE IF EXISTS `{{$prefix}}user_connects`;
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

DROP TABLE IF EXISTS `{{$prefix}}payments`;
CREATE TABLE `{{$prefix}}payments` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '支付编码',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '支付名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '支付描述',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `config` text COMMENT '配置信息JSON',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后编辑时间',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='付款方式';

DROP TABLE IF EXISTS `{{$prefix}}trades`;
CREATE TABLE `{{$prefix}}trades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '支付说明',
  `body` varchar(255) NOT NULL DEFAULT '' COMMENT '支付描述',
  `total_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '付款金额（单位：分）',
  `paid_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '已付金额（单位：分）',
  `trade_payment_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付记录ID（付成功的那条）',
  `refund_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '退款金额（单位：分）',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `create_ip` int(11) NOT NULL DEFAULT '0' COMMENT '创建IP',
  `show_url` varchar(255) NOT NULL DEFAULT '' COMMENT '商品展示网址',
  `return_url` varchar(255) NOT NULL DEFAULT '' COMMENT '页面跳转同步通知地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易记录';

DROP TABLE IF EXISTS `{{$prefix}}trade_refers`;
CREATE TABLE `{{$prefix}}trade_refers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `trade_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '交易类型',
  `refer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联ID',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易引用关系表';

DROP TABLE IF EXISTS `{{$prefix}}trade_payments`;
CREATE TABLE `{{$prefix}}trade_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `trade_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易ID',
  `total_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额（单位：分）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_ip` int(11) NOT NULL DEFAULT '0' COMMENT '创建IP',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `notify_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回调时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `payment_method_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式ID',
  `trade_no` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方交易号',
  `payer_account` varchar(50) NOT NULL DEFAULT '' COMMENT '付款人帐号',
  `paid_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '实付金额（单位：分）',
  `refund_fee` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '退款金额（单位：分）',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET={{$charset}} COMMENT='交易支付记录表';