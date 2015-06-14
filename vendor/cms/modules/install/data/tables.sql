DROP TABLE IF EXISTS `{{$prefix}}actionlogs`;
CREATE TABLE `{{$prefix}}actionlogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Type',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT 'Note',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `refer` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Refer',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}actions`;
CREATE TABLE `{{$prefix}}actions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '操作',
  `router` varchar(255) NOT NULL DEFAULT '' COMMENT '路由',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为公共路由',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent',
  PRIMARY KEY (`id`),
  KEY `router` (`router`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}analyst_caches`;
CREATE TABLE `{{$prefix}}analyst_caches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Date',
  `hour` tinyint(3) NOT NULL DEFAULT '-1' COMMENT 'Hour',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Site',
  `pv` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Pv',
  `uv` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Uv',
  `ip` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Ip',
  `new_visitors` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'New Visitors',
  `bounce_rate` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Bounce Rate',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `hash` char(32) NOT NULL DEFAULT '' COMMENT 'Hash',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '创建日期',
  `hour` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Hour',
  `trackid` varchar(30) NOT NULL DEFAULT '' COMMENT 'Trackid',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Site',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `date` (`create_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}analyst_sites`;
CREATE TABLE `{{$prefix}}analyst_sites` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '站点名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `create_date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Create Date',
  `hour` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Hour',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Site',
  `views` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Views',
  `HTTP_CLIENT_IP` varchar(255) NOT NULL DEFAULT '' COMMENT 'HTTP CLIENT IP',
  `HTTP_X_FORWARDED_FOR` varchar(255) NOT NULL DEFAULT '' COMMENT 'HTTP X FORWARDED FOR',
  `REMOTE_ADDR` varchar(255) NOT NULL DEFAULT '' COMMENT 'REMOTE ADDR',
  PRIMARY KEY (`id`),
  KEY `pv` (`mac`,`short_url`,`create_time`),
  KEY `date` (`create_date`,`hour`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}cat_prop_values`;
CREATE TABLE `{{$prefix}}cat_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Title',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}cat_props`;
CREATE TABLE `{{$prefix}}cat_props` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Type',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Required',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Title',
  `is_sale_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Sale Prop',
  `is_input_prop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Input Prop',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT 'Sort',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Value',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Value',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is System',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `left_right_value` (`left_value`,`right_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}cities`;
CREATE TABLE `{{$prefix}}cities` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT 'City',
  `parent` smallint(5) NOT NULL DEFAULT '0' COMMENT 'Parent',
  `spelling` varchar(50) NOT NULL DEFAULT '' COMMENT 'Spelling',
  `abbr` varchar(30) NOT NULL DEFAULT '' COMMENT '缩写',
  `short` varchar(30) NOT NULL DEFAULT '' COMMENT '单个首字母',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}contacts`;
CREATE TABLE `{{$prefix}}contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT 'Realname',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT 'Email',
  `phone` varchar(50) NOT NULL DEFAULT '' COMMENT 'Phone',
  `content` text NOT NULL COMMENT 'Content',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_answers`;
CREATE TABLE `{{$prefix}}exam_answers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Question Id',
  `answer` text NOT NULL COMMENT 'Answer',
  `is_right_answer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否正确答案',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  PRIMARY KEY (`id`),
  KEY `question` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_question_answer_text`;
