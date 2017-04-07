
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
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
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
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}};