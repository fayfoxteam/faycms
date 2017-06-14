/*
Navicat MySQL Data Transfer

Source Server         : 官网线上
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : gw_co_ltd

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2017-06-12 17:47:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for gg_admin
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_admin`;
CREATE TABLE `faycms_gg_admin` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '登陆名称',
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `passwd` char(37) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `encrypt` char(8) NOT NULL DEFAULT '' COMMENT '加密密码',
  `real_name` varchar(32) NOT NULL DEFAULT '' COMMENT '真实名称',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `login_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登录时间',
  `login_ip` int(11) NOT NULL DEFAULT '0' COMMENT '登录IP',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账号状态:0未激活,1开启,2关闭,3异常',
  `is_super` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否超级管理员',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台管理员账号表';

-- ----------------------------
-- Table structure for gg_admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_admin_permissions`;
CREATE TABLE `faycms_gg_admin_permissions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `node_bid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `node_sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  `controller` varchar(32) NOT NULL DEFAULT '',
  `action` varchar(32) NOT NULL DEFAULT '',
  `redirect_uri` varchar(255) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(32) NOT NULL DEFAULT '',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为不显示，1为显示在菜单中',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_admin_role_per
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_admin_role_per`;
CREATE TABLE `faycms_gg_admin_role_per` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '节点id',
  `per_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台节点 与 权限表关联（多对多）';

-- ----------------------------
-- Table structure for gg_admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_admin_roles`;
CREATE TABLE `faycms_gg_admin_roles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1000',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台管理员角色表';

-- ----------------------------
-- Table structure for gg_article
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_article`;
CREATE TABLE `faycms_gg_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '新闻标题',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `source_url` varchar(255) NOT NULL DEFAULT '' COMMENT '文章原网址',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `abstract` varchar(1000) NOT NULL DEFAULT '' COMMENT '文章摘要',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `sort` int(10) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `is_recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐 0否  1是',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `import_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导入原文章id',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章表';

-- ----------------------------
-- Table structure for gg_article_cat
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_article_cat`;
CREATE TABLE `faycms_gg_article_cat` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父节点',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1000' COMMENT '排序',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章分类';

-- ----------------------------
-- Table structure for gg_article_info
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_article_info`;
CREATE TABLE `faycms_gg_article_info` (
  `article_id` int(10) unsigned NOT NULL,
  `description` text COMMENT '商品详细',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章详情表';

-- ----------------------------
-- Table structure for gg_article_library
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_article_library`;
CREATE TABLE `faycms_gg_article_library` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text COMMENT '内容',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `source_url` varchar(255) NOT NULL DEFAULT '' COMMENT '文章原网址',
  `author` varchar(50) NOT NULL DEFAULT '' COMMENT '作者',
  `abstract` varchar(1000) NOT NULL DEFAULT '' COMMENT '文章摘要',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `sort` int(10) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `is_recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐 0否  1是',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章库表';

-- ----------------------------
-- Table structure for gg_article_to_tag
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_article_to_tag`;
CREATE TABLE `faycms_gg_article_to_tag` (
  `article_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章标签关系表';

-- ----------------------------
-- Table structure for gg_attachment
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_attachment`;
CREATE TABLE `faycms_gg_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '原文件名',
  `filepath` varchar(200) NOT NULL DEFAULT '',
  `filetype` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `filesize` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `haslitpic` tinyint(1) NOT NULL DEFAULT '1',
  `uploadtime` int(10) unsigned NOT NULL DEFAULT '0',
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件表';

-- ----------------------------
-- Table structure for gg_attachment_cat
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_attachment_cat`;
CREATE TABLE `faycms_gg_attachment_cat` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `sorting` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='图片分类';

-- ----------------------------
-- Table structure for gg_coltd_partners
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_coltd_partners`;
CREATE TABLE `faycms_gg_coltd_partners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `realname` varchar(32) NOT NULL DEFAULT '',
  `mobile` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_coltd_sets
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_coltd_sets`;
CREATE TABLE `faycms_gg_coltd_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_design
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_design`;
CREATE TABLE `faycms_gg_design` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `page_id` int(8) DEFAULT '0' COMMENT '关联页面的id',
  `website_id` int(8) DEFAULT '0' COMMENT '所属网站id',
  `type` varchar(50) DEFAULT '' COMMENT '内容类型',
  `sorting` int(3) DEFAULT '50' COMMENT '排序',
  `name` varchar(50) DEFAULT '' COMMENT '模块名称',
  `model` varchar(50) DEFAULT '' COMMENT 'model标识',
  `file` varchar(250) DEFAULT NULL COMMENT '对应模块地址',
  `data` text COMMENT 'json数据',
  `images` text,
  `is_enable` enum('0','1') DEFAULT '0' COMMENT '是否启用  1启用   0关闭',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `index_name` (`id`,`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='装修之后的模块表';

-- ----------------------------
-- Table structure for gg_design_data
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_design_data`;
CREATE TABLE `faycms_gg_design_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `page_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属页面的id',
  `design_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联的模块id',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `data` text COMMENT 'json数据',
  `data_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数据ID',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用:1启用,0关闭',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `index_name` (`id`,`design_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='模块下的列表';

-- ----------------------------
-- Table structure for gg_design_info
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_design_info`;
CREATE TABLE `faycms_gg_design_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `page_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属页面的id',
  `design_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联的模块id',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用:1启用,0关闭',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `index_name` (`id`,`design_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='模块下的列表';

-- ----------------------------
-- Table structure for gg_design_list
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_design_list`;
CREATE TABLE `faycms_gg_design_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `page_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联页面的id',
  `design_id` int(8) DEFAULT '0' COMMENT '关联的模块id',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '内容类型',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT 'model标识',
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT '对应模块地址',
  `data` text COMMENT 'json数据',
  `images` text,
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用:1启用,0关闭',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx-design_id` (`design_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='模块下的列表';

-- ----------------------------
-- Table structure for gg_domain
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_domain`;
CREATE TABLE `faycms_gg_domain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：系统内置的域名',
  `deleted_at` datetime NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_edit_module
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_edit_module`;
CREATE TABLE `faycms_gg_edit_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `page_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '页面ID',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `html` text COMMENT 'json数据',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用:1启用,0关闭',
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1公共模块,0不是',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  `module_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='高级编辑器存的数据表';

-- ----------------------------
-- Table structure for gg_edit_module_cat
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_edit_module_cat`;
CREATE TABLE `faycms_gg_edit_module_cat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用:1启用,0关闭',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='高级编辑器存的数据表';

-- ----------------------------
-- Table structure for gg_employee
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_employee`;
CREATE TABLE `faycms_gg_employee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '姓名',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `intro` text COMMENT '员工简介',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0离职,1在职',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工表';

-- ----------------------------
-- Table structure for gg_help_cats
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_help_cats`;
CREATE TABLE `faycms_gg_help_cats` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_helps
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_helps`;
CREATE TABLE `faycms_gg_helps` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `position` smallint(5) unsigned NOT NULL DEFAULT '0',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_manage
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_manage`;
CREATE TABLE `faycms_gg_manage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '登陆名称',
  `header` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `passwd` char(37) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `encrypt` char(8) NOT NULL DEFAULT '' COMMENT '加密密码',
  `real_name` varchar(32) NOT NULL DEFAULT '' COMMENT '真实名称',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登录时间',
  `login_ip` int(11) NOT NULL DEFAULT '0' COMMENT '登录IP',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '账号状态:0未激活,1开启,2关闭,3异常',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台管理员账号表';

-- ----------------------------
-- Table structure for gg_merchant
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_merchant`;
CREATE TABLE `faycms_gg_merchant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '商户名称',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '商户电话',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `passwd` char(37) NOT NULL DEFAULT '',
  `encrypt` char(8) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账号状态:0关闭,1开启,2过期',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `is_insider` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否内部人员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商户表';

-- ----------------------------
-- Table structure for gg_merchant_oauth
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_merchant_oauth`;
CREATE TABLE `faycms_gg_merchant_oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三方类型',
  `key` varchar(32) NOT NULL DEFAULT '',
  `refresh_token` varchar(32) NOT NULL DEFAULT '',
  `logined_ip` int(11) NOT NULL DEFAULT '0',
  `logined_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登录时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_message
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_message`;
CREATE TABLE `faycms_gg_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `title` varchar(32) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为已读',
  `readed_ip` int(11) NOT NULL DEFAULT '0',
  `readed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx-website_id` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='网站的短消息表';

-- ----------------------------
-- Table structure for gg_module
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_module`;
CREATE TABLE `faycms_gg_module` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `cat_bid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `cat_sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '模块分类',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `intro` text COMMENT '简介',
  `html` text,
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `device_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '页面类型:1mobile,2pc',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='模块表';

-- ----------------------------
-- Table structure for gg_module_cat
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_module_cat`;
CREATE TABLE `faycms_gg_module_cat` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '10000' COMMENT '排序 从小到大',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户编辑的页面表';

-- ----------------------------
-- Table structure for gg_module_img
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_module_img`;
CREATE TABLE `faycms_gg_module_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `module_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属网站id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `src` varchar(100) NOT NULL DEFAULT '' COMMENT '模块类型',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx-module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户编辑的页面表';

-- ----------------------------
-- Table structure for gg_page
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_page`;
CREATE TABLE `faycms_gg_page` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `device_type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '设备标识:1mobile,2pc',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:page单页,article,info,article_detail',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '页面名称',
  `category` varchar(50) NOT NULL DEFAULT '' COMMENT '页面类型',
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT 'URL地址',
  `picture` varchar(255) NOT NULL DEFAULT '' COMMENT '页面展示图',
  `describe` varchar(250) NOT NULL DEFAULT '' COMMENT '页面描述',
  `seo_title` varchar(255) NOT NULL DEFAULT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO页面关键词',
  `seo_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO页面说明',
  `is_page` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单一页面',
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否公开:0不公开,1公开',
  `is_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用:1启用,0关闭',
  `is_home` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否首页:1代表是,0代表不是',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `global` text COMMENT '页面的全局配置',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户编辑的页面表';

-- ----------------------------
-- Table structure for gg_product
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_product`;
CREATE TABLE `faycms_gg_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标题',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `abstract` text COMMENT '描述',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `is_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评论',
  `is_recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐  1是   0否',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '10000',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

-- ----------------------------
-- Table structure for gg_product_cat
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_product_cat`;
CREATE TABLE `faycms_gg_product_cat` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父节点',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '10000' COMMENT '排序',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品分类表';

-- ----------------------------
-- Table structure for gg_product_info
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_product_info`;
CREATE TABLE `faycms_gg_product_info` (
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text COMMENT '商品详细',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品详情表';

-- ----------------------------
-- Table structure for gg_tag
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_tag`;
CREATE TABLE `faycms_gg_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(10) NOT NULL DEFAULT '' COMMENT '标签名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx-tag_name` (`tag_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='标签表';

-- ----------------------------
-- Table structure for gg_template_cats
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_template_cats`;
CREATE TABLE `faycms_gg_template_cats` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_templates
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_templates`;
CREATE TABLE `faycms_gg_templates` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_bid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `cat_sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(32) NOT NULL DEFAULT '',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `updated_ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_ip` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` datetime DEFAULT NULL,
  `mobile_img` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(4) DEFAULT '0' COMMENT '审核状态 0 待审核 1 审核通过 2 审核不通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gg_web_message
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_web_message`;
CREATE TABLE `faycms_gg_web_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `name` varchar(50) NOT NULL DEFAULT '',
  `data` text COMMENT 'json数据',
  `ip` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx-website_id` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='网站留言表';

-- ----------------------------
-- Table structure for gg_website
-- ----------------------------
DROP TABLE IF EXISTS `faycms_gg_website`;
CREATE TABLE `faycms_gg_website` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_sid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '行业分类',
  `cat_bid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '模板分类',
  `merchant_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属管理员站点（只关联主账号）',
  `domain` varchar(255) NOT NULL DEFAULT '' COMMENT '自动生成的二级域名前缀',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '站点名称',
  `thumbnail` varchar(255) NOT NULL DEFAULT '' COMMENT '网站缩略图',
  `scope` varchar(50) NOT NULL DEFAULT '' COMMENT '业务范围',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '联系电话',
  `company_name` varchar(255) NOT NULL DEFAULT '',
  `company_pinyin` varchar(32) NOT NULL DEFAULT '',
  `company_suffix` varchar(32) NOT NULL DEFAULT '',
  `page_num` int(8) NOT NULL DEFAULT '0' COMMENT '站点下的页面数量',
  `domain_num` int(8) NOT NULL DEFAULT '0' COMMENT '站点绑定的域名数量',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '站点到期时间   -1永久使用',
  `msg` text COMMENT '站点说明  关闭时候的提示文字',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '站点状态:0关闭,1未发布,2已发布',
  `is_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启:0代表欠费关闭的站点',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序  会员账号等级下降 关闭排在后面的站点',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `is_guide` tinyint(1) NOT NULL DEFAULT '0',
  `company_short_name` varchar(50) NOT NULL DEFAULT '',
  `company_intro` text,
  `company_logo` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `weibo` varchar(50) NOT NULL DEFAULT '',
  `qrcode` varchar(50) NOT NULL DEFAULT '',
  `weibo_url` varchar(50) NOT NULL DEFAULT '',
  `weixin` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='web站点表';