CREATE TABLE `{{$prefix}}exam_exam_question_answer_text` (
  `exam_question_id` int(11) NOT NULL COMMENT 'Exam Question Id',
  `user_answer` text COMMENT 'User Answer',
  PRIMARY KEY (`exam_question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_question_answers_int`;
CREATE TABLE `{{$prefix}}exam_exam_question_answers_int` (
  `exam_question_id` int(10) unsigned NOT NULL COMMENT 'Exam Question Id',
  `user_answer_id` mediumint(8) unsigned NOT NULL COMMENT 'User Answer Id',
  PRIMARY KEY (`exam_question_id`,`user_answer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exams_questions`;
CREATE TABLE `{{$prefix}}exam_exams_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `exam_id` mediumint(8) unsigned NOT NULL COMMENT 'Exam Id',
  `question_id` mediumint(8) unsigned NOT NULL COMMENT 'Question Id',
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Total Score',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Score',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_paper_questions`;
CREATE TABLE `{{$prefix}}exam_paper_questions` (
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '试卷编号',
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '试题编号',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '分值',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  PRIMARY KEY (`paper_id`,`question_id`),
  KEY `question` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}favourites`;
CREATE TABLE `{{$prefix}}favourites` (
  `user_id` int(10) unsigned NOT NULL COMMENT 'User Id',
  `post_id` int(10) unsigned NOT NULL COMMENT 'Post Id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`user_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}files`;
CREATE TABLE `{{$prefix}}files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `raw_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Raw Name',
  `file_ext` varchar(10) NOT NULL DEFAULT '' COMMENT 'File Ext',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'File Size',
  `file_type` varchar(30) NOT NULL DEFAULT '' COMMENT 'File Type',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT 'File Path',
  `client_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Client Name',
  `is_image` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Image',
  `image_width` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Image Width',
  `image_height` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Image Height',
  `upload_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Upload Time',
  `user_id` int(10) unsigned NOT NULL COMMENT 'User Id',
  `downloads` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Downloads',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Type',
  `qiniu` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Qiniu',
  PRIMARY KEY (`id`),
  KEY `raw_name` (`raw_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}followers`;
CREATE TABLE `{{$prefix}}followers` (
  `user_id` int(10) unsigned NOT NULL COMMENT 'User Id',
  `follower` int(10) unsigned NOT NULL COMMENT 'Follower',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`user_id`,`follower`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods`;
CREATE TABLE `{{$prefix}}goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `description` text NOT NULL COMMENT '描述',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `sub_stock` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '何时减库存',
  `weight` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '单位:kg',
  `size` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '单位:立方米',
  `sn` varchar(50) NOT NULL DEFAULT '' COMMENT 'Sn',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thumbnail',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Status',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新品',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热销',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods_files`;
CREATE TABLE `{{$prefix}}goods_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Goods Id',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'File Id',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT 'Desc',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Position',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods_prop_values`;
CREATE TABLE `{{$prefix}}goods_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Goods Id',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `prop_value_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Value Id',
  `prop_value_alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'Prop Value Alias',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods_skus`;
CREATE TABLE `{{$prefix}}goods_skus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_id` int(10) unsigned NOT NULL COMMENT 'Goods Id',
  `prop_value_ids` varchar(255) NOT NULL DEFAULT '' COMMENT 'Prop Value Ids',
  `price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Price',
  `quantity` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Quantity',
  `tsces` varchar(50) NOT NULL DEFAULT '' COMMENT '商家编码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}keywords`;
CREATE TABLE `{{$prefix}}keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `keyword` varchar(50) NOT NULL DEFAULT '' COMMENT '关键词',
  `link` varchar(500) NOT NULL DEFAULT '' COMMENT '链接地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}likes`;
CREATE TABLE `{{$prefix}}likes` (
  `post_id` int(10) unsigned NOT NULL COMMENT 'Post Id',
  `user_id` int(10) unsigned NOT NULL COMMENT 'User Id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`post_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}links`;
CREATE TABLE `{{$prefix}}links` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '网址',
  `visiable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '可见',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加者',
  `target` varchar(25) NOT NULL DEFAULT '' COMMENT '打开方式',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last Modified Time',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `logo` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Logo',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}logs`;
CREATE TABLE `{{$prefix}}logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Type',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT 'Code',
  `data` text NOT NULL COMMENT 'Data',
  `create_date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Create Date',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `user_agent` varchar(255) NOT NULL COMMENT 'User Agent',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}messages`;
CREATE TABLE `{{$prefix}}messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `target` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '目标',
  `content` text NOT NULL COMMENT '评论内容',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Root',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Type',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态 ',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '1' COMMENT '判断是否为叶子节点',
  PRIMARY KEY (`id`),
  KEY `root` (`root`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}options`;
CREATE TABLE `{{$prefix}}options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `option_name` varchar(255) NOT NULL COMMENT '参数名',
  `option_value` text NOT NULL COMMENT '参数值',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT 'Description',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last Modified Time',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is System',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}pages`;
CREATE TABLE `{{$prefix}}pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(500) NOT NULL COMMENT '标题',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
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
  `abstract` varchar(500) NOT NULL DEFAULT '' COMMENT '摘要',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}pages_categories`;
CREATE TABLE `{{$prefix}}pages_categories` (
  `page_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Page Id',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  PRIMARY KEY (`page_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_prop_int`;
CREATE TABLE `{{$prefix}}post_prop_int` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `content` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Content',
  PRIMARY KEY (`post_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_prop_text`;
CREATE TABLE `{{$prefix}}post_prop_text` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `content` text NOT NULL COMMENT 'Content',
  PRIMARY KEY (`post_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_prop_varchar`;
CREATE TABLE `{{$prefix}}post_prop_varchar` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT 'Content',
  PRIMARY KEY (`post_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}posts`;
CREATE TABLE `{{$prefix}}posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(500) NOT NULL DEFAULT '' COMMENT '标题',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `content` text NOT NULL COMMENT '正文',
  `content_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '正文类型（普通文本，符文本，markdown）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '后台添加时间',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `publish_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '发布日期',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `last_view_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `user_id` int(9) unsigned NOT NULL DEFAULT '0' COMMENT '作者',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '文章状态',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缩略图',
  `abstract` varchar(500) NOT NULL DEFAULT '' COMMENT '摘要',
  `comments` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `likes` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `seo_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`),
  KEY `cat` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}posts_categories`;
CREATE TABLE `{{$prefix}}posts_categories` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT 'Cat Id',
  PRIMARY KEY (`post_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}posts_files`;
CREATE TABLE `{{$prefix}}posts_files` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_image` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否为图片',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  PRIMARY KEY (`post_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}posts_tags`;
CREATE TABLE `{{$prefix}}posts_tags` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Post Id',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Tag Id',
  PRIMARY KEY (`post_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}profile_int`;
CREATE TABLE `{{$prefix}}profile_int` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `content` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Content',
  PRIMARY KEY (`user_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}profile_text`;
CREATE TABLE `{{$prefix}}profile_text` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `content` text NOT NULL COMMENT 'Content',
  PRIMARY KEY (`user_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}profile_varchar`;
CREATE TABLE `{{$prefix}}profile_varchar` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Prop Id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT 'Content',
  PRIMARY KEY (`user_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}props`;
CREATE TABLE `{{$prefix}}props` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Refer',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Type',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名称',
  `element` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Element',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '必选标记',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is Show',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}regions`;
