DROP TABLE IF EXISTS `{{$prefix}}actionlogs`;
CREATE TABLE `{{$prefix}}actionlogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `refer` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}actions`;
CREATE TABLE `{{$prefix}}actions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `router` varchar(255) NOT NULL DEFAULT '',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `router` (`router`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}analyst_caches`;
CREATE TABLE `{{$prefix}}analyst_caches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `hour` tinyint(3) NOT NULL DEFAULT '-1',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pv` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uv` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ip` smallint(5) unsigned NOT NULL DEFAULT '0',
  `new_visitors` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bounce_rate` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}analyst_macs`;
CREATE TABLE `{{$prefix}}analyst_macs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `browser` varchar(30) NOT NULL DEFAULT '',
  `browser_version` varchar(30) NOT NULL DEFAULT '',
  `shell` varchar(30) NOT NULL DEFAULT '',
  `shell_version` varchar(30) NOT NULL DEFAULT '',
  `os` varchar(30) NOT NULL DEFAULT '',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `screen_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `screen_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `refer` varchar(255) NOT NULL DEFAULT '',
  `se` varchar(10) NOT NULL DEFAULT '',
  `keywords` varchar(50) NOT NULL DEFAULT '',
  `hash` char(32) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  `hour` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `trackid` varchar(30) NOT NULL DEFAULT '',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `date` (`create_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}analyst_sites`;
CREATE TABLE `{{$prefix}}analyst_sites` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}analyst_visits`;
CREATE TABLE `{{$prefix}}analyst_visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mac` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `refer` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `short_url` char(6) NOT NULL DEFAULT '',
  `trackid` varchar(30) NOT NULL DEFAULT '',
  `user_id` mediumint(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  `hour` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0',
  `views` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `HTTP_CLIENT_IP` varchar(255) NOT NULL DEFAULT '',
  `HTTP_X_FORWARDED_FOR` varchar(255) NOT NULL DEFAULT '',
  `REMOTE_ADDR` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pv` (`mac`,`short_url`,`create_time`),
  KEY `date` (`create_date`,`hour`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}cat_prop_values`;
CREATE TABLE `{{$prefix}}cat_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}cat_props`;
CREATE TABLE `{{$prefix}}cat_props` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `is_sale_prop` tinyint(1) NOT NULL DEFAULT '0',
  `is_input_prop` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}categories`;
CREATE TABLE `{{$prefix}}categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(50) NOT NULL DEFAULT '',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1000',
  `description` varchar(500) NOT NULL DEFAULT '',
  `is_nav` tinyint(1) NOT NULL DEFAULT '1',
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `left_right_value` (`left_value`,`right_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}cities`;
CREATE TABLE `{{$prefix}}cities` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL DEFAULT '',
  `parent` smallint(5) NOT NULL DEFAULT '0',
  `spelling` varchar(50) NOT NULL DEFAULT '',
  `abbr` varchar(30) NOT NULL DEFAULT '',
  `short` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}contacts`;
CREATE TABLE `{{$prefix}}contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_answers`;
CREATE TABLE `{{$prefix}}exam_answers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `is_right_answer` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `question` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_question_answer_text`;
CREATE TABLE `{{$prefix}}exam_exam_question_answer_text` (
  `exam_question_id` int(11) NOT NULL,
  `user_answer` text,
  PRIMARY KEY (`exam_question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_question_answers_int`;
CREATE TABLE `{{$prefix}}exam_exam_question_answers_int` (
  `exam_question_id` int(10) unsigned NOT NULL,
  `user_answer_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`exam_question_id`,`user_answer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exam_questions`;
CREATE TABLE `{{$prefix}}exam_exam_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_exams`;
CREATE TABLE `{{$prefix}}exam_exams` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL,
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `rand` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_paper_questions`;
CREATE TABLE `{{$prefix}}exam_paper_questions` (
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`paper_id`,`question_id`),
  KEY `question` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_papers`;
CREATE TABLE `{{$prefix}}exam_papers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rand` tinyint(1) NOT NULL DEFAULT '100',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `repeatedly` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}exam_questions`;
CREATE TABLE `{{$prefix}}exam_questions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `rand` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}favourites`;
CREATE TABLE `{{$prefix}}favourites` (
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}files`;
CREATE TABLE `{{$prefix}}files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `raw_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `file_ext` varchar(10) NOT NULL DEFAULT '',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0',
  `file_type` varchar(30) NOT NULL DEFAULT '',
  `file_path` varchar(255) NOT NULL DEFAULT '',
  `client_name` varchar(255) NOT NULL DEFAULT '',
  `is_image` tinyint(1) NOT NULL DEFAULT '0',
  `image_width` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `image_height` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `upload_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `downloads` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `qiniu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `raw_name` (`raw_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods`;
CREATE TABLE `{{$prefix}}goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sub_stock` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `weight` decimal(8,2) NOT NULL DEFAULT '0.00',
  `size` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sn` varchar(50) NOT NULL DEFAULT '',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods_files`;
CREATE TABLE `{{$prefix}}goods_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods_prop_values`;
CREATE TABLE `{{$prefix}}goods_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_value_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_value_alias` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}goods_skus`;
CREATE TABLE `{{$prefix}}goods_skus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL,
  `prop_value_ids` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tsces` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}item_prop_values`;
