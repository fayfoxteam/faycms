DROP TABLE IF EXISTS `{{$prefix}}guangong_arms`;
CREATE TABLE `{{$prefix}}guangong_arms` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `picture` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片',
  `sort` tinyint(100) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `description_picture` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '描述图片',
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
  `text_picture` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '描述',
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

DROP TABLE IF EXISTS `{{$prefix}}guangong_messages`;
CREATE TABLE `{{$prefix}}guangong_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text COMMENT '内容',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '留言时间',
  `reply` text COMMENT '管理员回复',
  `reply_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  `ip_int` int(11) NOT NULL DEFAULT '0' COMMENT 'IP',
  `delete_time` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='留言表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_ranks`;
CREATE TABLE `{{$prefix}}guangong_ranks` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  `captain` varchar(20) NOT NULL,
  `soldiers` mediumint(8) unsigned NOT NULL COMMENT '统领士兵数',
  `months` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '获得军衔规则：月',
  `times` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '获得军衔规则：累计次数',
  `continuous` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '获得军衔规则：连续签到天数',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '军衔高低（值越高表示军衔越高）',
  `description_picture` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '描述图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='军衔表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_read_logs`;
CREATE TABLE `{{$prefix}}guangong_read_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文献ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读时间',
  `create_date` date NOT NULL COMMENT '阅读日期',
  PRIMARY KEY (`id`),
  KEY `user_id-post_id` (`user_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='文献学习记录';

DROP TABLE IF EXISTS `{{$prefix}}guangong_speaks`;
CREATE TABLE `{{$prefix}}guangong_speaks` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `photo_server_id` varchar(100) NOT NULL DEFAULT '',
  `photo` int(10) unsigned NOT NULL DEFAULT '0',
  `words` varchar(100) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='代言表';

DROP TABLE IF EXISTS `{{$prefix}}guangong_tasks`;
CREATE TABLE `{{$prefix}}guangong_tasks` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='任务';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_extra`;
CREATE TABLE `{{$prefix}}guangong_user_extra` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `state` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '省',
  `city` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '市',
  `district` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '区/县',
  `arm_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '兵种',
  `defence_area_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '防区ID',
  `hour_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '时辰ID',
  `attendances` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '总出勤次数',
  `rank_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '军衔ID',
  `military` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '缴纳军费（单位：分）',
  `sign_up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名时间',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户扩展信息';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_group_users`;
CREATE TABLE `{{$prefix}}guangong_user_group_users` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '结盟ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成员ID',
  `accept` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否接受邀请',
  `words` varchar(255) NOT NULL DEFAULT '' COMMENT '我想对兄弟说',
  `public_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公开时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='结盟成员';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_groups`;
CREATE TABLE `{{$prefix}}guangong_user_groups` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '称谓',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发起者',
  `vow` varchar(255) NOT NULL DEFAULT '' COMMENT '誓言',
  `count` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '结义人数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发起时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='结盟';

DROP TABLE IF EXISTS `{{$prefix}}guangong_user_tasks`;
CREATE TABLE `{{$prefix}}guangong_user_tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `task_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '任务ID',
  `create_date` date NOT NULL COMMENT '日期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `day-task` (`user_id`,`create_date`,`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='用户任务记录表';


DROP TABLE IF EXISTS `{{$prefix}}guangong_vows`;
CREATE TABLE `{{$prefix}}guangong_vows` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '誓词',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序值',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={{$charset}} COMMENT='预定义誓词';

-- 初始化时辰表数据
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('1', '子时', '23', '1', '夜半，又名子夜、中夜：十二时辰的第一个时辰。', '老鼠在这时间最活跃。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('2', '丑时', '1', '3', '鸡鸣，又名荒鸡：十二时辰的第二个时辰。', '牛在这时候咀嚼白天没消化的食物。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('3', '寅时', '3', '5', '平旦，又称黎明、早晨、日旦等：时是夜与日的交替之际。', '老虎在此时最凶猛。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('4', '卯时', '5', '7', '日出，又名破晓、旭日等：指太阳刚刚露脸，冉冉初升的那段时间。', '月亮又称玉兔，这段时间在天上。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('5', '辰时', '7', '9', '食时，又名早食等：古人“朝食”之时也就是吃早饭时间。', '相传这是“群龙行雨”的时候。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('6', '巳时', '9', '11', '隅中，又名日禺等：临近中午的时候称为隅中。', '蛇在这时候隐蔽在草丛中。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('7', '午时', '11', '13', '日中，又名日正、中午等：这时候太阳最猛烈，阳气达到极限。', '这时阴气将产生，而马是阴类动物。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('8', '未时', '13', '15', '日昳，又名日跌、日央等：太阳偏西为日跌。', '羊在这段时间吃草。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('9', '申时', '15', '17', '哺时，又名日铺、夕食等。', '猴子喜欢在这时候啼叫。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('10', '酉时', '17', '19', '日入，又名日落、日沉、傍晚：意为太阳落山的时候。', '鸡於傍晚开始归巢。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('11', '戌时', '19', '21', '黄昏，又名日暮、日晚等：此时太阳已落山将黑未黑，天地昏黄万物朦胧，故称黄昏。', '狗开始守门口。');
INSERT INTO `{{$prefix}}guangong_hours` (`id`, `name`, `start_hour`, `end_hour`, `description`, `zodiac`) VALUES ('12', '亥时', '21', '23', '人定，又名定昏等：此时夜色已深人们安歇睡眠了，人定也就是人静。', '夜深时分猪正在熟睡。');

-- 初始化任务表数据
INSERT INTO `{{$prefix}}guangong_tasks` (`id`, `name`) VALUES ('1', '天天报勤务');
INSERT INTO `{{$prefix}}guangong_tasks` (`id`, `name`) VALUES ('2', '分享朋友圈');
INSERT INTO `{{$prefix}}guangong_tasks` (`id`, `name`) VALUES ('3', '点阅资料库');
INSERT INTO `{{$prefix}}guangong_tasks` (`id`, `name`) VALUES ('4', '传播正能量');

-- 系统文件
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('1', 'bubing', '.png', '47098', 'image/png', './apps/guangong/images/arm/', '步兵', '1', '277', '296', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('2', 'chebing', '.png', '110453', 'image/png', './apps/guangong/images/arm/', '车兵', '1', '359', '279', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('3', 'nubing', '.png', '47197', 'image/png', './apps/guangong/images/arm/', '弩兵', '1', '354', '261', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('4', 'qibing', '.png', '118515', 'image/png', './apps/guangong/images/arm/', '骑兵', '1', '319', '297', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('5', 'shuijun', '.png', '272002', 'image/png', './apps/guangong/images/arm/', '水军', '1', '381', '373', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('6', 'bubing-desc', '.png', '70018', 'image/png', './apps/guangong/images/arm/', '步兵描述', '1', '226', '304', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('7', 'chebing-desc', '.png', '74441', 'image/png', './apps/guangong/images/arm/', '车兵描述', '1', '226', '330', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('8', 'nubing-desc', '.png', '69395', 'image/png', './apps/guangong/images/arm/', '弩兵描述', '1', '226', '305', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('9', 'qibing-desc', '.png', '65338', 'image/png', './apps/guangong/images/arm/', '骑兵描述', '1', '226', '306', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('10', 'shuijun-desc', '.png', '98869', 'image/png', './apps/guangong/images/arm/', '水军描述', '1', '227', '353', '{{$time}}', '10000');

INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('11', '3wkjs', '.png', '45660', 'image/png', './apps/guangong/images/arm/', 'wuzhang.png', '1', '319', '260', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('12', 'wAaen', '.png', '46249', 'image/png', './apps/guangong/images/arm/', 'shenzhang.png', '1', '319', '260', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('13', 'ojWel', '.png', '46475', 'image/png', './apps/guangong/images/arm/', 'duizhang.png', '1', '319', '302', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('14', 'pHYDh', '.png', '46412', 'image/png', './apps/guangong/images/arm/', 'tunzhang.png', '1', '320', '261', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('15', '2KRkO', '.png', '46842', 'image/png', './apps/guangong/images/arm/', 'quzhang.png', '1', '319', '260', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('16', 'N2tix', '.png', '47931', 'image/png', './apps/guangong/images/arm/', 'junsima.png', '1', '319', '260', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('17', '8LuNP', '.png', '48019', 'image/png', './apps/guangong/images/arm/', 'xiaowei.png', '1', '319', '260', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('18', '8d8nA', '.png', '63907', 'image/png', './apps/guangong/images/arm/', 'jiangjun.png', '1', '299', '332', '{{$time}}', '10000');

INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('20', 'linglinjun', '.png', '129692', 'image/png', './apps/guangong/images/arm/', 'linglinjun.png', '1', '558', '671', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('21', 'linglinjun-text', '.png', '108192', 'image/png', './apps/guangong/images/arm/', 'linglinjun-text.png', '1', '341', '464', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('22', 'nanjun', '.png', '129107', 'image/png', './apps/guangong/images/arm/', 'nanjun.png', '1', '558', '671', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('23', 'nanjun-text', '.png', '131233', 'image/png', './apps/guangong/images/arm/', 'nanjun-text.png', '1', '384', '490', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('24', 'wulinjun', '.png', '130539', 'image/png', './apps/guangong/images/arm/', 'wulinjun.png', '1', '558', '671', '{{$time}}', '10000');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`) VALUES ('25', 'wulinjun-text', '.png', '89835', 'image/png', './apps/guangong/images/arm/', 'wulinjun-text.png', '1', '321', '437', '{{$time}}', '10000');

-- 军衔制度
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('1', '五人为伍', '伍长', '5', '0', '0', '7', '1', '11');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('2', '两伍为一什', '什长', '10', '0', '0', '30', '2', '12');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('3', '五什为一队', '队长', '50', '2', '55', '0', '3', '13');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('4', '两队为一屯', '屯长', '100', '3', '80', '0', '4', '14');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('5', '两屯为一曲', '军侯', '200', '6', '160', '0', '5', '15');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('6', '两曲为一部', '军司马', '400', '10', '255', '0', '6', '16');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('7', '五部为一营', '校尉', '2000', '12', '255', '0', '7', '17');
INSERT INTO `{{$prefix}}guangong_ranks` (`id`, `name`, `captain`, `soldiers`, `months`, `times`, `continuous`, `sort`, `description_picture`) VALUES ('8', '五部为一营', '将军', '2000', '12', '255', '0', '8', '18');

-- 防区
INSERT INTO `{{$prefix}}guangong_defence_areas` (`id`, `name`, `picture`, `text_picture`, `enabled`) VALUES ('1', '南郡', '22', '23', '1');
INSERT INTO `{{$prefix}}guangong_defence_areas` (`id`, `name`, `picture`, `text_picture`, `enabled`) VALUES ('2', '武陵郡', '24', '25', '1');
INSERT INTO `{{$prefix}}guangong_defence_areas` (`id`, `name`, `picture`, `text_picture`, `enabled`) VALUES ('3', '零陵郡', '20', '21', '1');

-- 兵种
INSERT INTO `{{$prefix}}guangong_arms` (`id`, `name`, `picture`, `sort`, `enabled`, `description_picture`) VALUES ('1', '车兵', '2', '100', '1', '7');
INSERT INTO `{{$prefix}}guangong_arms` (`id`, `name`, `picture`, `sort`, `enabled`, `description_picture`) VALUES ('2', '水军', '5', '100', '1', '10');
INSERT INTO `{{$prefix}}guangong_arms` (`id`, `name`, `picture`, `sort`, `enabled`, `description_picture`) VALUES ('3', '骑兵', '4', '99', '1', '9');
INSERT INTO `{{$prefix}}guangong_arms` (`id`, `name`, `picture`, `sort`, `enabled`, `description_picture`) VALUES ('4', '弩兵', '3', '100', '1', '8');
INSERT INTO `{{$prefix}}guangong_arms` (`id`, `name`, `picture`, `sort`, `enabled`, `description_picture`) VALUES ('5', '步兵', '1', '100', '1', '6');