CREATE TABLE `{{$prefix}}regions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent Id',
  `name` varchar(120) NOT NULL DEFAULT '' COMMENT 'Name',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'Type',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}roles`;
CREATE TABLE `{{$prefix}}roles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is Show',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}roles_actions`;
CREATE TABLE `{{$prefix}}roles_actions` (
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Role Id',
  `action_id` smallint(5) unsigned NOT NULL COMMENT 'Action Id',
  PRIMARY KEY (`role_id`,`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}roles_cats`;
CREATE TABLE `{{$prefix}}roles_cats` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT 'Role Id',
  `cat_id` mediumint(8) unsigned NOT NULL COMMENT 'Cat Id',
  PRIMARY KEY (`role_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}spider_logs`;
CREATE TABLE `{{$prefix}}spider_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `spider` varchar(50) NOT NULL DEFAULT '' COMMENT 'Spider',
  `user_agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User Agent',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'Ip Int',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Url',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Create Time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}tags`;
CREATE TABLE `{{$prefix}}tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标签',
  `count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Count',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT 'Sort',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Title',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Keywords',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Seo Description',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}user_settings`;
CREATE TABLE `{{$prefix}}user_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User Id',
  `setting_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'Setting Key',
  `setting_value` text NOT NULL COMMENT 'Setting Value',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}users`;
CREATE TABLE `{{$prefix}}users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '登录名',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `cellphone` varchar(30) NOT NULL DEFAULT '' COMMENT '手机号码',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(5) NOT NULL DEFAULT '' COMMENT '五位随机数',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` int(11) NOT NULL DEFAULT '0' COMMENT '注册ip',
  `login_times` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `last_login_ip` int(11) NOT NULL DEFAULT '0' COMMENT '最后登陆者ip',
  `last_time_online` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后在线时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户审核状态',
  `block` tinyint(1) NOT NULL DEFAULT '0' COMMENT '屏蔽用户',
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '角色',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父节点',
  `active_key` char(32) NOT NULL DEFAULT '' COMMENT '邮件验证码',
  `active_expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邮件过期时间',
  `sms_key` char(6) NOT NULL DEFAULT '' COMMENT 'Sms Key',
  `sms_expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Sms Expire',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Deleted',
  `trackid` varchar(50) NOT NULL DEFAULT '' COMMENT 'Trackid',
  `refer` varchar(255) NOT NULL DEFAULT '' COMMENT '来源URL',
  `se` varchar(30) NOT NULL DEFAULT '' COMMENT '搜索引擎',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '搜索关键词',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}widgets`;
CREATE TABLE `{{$prefix}}widgets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
  `options` text NOT NULL COMMENT '实例参数',
  `widget_name` varchar(255) NOT NULL DEFAULT '' COMMENT '小工具名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '小工具描述',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `widgetarea` varchar(50) NOT NULL DEFAULT '' COMMENT '小工具域',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;