CREATE TABLE `{{$prefix}}item_prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}item_props`;
CREATE TABLE `{{$prefix}}item_props` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `is_input_prop` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `parent_pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `parent_vid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `is_sale_prop` tinyint(1) NOT NULL DEFAULT '0',
  `is_color_prop` tinyint(1) NOT NULL DEFAULT '0',
  `is_enum_prop` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `multi` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}keywords`;
CREATE TABLE `{{$prefix}}keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}likes`;
CREATE TABLE `{{$prefix}}likes` (
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}links`;
CREATE TABLE `{{$prefix}}links` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `visiable` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `target` varchar(25) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `logo` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}logs`;
CREATE TABLE `{{$prefix}}logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}menus`;
CREATE TABLE `{{$prefix}}menus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `sub_title` varchar(255) NOT NULL DEFAULT '',
  `css_class` varchar(50) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `link` varchar(255) NOT NULL DEFAULT '',
  `target` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}messages`;
CREATE TABLE `{{$prefix}}messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `target` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `root` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `root` (`root`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}notifications`;
CREATE TABLE `{{$prefix}}notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active_key` varchar(32) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0',
  `validity_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}options`;
CREATE TABLE `{{$prefix}}options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) NOT NULL,
  `option_value` text NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}page_categories`;
CREATE TABLE `{{$prefix}}page_categories` (
  `page_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}pages`;
CREATE TABLE `{{$prefix}}pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `author` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) NOT NULL DEFAULT '0',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `seo_title` varchar(100) NOT NULL DEFAULT '',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `abstract` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_categories`;
CREATE TABLE `{{$prefix}}post_categories` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_files`;
CREATE TABLE `{{$prefix}}post_files` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `is_image` tinyint(1) NOT NULL DEFAULT '1',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`post_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_prop_int`;
CREATE TABLE `{{$prefix}}post_prop_int` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_prop_text`;
CREATE TABLE `{{$prefix}}post_prop_text` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`post_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}post_prop_varchar`;
CREATE TABLE `{{$prefix}}post_prop_varchar` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`post_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}posts`;
CREATE TABLE `{{$prefix}}posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(500) NOT NULL DEFAULT '',
  `alias` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `content_type` tinyint(4) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `publish_date` date NOT NULL DEFAULT '0000-00-00',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_view_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(9) unsigned NOT NULL DEFAULT '0',
  `is_top` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `thumbnail` int(10) unsigned NOT NULL DEFAULT '0',
  `abstract` varchar(500) NOT NULL DEFAULT '',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `likes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(100) NOT NULL DEFAULT '',
  `seo_keywords` varchar(100) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`),
  KEY `cat` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}posts_tags`;
CREATE TABLE `{{$prefix}}posts_tags` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{{$prefix}}profile_int`;
CREATE TABLE `{{$prefix}}profile_int` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`prop_id`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}profile_text`;
CREATE TABLE `{{$prefix}}profile_text` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`user_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}profile_varchar`;
CREATE TABLE `{{$prefix}}profile_varchar` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`,`prop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}prop_values`;
CREATE TABLE `{{$prefix}}prop_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}props`;
CREATE TABLE `{{$prefix}}props` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `element` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}regions`;
CREATE TABLE `{{$prefix}}regions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(120) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}role_actions`;
CREATE TABLE `{{$prefix}}role_actions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `action_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}roles`;
CREATE TABLE `{{$prefix}}roles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}spider_logs`;
CREATE TABLE `{{$prefix}}spider_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spider` varchar(50) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}tags`;
CREATE TABLE `{{$prefix}}tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}templates`;
CREATE TABLE `{{$prefix}}templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}user_notifications`;
CREATE TABLE `{{$prefix}}user_notifications` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `notification_id` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `ignored` tinyint(1) NOT NULL DEFAULT '0',
  `option` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`,`notification_id`),
  KEY `unread` (`user_id`,`read`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}user_settings`;
CREATE TABLE `{{$prefix}}user_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `setting_key` varchar(255) NOT NULL DEFAULT '',
  `setting_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}users`;
CREATE TABLE `{{$prefix}}users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `cellphone` varchar(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `salt` char(5) NOT NULL DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `avatar` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_ip` int(11) NOT NULL DEFAULT '0',
  `login_times` smallint(5) unsigned NOT NULL DEFAULT '0',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` int(11) NOT NULL DEFAULT '0',
  `last_time_online` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `block` tinyint(1) NOT NULL DEFAULT '0',
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active_key` char(32) NOT NULL DEFAULT '',
  `active_expire` int(10) unsigned NOT NULL DEFAULT '0',
  `sms_key` char(6) NOT NULL DEFAULT '',
  `sms_expire` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `trackid` varchar(50) NOT NULL DEFAULT '',
  `refer` varchar(255) NOT NULL DEFAULT '',
  `se` varchar(30) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}followers`;
CREATE TABLE `{{$prefix}}followers` (
  `user_id` int(10) unsigned NOT NULL,
  `follower` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`follower`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}vouchers`;
CREATE TABLE `{{$prefix}}vouchers` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sn` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `counts` smallint(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{$prefix}}widgets`;
CREATE TABLE `{{$prefix}}widgets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `options` text NOT NULL,
  `widget_name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;