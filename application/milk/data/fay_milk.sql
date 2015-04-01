-- phpMyAdmin SQL Dump
-- version 4.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-03-13 06:32:51
-- 服务器版本： 5.5.41-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fay_milk`
--

-- --------------------------------------------------------

--
-- 表的结构 `fay_actionlogs`
--

CREATE TABLE IF NOT EXISTS `fay_actionlogs` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `refer` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_actionlogs`
--

INSERT INTO `fay_actionlogs` (`id`, `user_id`, `type`, `note`, `create_time`, `refer`, `ip_int`) VALUES
(1, 10000, 17, '添加页面', 1426513388, 1000, 2130706433),
(2, 10000, 17, '编辑页面', 1426513395, 1000, 2130706433),
(3, 10000, 17, '编辑页面', 1426514079, 1000, 2130706433),
(4, 10000, 6, '添加分类', 1426515573, 10000, 2130706433),
(5, 10000, 6, '添加分类', 1426515581, 10001, 2130706433),
(6, 10000, 6, '添加分类', 1426515589, 10002, 2130706433),
(7, 10000, 6, '添加分类', 1426515610, 10003, 2130706433),
(8, 10000, 17, '添加页面', 1426516232, 1001, 2130706433),
(9, 10000, 17, '编辑页面', 1426516246, 1001, 2130706433),
(10, 10000, 17, '添加页面', 1426517190, 1002, 2130706433),
(11, 10000, 17, '编辑页面', 1426517194, 1002, 2130706433),
(12, 10000, 16, '添加文章', 1426517808, 1000, 2130706433),
(13, 10000, 16, '添加文章', 1426517815, 1001, 2130706433),
(14, 10000, 16, '添加文章', 1426517818, 1002, 2130706433),
(15, 10000, 17, '编辑页面', 1426595281, 1001, 2130706433),
(16, 10000, 28, '创建了一个小工具实例', 1426598643, 1, 2130706433),
(17, 10000, 28, '创建了一个小工具实例', 1426598827, 2, 2130706433),
(18, 10000, 28, '删除了一个小工具实例', 1426599165, 2, 2130706433),
(19, 10000, 28, '创建了一个小工具实例', 1426599176, 3, 2130706433),
(20, 10000, 28, '创建了一个小工具实例', 1426599300, 4, 2130706433),
(21, 10000, 28, '删除了一个小工具实例', 1426599402, 4, 2130706433),
(22, 10000, 16, '添加文章', 1426599523, 1003, 2130706433),
(23, 10000, 28, '创建了一个小工具实例', 1426599567, 5, 2130706433),
(24, 10000, 10, '添加友情链接', 1426599677, 1, 2130706433),
(25, 10000, 28, '创建了一个小工具实例', 1426599701, 6, 2130706433),
(26, 10000, 10, '添加友情链接', 1426599961, 2, 2130706433),
(27, 10000, 16, '添加文章', 1426604941, 1004, 2130706433),
(28, 10000, 16, '编辑文章', 1426604963, 1004, 2130706433),
(29, 10000, 16, '添加文章', 1426604970, 1005, 2130706433),
(30, 10000, 16, '添加文章', 1426604993, 1006, 2130706433),
(31, 10000, 16, '添加文章', 1426605004, 1007, 2130706433),
(32, 10000, 16, '添加文章', 1426605010, 1008, 2130706433),
(33, 10000, 16, '添加文章', 1426605016, 1009, 2130706433),
(34, 10000, 16, '编辑文章', 1426605025, 1009, 2130706433),
(35, 10000, 16, '添加文章', 1426605030, 1010, 2130706433),
(36, 10000, 16, '添加文章', 1426605037, 1011, 2130706433),
(37, 10000, 6, '添加分类', 1426775113, 10004, 2130706433),
(38, 10000, 17, '将页面永久删除', 1426778514, 1002, 2130706433),
(39, 10000, 17, '将页面永久删除', 1426778516, 1001, 2130706433),
(40, 10000, 17, '将页面永久删除', 1426778519, 1000, 2130706433),
(41, 10000, 17, '添加页面', 1426778531, 1003, 2130706433),
(42, 10000, 6, '移除分类及其所有子分类', 1426778922, 10000, 2130706433),
(43, 10000, 6, '移除分类及其所有子分类', 1426778924, 10001, 2130706433),
(44, 10000, 6, '移除分类及其所有子分类', 1426778925, 10002, 2130706433),
(45, 10000, 6, '移除分类及其所有子分类', 1426778926, 10003, 2130706433),
(46, 10000, 6, '添加分类', 1426778936, 10005, 2130706433),
(47, 10000, 16, '添加文章', 1426778958, 1012, 2130706433),
(48, 10000, 28, '创建了一个小工具实例', 1426779064, 7, 2130706433),
(49, 10000, 16, '添加文章', 1426779312, 1013, 2130706433),
(50, 10000, 6, '添加分类', 1426864516, 10006, 2130706433),
(51, 10000, 6, '添加分类', 1426864520, 10007, 2130706433),
(52, 10000, 6, '添加分类', 1426864524, 10008, 2130706433),
(53, 10000, 28, '删除了一个小工具实例', 1426864565, 6, 2130706433),
(54, 10000, 28, '删除了一个小工具实例', 1426864567, 5, 2130706433),
(55, 10000, 28, '删除了一个小工具实例', 1426864568, 3, 2130706433),
(56, 10000, 28, '删除了一个小工具实例', 1426864569, 1, 2130706433),
(57, 10000, 28, '创建了一个小工具实例', 1426864580, 8, 2130706433),
(58, 10000, 16, '添加文章', 1426864815, 1014, 2130706433),
(59, 10000, 16, '添加文章', 1427004910, 1015, 2130706433),
(60, 10000, 16, '添加文章', 1427004922, 1016, 2130706433),
(61, 10000, 16, '添加文章', 1427004934, 1017, 2130706433),
(62, 10000, 16, '添加文章', 1427004944, 1018, 2130706433),
(63, 10000, 16, '添加文章', 1427004957, 1019, 2130706433),
(64, 10000, 16, '添加文章', 1427004969, 1020, 2130706433),
(65, 10000, 16, '添加文章', 1427004985, 1021, 2130706433),
(66, 10000, 16, '添加文章', 1427004996, 1022, 2130706433),
(67, 10000, 16, '添加文章', 1427006840, 1023, 2130706433),
(68, 10000, 28, '创建了一个小工具实例', 1427027999, 9, 2130706433),
(69, 10000, 28, '创建了一个小工具实例', 1427029019, 10, 2130706433),
(70, 10000, 16, '编辑文章', 1427029564, 1023, 2130706433),
(71, 10000, 16, '添加文章', 1427029621, 1024, 2130706433),
(72, 10000, 28, '创建了一个小工具实例', 1427029710, 11, 2130706433),
(73, 10000, 6, '添加分类', 1427033301, 10009, 2130706433),
(74, 10000, 6, '修改分类', 1427033318, 10008, 2130706433),
(75, 10000, 6, '修改分类', 1427033324, 10007, 2130706433),
(76, 10000, 6, '修改分类', 1427033329, 10006, 2130706433),
(77, 10000, 6, '添加分类', 1427033632, 10010, 2130706433),
(78, 10000, 6, '添加分类', 1427033703, 10011, 2130706433),
(79, 10000, 6, '移除分类及其所有子分类', 1427033737, 10010, 2130706433),
(80, 10000, 6, '移除分类及其所有子分类', 1427033739, 10011, 2130706433),
(81, 10000, 17, '添加页面', 1427034115, 1004, 2130706433),
(82, 10000, 17, '添加页面', 1427034152, 1005, 2130706433),
(83, 10000, 17, '添加页面', 1427034198, 1006, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `fay_actions`
--

CREATE TABLE IF NOT EXISTS `fay_actions` (
  `id` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `router` varchar(255) NOT NULL DEFAULT '',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_actions`
--

INSERT INTO `fay_actions` (`id`, `title`, `router`, `cat_id`, `is_public`, `parent`) VALUES
(1, '列表', 'admin/link/index', 101, 0, 0),
(2, '添加', 'admin/link/create', 101, 0, 0),
(3, '编辑', 'admin/link/edit', 101, 0, 1),
(4, '永久删除', 'admin/link/remove', 101, 0, 1),
(5, '分类列表', 'admin/link/cat', 101, 0, 0),
(6, '添加分类', 'admin/link/cat-create', 101, 0, 5),
(7, '编辑分类', 'admin/link/cat-edit', 101, 0, 5),
(8, '删除分类', 'admin/link/cat-remove', 101, 0, 5),
(9, '添加', 'admin/post/create', 103, 0, 18),
(10, '列表', 'admin/post/index', 103, 0, 0),
(11, '排序', 'admin/post/sort', 103, 0, 10),
(12, '编辑', 'admin/post/edit', 103, 0, 10),
(13, '标签', 'admin/tag/index', 103, 0, 0),
(14, '关键词', 'admin/keyword/index', 103, 0, 0),
(15, '删除', 'admin/post/delete', 103, 0, 10),
(16, '还原', 'admin/post/undelete', 103, 0, 10),
(17, '永久删除', 'admin/post/remove', 103, 0, 10),
(18, '分类列表', 'admin/post/cat', 103, 0, 0),
(19, '添加分类', 'admin/post/cat-create', 103, 0, 18),
(20, '分类属性', 'admin/post-prop/index', 103, 0, 18),
(21, '编辑分类', 'admin/post/cat-edit', 103, 0, 18),
(22, '删除分类', 'admin/post/cat-remove', 103, 0, 18),
(23, '分类排序', 'admin/post/cat-sort', 103, 0, 18),
(24, '列表', 'admin/contact/index', 105, 0, 0),
(25, '标记为已读', 'admin/contact/set-read', 105, 0, 24),
(26, '标记为未读', 'admin/contact/set-unread', 105, 0, 24),
(27, '永久删除', 'admin/contact/remove', 105, 0, 24),
(28, '列表', 'admin/operator/index', 106, 0, 0),
(29, '添加', 'admin/operator/create', 106, 0, 0),
(30, '编辑', 'admin/operator/edit', 106, 0, 28),
(31, '查看', 'admin/operator/item', 106, 0, 28),
(32, '设置状态', 'admin/operator/set-status', 106, 0, 31),
(33, '列表', 'admin/page/index', 107, 0, 0),
(34, '添加', 'admin/page/create', 107, 0, 0),
(35, '修改', 'admin/page/edit', 107, 0, 33),
(36, '删除', 'admin/page/delete', 107, 0, 33),
(37, '永久删除', 'admin/page/remove', 107, 0, 33),
(38, '分类列表', 'admin/page/cat', 107, 0, 0),
(39, '添加分类', 'admin/page/cat-create', 107, 0, 38),
(40, '修改分类', 'admin/page/cat-edit', 107, 0, 38),
(41, '删除分类', 'admin/page/cat-remove', 107, 0, 38),
(42, '排序', 'admin/page/sort', 107, 0, 33),
(43, '列表', 'admin/user/index', 109, 0, 0),
(44, '添加', 'admin/user/create', 109, 0, 0),
(45, '查看', 'admin/user/item', 109, 0, 43),
(46, '编辑', 'admin/user/edit', 109, 0, 43),
(47, '设置状态', 'admin/user/set-status', 109, 0, 45),
(48, '列表', 'admin/comment/index', 110, 0, 0),
(49, '批准', 'admin/comment/approve', 110, 0, 48),
(50, '驳回', 'admin/comment/unapprove', 110, 0, 48),
(51, '删除', 'admin/comment/delete', 110, 0, 48),
(52, '永久删除', 'admin/comment/remove', 110, 0, 48),
(53, '站点参数', 'admin/site/options', 111, 0, 0),
(54, '小工具', 'admin/widget/instances', 111, 0, 0),
(55, '移除小工具', 'admin/widget/remove-instance', 111, 0, 54),
(56, '编辑小工具', 'admin/widget/edit', 111, 0, 54),
(57, '列表', 'admin/role/index', 112, 0, 0),
(58, '添加', 'admin/role/create', 112, 0, 0),
(59, '删除', 'admin/role/delete', 112, 0, 57),
(60, '属性', 'admin/role-prop/index', 112, 0, 60),
(61, '蜘蛛爬行日志', 'admin/analyst/spiderlog', 113, 0, 0),
(62, '访客统计', 'admin/analyst/visitor', 113, 0, 0),
(63, '访问日志', 'admin/analyst/views', 113, 0, 0),
(64, '页面PV量', 'admin/analyst/pv', 113, 0, 0),
(65, '站点管理', 'admin/analyst-site/index', 113, 0, 0),
(66, '列表', 'admin/menu/index', 114, 0, 0),
(67, '添加', 'admin/menu/create', 114, 0, 66),
(68, '编辑', 'admin/menu/edit', 114, 0, 66),
(69, '删除', 'admin/menu/remove', 114, 0, 66),
(70, '列表', 'admin/chat/index', 115, 0, 0),
(71, '批准', 'admin/chat/approve', 115, 0, 70),
(72, '驳回', 'admin/chat/unapprove', 115, 0, 70),
(73, '删除', 'admin/chat/delete', 115, 0, 70),
(74, '回复', 'admin/chat/reply', 115, 0, 70),
(75, '删除会话', 'admin/chat/remove-all', 115, 0, 70),
(76, '试题库', 'admin/exam-question/index', 116, 0, 0),
(77, '添加试题', 'admin/exam-question/create', 116, 0, 0),
(78, '编辑试题', 'admin/exam-question/edit', 116, 0, 76),
(79, '删除试题', 'admin/exam-question/delete', 116, 0, 76),
(80, '分类列表', 'admin/exam-question/cat', 116, 0, 0),
(81, '添加分类', 'admin/exam-question/cat-create', 116, 0, 80),
(82, '删除分类', 'admin/exam-question/cat-remove', 116, 0, 80),
(83, '编辑分类', 'admin/exam-question/cat-edit', 116, 0, 80),
(84, '列表', 'admin/exam-paper/index', 117, 0, 0),
(85, '组卷', 'admin/exam-paper/create', 117, 0, 0),
(86, '编辑', 'admin/exam-paper/edit', 117, 0, 84),
(87, '删除', 'admin/exam-paper/delete', 117, 0, 84),
(88, '阅卷（列表）', 'admin/exam-exam/index', 117, 0, 0),
(89, '阅卷（详细）', 'admin/exam-exam/item', 117, 0, 88),
(90, '阅卷（评分）', 'admin/exam-exam/set-score', 117, 0, 89),
(91, '阅卷（永久删除）', 'admin/exam-exam/remove', 117, 0, 88),
(92, '分类列表', 'admin/exam-paper/cat', 117, 0, 0),
(93, '添加分类', 'admin/exam-paper/cat-create', 117, 0, 92),
(94, '编辑分类', 'admin/exam-paper/cat-edit', 117, 0, 92),
(95, '删除分类', 'admin/exam-paper/cat-remove', 117, 0, 92),
(96, '上传', 'admin/file/do-upload', 102, 0, 0),
(97, '列表', 'admin/file/index', 102, 0, 0),
(98, '删除', 'admin/file/remove', 102, 0, 97),
(99, '下载', 'admin/file/download', 102, 0, 97),
(100, '上传至七牛', 'admin/qiniu/put', 102, 0, 97),
(101, '从七牛移除', 'admin/qiniu/delete', 102, 0, 97);

-- --------------------------------------------------------

--
-- 表的结构 `fay_analyst_caches`
--

CREATE TABLE IF NOT EXISTS `fay_analyst_caches` (
  `id` int(10) unsigned NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `hour` tinyint(3) NOT NULL DEFAULT '-1',
  `site` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pv` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uv` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ip` smallint(5) unsigned NOT NULL DEFAULT '0',
  `new_visitors` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bounce_rate` decimal(5,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=MyISAM AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_analyst_caches`
--

INSERT INTO `fay_analyst_caches` (`id`, `date`, `hour`, `site`, `pv`, `uv`, `ip`, `new_visitors`, `bounce_rate`) VALUES
(1, '2015-03-15', 0, 0, 0, 0, 0, 0, 0.00),
(2, '2015-03-15', 1, 0, 0, 0, 0, 0, 0.00),
(3, '2015-03-15', 2, 0, 0, 0, 0, 0, 0.00),
(4, '2015-03-15', 3, 0, 0, 0, 0, 0, 0.00),
(5, '2015-03-15', 4, 0, 0, 0, 0, 0, 0.00),
(6, '2015-03-15', 5, 0, 0, 0, 0, 0, 0.00),
(7, '2015-03-15', 6, 0, 0, 0, 0, 0, 0.00),
(8, '2015-03-15', 7, 0, 0, 0, 0, 0, 0.00),
(9, '2015-03-15', 8, 0, 0, 0, 0, 0, 0.00),
(10, '2015-03-15', 9, 0, 0, 0, 0, 0, 0.00),
(11, '2015-03-15', 10, 0, 0, 0, 0, 0, 0.00),
(12, '2015-03-15', 11, 0, 0, 0, 0, 0, 0.00),
(13, '2015-03-15', 12, 0, 0, 0, 0, 0, 0.00),
(14, '2015-03-15', 13, 0, 0, 0, 0, 0, 0.00),
(15, '2015-03-15', 14, 0, 0, 0, 0, 0, 0.00),
(16, '2015-03-15', 15, 0, 0, 0, 0, 0, 0.00),
(17, '2015-03-15', 16, 0, 0, 0, 0, 0, 0.00),
(18, '2015-03-15', 17, 0, 0, 0, 0, 0, 0.00),
(19, '2015-03-15', 18, 0, 0, 0, 0, 0, 0.00),
(20, '2015-03-15', 19, 0, 0, 0, 0, 0, 0.00),
(21, '2015-03-15', 20, 0, 0, 0, 0, 0, 0.00),
(22, '2015-03-15', 21, 0, 0, 0, 0, 0, 0.00),
(23, '2015-03-15', 22, 0, 0, 0, 0, 0, 0.00),
(24, '2015-03-15', 23, 0, 0, 0, 0, 0, 0.00),
(25, '2015-03-15', -1, 0, 0, 0, 0, 0, 0.00),
(26, '2015-03-16', 0, 0, 2, 1, 1, 1, 100.00),
(27, '2015-03-16', 1, 0, 0, 0, 0, 0, 0.00),
(28, '2015-03-16', 2, 0, 0, 0, 0, 0, 0.00),
(29, '2015-03-16', 3, 0, 0, 0, 0, 0, 0.00),
(30, '2015-03-16', 4, 0, 0, 0, 0, 0, 0.00),
(31, '2015-03-16', 5, 0, 0, 0, 0, 0, 0.00),
(32, '2015-03-16', 6, 0, 0, 0, 0, 0, 0.00),
(33, '2015-03-16', 7, 0, 0, 0, 0, 0, 0.00),
(34, '2015-03-16', 8, 0, 0, 0, 0, 0, 0.00),
(35, '2015-03-16', 9, 0, 0, 0, 0, 0, 0.00),
(36, '2015-03-16', 10, 0, 0, 0, 0, 0, 0.00),
(37, '2015-03-16', 11, 0, 0, 0, 0, 0, 0.00),
(38, '2015-03-16', 12, 0, 0, 0, 0, 0, 0.00),
(39, '2015-03-16', 13, 0, 0, 0, 0, 0, 0.00),
(40, '2015-03-16', 14, 0, 0, 0, 0, 0, 0.00),
(41, '2015-03-16', 15, 0, 0, 0, 0, 0, 0.00),
(42, '2015-03-16', 16, 0, 0, 0, 0, 0, 0.00),
(43, '2015-03-16', 17, 0, 0, 0, 0, 0, 0.00),
(44, '2015-03-16', 18, 0, 0, 0, 0, 0, 0.00),
(45, '2015-03-16', 19, 0, 0, 0, 0, 0, 0.00),
(46, '2015-03-16', 20, 0, 0, 0, 0, 0, 0.00),
(47, '2015-03-17', 0, 0, 0, 0, 0, 0, 0.00),
(48, '2015-03-17', 1, 0, 0, 0, 0, 0, 0.00),
(49, '2015-03-17', 2, 0, 0, 0, 0, 0, 0.00),
(50, '2015-03-17', 3, 0, 0, 0, 0, 0, 0.00),
(51, '2015-03-17', 4, 0, 0, 0, 0, 0, 0.00),
(52, '2015-03-17', 5, 0, 0, 0, 0, 0, 0.00),
(53, '2015-03-17', 6, 0, 0, 0, 0, 0, 0.00),
(54, '2015-03-17', 7, 0, 0, 0, 0, 0, 0.00),
(55, '2015-03-17', 8, 0, 0, 0, 0, 0, 0.00),
(56, '2015-03-16', 21, 0, 0, 0, 0, 0, 0.00),
(57, '2015-03-16', 22, 0, 0, 0, 0, 0, 0.00),
(58, '2015-03-16', 23, 0, 0, 0, 0, 0, 0.00),
(59, '2015-03-16', -1, 0, 2, 1, 1, 1, 100.00),
(60, '2015-03-17', 9, 0, 2, 1, 1, 0, 100.00),
(61, '2015-03-17', 10, 0, 0, 0, 0, 0, 0.00),
(62, '2015-03-17', 11, 0, 0, 0, 0, 0, 0.00),
(63, '2015-03-17', 12, 0, 0, 0, 0, 0, 0.00),
(64, '2015-03-17', 13, 0, 0, 0, 0, 0, 0.00),
(65, '2015-03-17', 14, 0, 0, 0, 0, 0, 0.00),
(66, '2015-03-17', 15, 0, 0, 0, 0, 0, 0.00),
(67, '2015-03-17', 16, 0, 0, 0, 0, 0, 0.00),
(68, '2015-03-17', 17, 0, 0, 0, 0, 0, 0.00),
(69, '2015-03-17', 18, 0, 0, 0, 0, 0, 0.00),
(70, '2015-03-17', 19, 0, 0, 0, 0, 0, 0.00),
(71, '2015-03-17', 20, 0, 0, 0, 0, 0, 0.00),
(72, '2015-03-17', 21, 0, 0, 0, 0, 0, 0.00),
(73, '2015-03-17', 22, 0, 0, 0, 0, 0, 0.00),
(74, '2015-03-19', 0, 0, 0, 0, 0, 0, 0.00),
(75, '2015-03-19', 1, 0, 0, 0, 0, 0, 0.00),
(76, '2015-03-19', 2, 0, 0, 0, 0, 0, 0.00),
(77, '2015-03-19', 3, 0, 0, 0, 0, 0, 0.00),
(78, '2015-03-19', 4, 0, 0, 0, 0, 0, 0.00),
(79, '2015-03-19', 5, 0, 0, 0, 0, 0, 0.00),
(80, '2015-03-19', 6, 0, 0, 0, 0, 0, 0.00),
(81, '2015-03-19', 7, 0, 0, 0, 0, 0, 0.00),
(82, '2015-03-19', 8, 0, 0, 0, 0, 0, 0.00),
(83, '2015-03-19', 9, 0, 0, 0, 0, 0, 0.00),
(84, '2015-03-19', 10, 0, 0, 0, 0, 0, 0.00),
(85, '2015-03-19', 11, 0, 0, 0, 0, 0, 0.00),
(86, '2015-03-19', 12, 0, 0, 0, 0, 0, 0.00),
(87, '2015-03-19', 13, 0, 0, 0, 0, 0, 0.00),
(88, '2015-03-19', 14, 0, 0, 0, 0, 0, 0.00),
(89, '2015-03-19', 15, 0, 0, 0, 0, 0, 0.00),
(90, '2015-03-19', 16, 0, 0, 0, 0, 0, 0.00),
(91, '2015-03-19', 17, 0, 0, 0, 0, 0, 0.00),
(92, '2015-03-19', 18, 0, 0, 0, 0, 0, 0.00),
(93, '2015-03-19', 19, 0, 0, 0, 0, 0, 0.00),
(94, '2015-03-19', 20, 0, 0, 0, 0, 0, 0.00),
(95, '2015-03-19', 21, 0, 0, 0, 0, 0, 0.00),
(96, '2015-03-18', 0, 0, 0, 0, 0, 0, 0.00),
(97, '2015-03-18', 1, 0, 0, 0, 0, 0, 0.00),
(98, '2015-03-18', 2, 0, 0, 0, 0, 0, 0.00),
(99, '2015-03-18', 3, 0, 0, 0, 0, 0, 0.00),
(100, '2015-03-18', 4, 0, 0, 0, 0, 0, 0.00),
(101, '2015-03-18', 5, 0, 0, 0, 0, 0, 0.00),
(102, '2015-03-18', 6, 0, 0, 0, 0, 0, 0.00),
(103, '2015-03-18', 7, 0, 0, 0, 0, 0, 0.00),
(104, '2015-03-18', 8, 0, 0, 0, 0, 0, 0.00),
(105, '2015-03-18', 9, 0, 0, 0, 0, 0, 0.00),
(106, '2015-03-18', 10, 0, 0, 0, 0, 0, 0.00),
(107, '2015-03-18', 11, 0, 0, 0, 0, 0, 0.00),
(108, '2015-03-18', 12, 0, 0, 0, 0, 0, 0.00),
(109, '2015-03-18', 13, 0, 0, 0, 0, 0, 0.00),
(110, '2015-03-18', 14, 0, 0, 0, 0, 0, 0.00),
(111, '2015-03-18', 15, 0, 0, 0, 0, 0, 0.00),
(112, '2015-03-18', 16, 0, 0, 0, 0, 0, 0.00),
(113, '2015-03-18', 17, 0, 0, 0, 0, 0, 0.00),
(114, '2015-03-18', 18, 0, 0, 0, 0, 0, 0.00),
(115, '2015-03-18', 19, 0, 0, 0, 0, 0, 0.00),
(116, '2015-03-18', 20, 0, 0, 0, 0, 0, 0.00),
(117, '2015-03-18', 21, 0, 0, 0, 0, 0, 0.00),
(118, '2015-03-18', 22, 0, 0, 0, 0, 0, 0.00),
(119, '2015-03-18', 23, 0, 0, 0, 0, 0, 0.00),
(120, '2015-03-18', -1, 0, 0, 0, 0, 0, 0.00),
(121, '2015-03-19', 22, 0, 1, 1, 1, 1, 100.00),
(122, '2015-03-22', 0, 0, 0, 0, 0, 0, 0.00),
(123, '2015-03-22', 1, 0, 0, 0, 0, 0, 0.00),
(124, '2015-03-22', 2, 0, 0, 0, 0, 0, 0.00),
(125, '2015-03-22', 3, 0, 0, 0, 0, 0, 0.00),
(126, '2015-03-22', 4, 0, 0, 0, 0, 0, 0.00),
(127, '2015-03-22', 5, 0, 0, 0, 0, 0, 0.00),
(128, '2015-03-22', 6, 0, 0, 0, 0, 0, 0.00),
(129, '2015-03-22', 7, 0, 0, 0, 0, 0, 0.00),
(130, '2015-03-22', 8, 0, 0, 0, 0, 0, 0.00),
(131, '2015-03-22', 9, 0, 0, 0, 0, 0, 0.00),
(132, '2015-03-22', 10, 0, 0, 0, 0, 0, 0.00),
(133, '2015-03-22', 11, 0, 0, 0, 0, 0, 0.00),
(134, '2015-03-22', 12, 0, 0, 0, 0, 0, 0.00),
(135, '2015-03-22', 13, 0, 0, 0, 0, 0, 0.00),
(136, '2015-03-21', 0, 0, 0, 0, 0, 0, 0.00),
(137, '2015-03-21', 1, 0, 0, 0, 0, 0, 0.00),
(138, '2015-03-21', 2, 0, 0, 0, 0, 0, 0.00),
(139, '2015-03-21', 3, 0, 0, 0, 0, 0, 0.00),
(140, '2015-03-21', 4, 0, 0, 0, 0, 0, 0.00),
(141, '2015-03-21', 5, 0, 0, 0, 0, 0, 0.00),
(142, '2015-03-21', 6, 0, 0, 0, 0, 0, 0.00),
(143, '2015-03-21', 7, 0, 0, 0, 0, 0, 0.00),
(144, '2015-03-21', 8, 0, 0, 0, 0, 0, 0.00),
(145, '2015-03-21', 9, 0, 0, 0, 0, 0, 0.00),
(146, '2015-03-21', 10, 0, 0, 0, 0, 0, 0.00),
(147, '2015-03-21', 11, 0, 0, 0, 0, 0, 0.00),
(148, '2015-03-21', 12, 0, 0, 0, 0, 0, 0.00),
(149, '2015-03-21', 13, 0, 0, 0, 0, 0, 0.00),
(150, '2015-03-21', 14, 0, 0, 0, 0, 0, 0.00),
(151, '2015-03-21', 15, 0, 0, 0, 0, 0, 0.00),
(152, '2015-03-21', 16, 0, 0, 0, 0, 0, 0.00),
(153, '2015-03-21', 17, 0, 0, 0, 0, 0, 0.00),
(154, '2015-03-21', 18, 0, 0, 0, 0, 0, 0.00),
(155, '2015-03-21', 19, 0, 0, 0, 0, 0, 0.00),
(156, '2015-03-21', 20, 0, 0, 0, 0, 0, 0.00),
(157, '2015-03-21', 21, 0, 0, 0, 0, 0, 0.00),
(158, '2015-03-21', 22, 0, 0, 0, 0, 0, 0.00),
(159, '2015-03-21', 23, 0, 0, 0, 0, 0, 0.00),
(160, '2015-03-21', -1, 0, 0, 0, 0, 0, 0.00),
(161, '2015-03-22', 14, 0, 2, 1, 1, 0, 100.00),
(162, '2015-03-22', 15, 0, 0, 0, 0, 0, 0.00),
(163, '2015-03-22', 16, 0, 0, 0, 0, 0, 0.00),
(164, '2015-03-22', 17, 0, 0, 0, 0, 0, 0.00),
(165, '2015-03-22', 18, 0, 0, 0, 0, 0, 0.00),
(166, '2015-03-22', 19, 0, 0, 0, 0, 0, 0.00),
(167, '2015-03-22', 20, 0, 0, 0, 0, 0, 0.00);

-- --------------------------------------------------------

--
-- 表的结构 `fay_analyst_macs`
--

CREATE TABLE IF NOT EXISTS `fay_analyst_macs` (
  `id` int(10) unsigned NOT NULL,
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
  `site` smallint(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_analyst_macs`
--

INSERT INTO `fay_analyst_macs` (`id`, `user_agent`, `browser`, `browser_version`, `shell`, `shell_version`, `os`, `ip_int`, `screen_width`, `screen_height`, `url`, `refer`, `se`, `keywords`, `hash`, `create_time`, `create_date`, `hour`, `trackid`, `site`) VALUES
(1, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36', 'Chrome', '41.0.2272.76', '', '', 'mac', 2130706433, 1280, 800, 'http://jmc/a', 'http://jmc/install/index/settings', '', '', 'a51c5fa11c6de014e60a87026cbfcc4d', 1426437489, '2015-03-16', 0, '', 1),
(2, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36', 'Chrome', '41.0.2272.89', '', '', 'mac', 2130706433, 1280, 800, 'http://jmc/a', '', '', '', '6181fdee1fc9b33344a827709aa9171e', 1426775091, '2015-03-19', 22, '', 1),
(3, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36', 'Chrome', '41.0.2272.89', '', '', 'mac', 2130706433, 1280, 800, 'http://milk/a', '', '', '', 'fd675612f11abe2d85d1e5e329132989', 1426777216, '2015-03-19', 23, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `fay_analyst_sites`
--

CREATE TABLE IF NOT EXISTS `fay_analyst_sites` (
  `id` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_analyst_sites`
--

INSERT INTO `fay_analyst_sites` (`id`, `title`, `description`, `deleted`) VALUES
(1, 'localhost', '本站', 0);

-- --------------------------------------------------------

--
-- 表的结构 `fay_analyst_visits`
--

CREATE TABLE IF NOT EXISTS `fay_analyst_visits` (
  `id` int(10) unsigned NOT NULL,
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
  `REMOTE_ADDR` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_analyst_visits`
--

INSERT INTO `fay_analyst_visits` (`id`, `mac`, `ip_int`, `refer`, `url`, `short_url`, `trackid`, `user_id`, `create_time`, `create_date`, `hour`, `site`, `views`, `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, `REMOTE_ADDR`) VALUES
(1, 1, 2130706433, 'http://jmc/install/index/settings', 'http://jmc/a', 'WjmK9j', '', 0, 1426437489, '2015-03-16', 0, 1, 2, '', '', '127.0.0.1'),
(2, 1, 2130706433, '', 'http://jmc/a', 'WjmK9j', '', 0, 1426555633, '2015-03-17', 9, 1, 5, '', '', '127.0.0.1'),
(3, 2, 2130706433, '', 'http://jmc/a', 'WjmK9j', '', 0, 1426775091, '2015-03-19', 22, 1, 1, '', '', '127.0.0.1'),
(4, 3, 2130706433, '', 'http://milk/a', '51r4va', '', 0, 1426777216, '2015-03-19', 23, 1, 1, '', '', '127.0.0.1'),
(5, 3, 2130706433, '', 'http://milk/a', '51r4va', '', 0, 1427004875, '2015-03-22', 14, 1, 2, '', '', '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `fay_categories`
--

CREATE TABLE IF NOT EXISTS `fay_categories` (
  `id` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(50) NOT NULL DEFAULT '',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `description` varchar(500) NOT NULL DEFAULT '',
  `is_nav` tinyint(1) NOT NULL DEFAULT '0',
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=10012 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_categories`
--

INSERT INTO `fay_categories` (`id`, `title`, `alias`, `parent`, `file_id`, `sort`, `description`, `is_nav`, `left_value`, `right_value`, `is_system`, `seo_title`, `seo_keywords`, `seo_description`) VALUES
(1, '未分类', '_system_post', 0, 0, 100, '文章分类根目录', 0, 2, 13, 1, '', '', ''),
(2, '页面分类', '_system_page', 0, 0, 100, '', 0, 14, 15, 1, '', '', ''),
(3, '权限', '_system_action', 0, 0, 100, '', 0, 16, 51, 1, '', '', ''),
(4, '系统消息', '_system_notification', 0, 0, 100, '', 0, 52, 53, 1, '', '', ''),
(5, '用户留言', '_system_messages', 0, 0, 100, '', 0, 54, 55, 1, '', '', ''),
(6, '商品分类', '_system_goods', 0, 0, 100, '', 0, 56, 59, 1, '', '', ''),
(7, '优惠卷', '_system_voucher', 0, 0, 100, '', 0, 60, 61, 1, '', '', ''),
(8, '考试', '_system_exam', 0, 0, 100, '', 0, 62, 67, 1, '', '', ''),
(9, '试题', '_system_exam_question', 8, 0, 100, '', 0, 63, 64, 1, '', '', ''),
(10, '试卷', '_system_exam_paper', 8, 0, 100, '', 0, 65, 66, 1, '', '', ''),
(11, '友情链接', '_system_link', 0, 0, 100, '', 0, 68, 69, 1, '', '', ''),
(101, '友情链接', '_role_youqinglianjie', 3, 0, 100, '', 0, 17, 18, 1, '', '', ''),
(102, '文件', '_role_wenjian', 3, 0, 100, '', 0, 19, 20, 1, '', '', ''),
(103, '文章', '_role_wenzhang', 3, 0, 100, '', 0, 21, 22, 1, '', '', ''),
(104, '提醒', '_role_tixing', 3, 0, 100, '', 0, 23, 24, 1, '', '', ''),
(105, '用户留言', '_role_yonghuliuyan', 3, 0, 100, '', 0, 25, 26, 1, '', '', ''),
(106, '管理员', '_role_guanliyuan', 3, 0, 100, '', 0, 27, 28, 1, '', '', ''),
(107, '页面', '_role_jingtaiyemian', 3, 0, 100, '', 0, 29, 30, 1, '', '', ''),
(108, '商品', '_role_shangpinguanli', 3, 0, 100, '', 0, 31, 32, 1, '', '', ''),
(109, '用户', '_role_yonghuguanli', 3, 0, 100, '', 0, 33, 34, 1, '', '', ''),
(110, '文章评论', '_role_wenzhangpinglun', 3, 0, 100, '', 0, 35, 36, 1, '', '', ''),
(111, '站点', '_role_zhandian', 3, 0, 100, '', 0, 37, 38, 1, '', '', ''),
(112, '角色', '_role_juese', 3, 0, 100, '', 0, 39, 40, 1, '', '', ''),
(113, '访问统计', '_role_fangwentongji', 3, 0, 100, '', 0, 41, 42, 1, '', '', ''),
(114, '导航栏', '_role_daohanglan', 3, 0, 100, '', 0, 43, 44, 1, '', '', ''),
(115, '会话', '_role_huihua', 3, 0, 100, '', 0, 45, 46, 1, '', '', ''),
(116, '试题', '_role_shiti', 3, 0, 100, '', 0, 47, 48, 1, '', '', ''),
(117, '试卷', '_role_shijuan', 3, 0, 100, '', 0, 49, 50, 1, '', '', ''),
(10008, '奶茶3', 'naicha3', 10009, 0, 255, '', 1, 10, 11, 0, '', '', ''),
(10007, '奶茶2', 'naicha2', 10009, 0, 255, '', 1, 8, 9, 0, '', '', ''),
(10006, '奶茶1', 'naicha1', 10009, 0, 255, '', 1, 6, 7, 0, '', '', ''),
(10005, '最新资讯', 'zuixinzixun', 1, 0, 255, '', 0, 3, 4, 0, '', '', ''),
(10004, '奶茶', 'naicha', 6, 0, 255, '', 1, 57, 58, 0, '', '', ''),
(10009, '产品分类', 'chanpinfenlei', 1, 0, 255, '', 1, 5, 12, 0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `fay_cat_props`
--

CREATE TABLE IF NOT EXISTS `fay_cat_props` (
  `id` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `is_sale_prop` tinyint(1) NOT NULL DEFAULT '0',
  `is_input_prop` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_cat_prop_values`
--

CREATE TABLE IF NOT EXISTS `fay_cat_prop_values` (
  `id` int(10) unsigned NOT NULL,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_cities`
--

CREATE TABLE IF NOT EXISTS `fay_cities` (
  `id` smallint(5) unsigned NOT NULL,
  `city` varchar(255) NOT NULL DEFAULT '',
  `parent` smallint(5) NOT NULL DEFAULT '0',
  `spelling` varchar(50) NOT NULL DEFAULT '',
  `abbr` varchar(30) NOT NULL DEFAULT '',
  `short` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=503 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_cities`
--

INSERT INTO `fay_cities` (`id`, `city`, `parent`, `spelling`, `abbr`, `short`) VALUES
(1, '北京', 0, 'BeiJing', 'BJ', 'B'),
(2, '上海', 0, 'ShangHai', 'SH', 'S'),
(3, '天津', 0, 'TianJin', 'TJ', 'T'),
(4, '重庆', 0, 'ZhongQing', 'ZQ', 'Z'),
(5, '黑龙江', 0, 'HeiLongJiang', 'HLJ', 'H'),
(6, '辽宁', 0, 'LiaoNing', 'LN', 'L'),
(7, '吉林', 0, 'JiLin', 'JL', 'J'),
(8, '河北', 0, 'HeBei', 'HB', 'H'),
(9, '内蒙古', 0, 'NeiMengGu', 'NMG', 'N'),
(10, '陕西', 0, 'ShanXi', 'SX', 'S'),
(11, '山西', 0, 'ShanXi', 'SX', 'S'),
(12, '甘肃', 0, 'GanSu', 'GS', 'G'),
(13, '宁夏', 0, 'NingXia', 'NX', 'N'),
(14, '新疆', 0, 'XinJiang', 'XJ', 'X'),
(15, '西藏', 0, 'XiCang', 'XC', 'X'),
(16, '青海', 0, 'QingHai', 'QH', 'Q'),
(17, '四川', 0, 'SiChuan', 'SC', 'S'),
(18, '云南', 0, 'YunNan', 'YN', 'Y'),
(19, '贵州', 0, 'GuiZhou', 'GZ', 'G'),
(20, '湖南', 0, 'HuNan', 'HN', 'H'),
(21, '湖北', 0, 'HuBei', 'HB', 'H'),
(22, '河南', 0, 'HeNan', 'HN', 'H'),
(23, '山东', 0, 'ShanDong', 'SD', 'S'),
(24, '安徽', 0, 'AnHui', 'AH', 'A'),
(25, '江苏', 0, 'JiangSu', 'JS', 'J'),
(26, '浙江', 0, 'ZheJiang', 'ZJ', 'Z'),
(27, '台湾', 0, 'TaiWan', 'TW', 'T'),
(28, '香港', 0, 'XiangGang', 'XG', 'X'),
(29, '澳门', 0, 'AoMen', 'AM', 'A'),
(30, '广东', 0, 'GuangDong', 'GD', 'G'),
(31, '广西', 0, 'GuangXi', 'GX', 'G'),
(32, '江西', 0, 'JiangXi', 'JX', 'J'),
(33, '福建', 0, 'FuJian', 'FJ', 'F'),
(34, '海南', 0, 'HaiNan', 'HN', 'H'),
(35, '其它', 0, 'QiTa', 'QT', 'Q'),
(36, '北京市', 1, 'BeiJingShi', 'BJS', 'B'),
(37, '朝阳', 1, 'ChaoYang', 'CY', 'C'),
(38, '东城', 1, 'DongCheng', 'DC', 'D'),
(39, '西城', 1, 'XiCheng', 'XC', 'X'),
(40, '海淀', 1, 'HaiDian', 'HD', 'H'),
(41, '丰台', 1, 'FengTai', 'FT', 'F'),
(42, '崇文', 1, 'ChongWen', 'CW', 'C'),
(43, '宣武', 1, 'XuanWu', 'XW', 'X'),
(44, '通州', 1, 'TongZhou', 'TZ', 'T'),
(45, '昌平', 1, 'ChangPing', 'CP', 'C'),
(46, '大兴', 1, 'DaXing', 'DX', 'D'),
(47, '房山', 1, 'FangShan', 'FS', 'F'),
(48, '怀柔', 1, 'HuaiRou', 'HR', 'H'),
(49, '门头沟', 1, 'MenTouGou', 'MTG', 'M'),
(50, '密云', 1, 'MiYun', 'MY', 'M'),
(51, '平谷', 1, 'PingGu', 'PG', 'P'),
(52, '石景山', 1, 'ShiJingShan', 'SJS', 'S'),
(53, '顺义', 1, 'ShunYi', 'SY', 'S'),
(54, '延庆', 1, 'YanQing', 'YQ', 'Y'),
(55, '上海市', 2, 'ShangHaiShi', 'SHS', 'S'),
(56, '长宁', 2, 'ChangNing', 'CN', 'C'),
(57, '松江', 2, 'SongJiang', 'SJ', 'S'),
(58, '闸北', 2, 'ZhaBei', 'ZB', 'Z'),
(59, '徐汇', 2, 'XuHui', 'XH', 'X'),
(60, '杨浦', 2, 'YangPu', 'YP', 'Y'),
(61, '青浦', 2, 'QingPu', 'QP', 'Q'),
(62, '崇明', 2, 'ChongMing', 'CM', 'C'),
(63, '奉贤', 2, 'FengXian', 'FX', 'F'),
(64, '普陀', 2, 'PuTuo', 'PT', 'P'),
(65, '浦东', 2, 'PuDong', 'PD', 'P'),
(66, '南市', 2, 'NanShi', 'NS', 'N'),
(67, '虹口', 2, 'HongKou', 'HK', 'H'),
(68, '南汇', 2, 'NanHui', 'NH', 'N'),
(69, '闵行', 2, 'Xing', 'X', 'X'),
(70, '黄浦', 2, 'HuangPu', 'HP', 'H'),
(71, '嘉定', 2, 'JiaDing', 'JD', 'J'),
(72, '金山', 2, 'JinShan', 'JS', 'J'),
(73, '静安', 2, 'JingAn', 'JA', 'J'),
(74, '卢湾', 2, 'LuWan', 'LW', 'L'),
(75, '宝山', 2, 'BaoShan', 'BS', 'B'),
(76, '天津市', 3, 'TianJinShi', 'TJS', 'T'),
(77, '西青', 3, 'XiQing', 'XQ', 'X'),
(78, '武清', 3, 'WuQing', 'WQ', 'W'),
(79, '北辰', 3, 'BeiChen', 'BC', 'B'),
(80, '大港', 3, 'DaGang', 'DG', 'D'),
(81, '东丽', 3, 'DongLi', 'DL', 'D'),
(82, '汉沽', 3, 'HanGu', 'HG', 'H'),
(83, '塘沽', 3, 'TangGu', 'TG', 'T'),
(84, '和平', 3, 'HePing', 'HP', 'H'),
(85, '河北', 3, 'HeBei', 'HB', 'H'),
(86, '河东', 3, 'HeDong', 'HD', 'H'),
(87, '河西', 3, 'HeXi', 'HX', 'H'),
(88, '红桥', 3, 'HongQiao', 'HQ', 'H'),
(89, '蓟县', 3, 'JiXian', 'JX', 'J'),
(90, '津南', 3, 'JinNan', 'JN', 'J'),
(91, '静海', 3, 'JingHai', 'JH', 'J'),
(92, '南开', 3, 'NanKai', 'NK', 'N'),
(93, '宁河', 3, 'NingHe', 'NH', 'N'),
(94, '宝坻', 3, 'Bao', 'B', 'B'),
(95, '重庆市', 4, 'ZhongQingShi', 'ZQS', 'Z'),
(96, '万州', 4, 'WanZhou', 'WZ', 'W'),
(97, '江北', 4, 'JiangBei', 'JB', 'J'),
(98, '渝中', 4, 'YuZhong', 'YZ', 'Y'),
(99, '巴南', 4, 'BaNan', 'BN', 'B'),
(100, '沙坪坝', 4, 'ShaPingBa', 'SPB', 'S'),
(101, '万盛区', 4, 'WanShengQu', 'WSQ', 'W'),
(102, '南岸', 4, 'NanAn', 'NA', 'N'),
(103, '北碚区', 4, 'BeiQu', 'BQ', 'B'),
(104, '渝北', 4, 'YuBei', 'YB', 'Y'),
(105, '九龙坡', 4, 'JiuLongPo', 'JLP', 'J'),
(106, '大渡口', 4, 'DaDuKou', 'DDK', 'D'),
(107, '双桥', 4, 'ShuangQiao', 'SQ', 'S'),
(108, '永川', 4, 'YongChuan', 'YC', 'Y'),
(109, '合川', 4, 'HeChuan', 'HC', 'H'),
(110, '江津', 4, 'JiangJin', 'JJ', 'J'),
(111, '南川', 4, 'NanChuan', 'NC', 'N'),
(112, '綦江', 4, 'Jiang', 'J', 'J'),
(113, '长寿', 4, 'ChangShou', 'CS', 'C'),
(114, '潼南', 4, 'Nan', 'N', 'N'),
(115, '荣昌', 4, 'RongChang', 'RC', 'R'),
(116, '璧山', 4, 'Shan', 'S', 'S'),
(117, '大足', 4, 'DaZu', 'DZ', 'D'),
(118, '铜梁', 4, 'TongLiang', 'TL', 'T'),
(119, '梁平', 4, 'LiangPing', 'LP', 'L'),
(120, '城口', 4, 'ChengKou', 'CK', 'C'),
(121, '垫江', 4, 'DianJiang', 'DJ', 'D'),
(122, '武隆', 4, 'WuLong', 'WL', 'W'),
(123, '丰都', 4, 'FengDu', 'FD', 'F'),
(124, '奉节', 4, 'FengJie', 'FJ', 'F'),
(125, '开县', 4, 'KaiXian', 'KX', 'K'),
(126, '云阳', 4, 'YunYang', 'YY', 'Y'),
(127, '忠县', 4, 'ZhongXian', 'ZX', 'Z'),
(128, '巫溪', 4, 'WuXi', 'WX', 'W'),
(129, '巫山', 4, 'WuShan', 'WS', 'W'),
(130, '黔江', 4, 'QianJiang', 'QJ', 'Q'),
(131, '石柱土', 4, 'ShiZhuTu', 'SZT', 'S'),
(132, '酉阳', 4, 'YouYang', 'YY', 'Y'),
(133, '秀山', 4, 'XiuShan', 'XS', 'X'),
(134, '彭水', 4, 'PengShui', 'PS', 'P'),
(135, '黔江开发区', 4, 'QianJiangKaiFaQu', 'QJKFQ', 'Q'),
(136, '涪陵', 4, 'FuLing', 'FL', 'F'),
(137, '哈尔滨', 5, 'HaErBin', 'HEB', 'H'),
(138, '齐齐哈尔', 5, 'QiQiHaEr', 'QQHE', 'Q'),
(139, '鸡西', 5, 'JiXi', 'JX', 'J'),
(140, '鹤岗', 5, 'HeGang', 'HG', 'H'),
(141, '双鸭山', 5, 'ShuangYaShan', 'SYS', 'S'),
(142, '大庆', 5, 'DaQing', 'DQ', 'D'),
(143, '伊春', 5, 'YiChun', 'YC', 'Y'),
(144, '佳木斯', 5, 'JiaMuSi', 'JMS', 'J'),
(145, '七台河', 5, 'QiTaiHe', 'QTH', 'Q'),
(146, '牡丹江', 5, 'MuDanJiang', 'MDJ', 'M'),
(147, '黑河', 5, 'HeiHe', 'HH', 'H'),
(148, '绥化', 5, 'SuiHua', 'SH', 'S'),
(149, '松花江', 5, 'SongHuaJiang', 'SHJ', 'S'),
(150, '嫩江', 5, 'NenJiang', 'NJ', 'N'),
(151, '合江', 5, 'HeJiang', 'HJ', 'H'),
(152, '大兴安岭', 5, 'DaXingAnLing', 'DXAL', 'D'),
(153, '沈阳', 6, 'ShenYang', 'SY', 'S'),
(154, '大连', 6, 'DaLian', 'DL', 'D'),
(155, '鞍山', 6, 'AnShan', 'AS', 'A'),
(156, '抚顺', 6, 'FuShun', 'FS', 'F'),
(157, '本溪', 6, 'BenXi', 'BX', 'B'),
(158, '丹东', 6, 'DanDong', 'DD', 'D'),
(159, '锦州', 6, 'JinZhou', 'JZ', 'J'),
(160, '营口', 6, 'YingKou', 'YK', 'Y'),
(161, '阜新', 6, 'FuXin', 'FX', 'F'),
(162, '辽阳', 6, 'LiaoYang', 'LY', 'L'),
(163, '盘锦', 6, 'PanJin', 'PJ', 'P'),
(164, '铁岭', 6, 'TieLing', 'TL', 'T'),
(165, '朝阳', 6, 'ChaoYang', 'CY', 'C'),
(166, '葫芦岛', 6, 'HuLuDao', 'HLD', 'H'),
(167, '长春', 7, 'ChangChun', 'CC', 'C'),
(168, '吉林', 7, 'JiLin', 'JL', 'J'),
(169, '四平', 7, 'SiPing', 'SP', 'S'),
(170, '辽源', 7, 'LiaoYuan', 'LY', 'L'),
(171, '通化', 7, 'TongHua', 'TH', 'T'),
(172, '白山', 7, 'BaiShan', 'BS', 'B'),
(173, '松原', 7, 'SongYuan', 'SY', 'S'),
(174, '白城', 7, 'BaiCheng', 'BC', 'B'),
(175, '延边', 7, 'YanBian', 'YB', 'Y'),
(176, '永吉', 7, 'YongJi', 'YJ', 'Y'),
(177, '德惠', 7, 'DeHui', 'DH', 'D'),
(178, '石家庄', 8, 'ShiJiaZhuang', 'SJZ', 'S'),
(179, '唐山', 8, 'TangShan', 'TS', 'T'),
(180, '秦皇岛', 8, 'QinHuangDao', 'QHD', 'Q'),
(181, '邯郸', 8, 'HanDan', 'HD', 'H'),
(182, '邢台', 8, 'XingTai', 'XT', 'X'),
(183, '保定', 8, 'BaoDing', 'BD', 'B'),
(184, '张家口', 8, 'ZhangJiaKou', 'ZJK', 'Z'),
(185, '承德', 8, 'ChengDe', 'CD', 'C'),
(186, '沧州', 8, 'CangZhou', 'CZ', 'C'),
(187, '廊坊', 8, 'LangFang', 'LF', 'L'),
(188, '衡水', 8, 'HengShui', 'HS', 'H'),
(189, '呼和浩特', 9, 'HuHeHaoTe', 'HHHT', 'H'),
(190, '包头', 9, 'BaoTou', 'BT', 'B'),
(191, '乌海', 9, 'WuHai', 'WH', 'W'),
(192, '赤峰', 9, 'ChiFeng', 'CF', 'C'),
(193, '通辽', 9, 'TongLiao', 'TL', 'T'),
(194, '鄂尔多斯', 9, 'EErDuoSi', 'EEDS', 'E'),
(195, '呼伦贝尔盟', 9, 'HuLunBeiErMeng', 'HLBEM', 'H'),
(196, '兴安盟', 9, 'XingAnMeng', 'XAM', 'X'),
(197, '哲里木盟', 9, 'ZheLiMuMeng', 'ZLMM', 'Z'),
(198, '昭乌达盟', 9, 'ZhaoWuDaMeng', 'ZWDM', 'Z'),
(199, '锡林郭勒盟', 9, 'XiLinGuoLeMeng', 'XLGLM', 'X'),
(200, '乌兰察布盟', 9, 'WuLanChaBuMeng', 'WLCBM', 'W'),
(201, '伊克昭盟', 9, 'YiKeZhaoMeng', 'YKZM', 'Y'),
(202, '巴彦淖尔盟', 9, 'BaYanNaoErMeng', 'BYNEM', 'B'),
(203, '阿拉善盟', 9, 'ALaShanMeng', 'ALSM', 'A'),
(204, '西安', 10, 'XiAn', 'XA', 'X'),
(205, '铜川', 10, 'TongChuan', 'TC', 'T'),
(206, '宝鸡', 10, 'BaoJi', 'BJ', 'B'),
(207, '咸阳', 10, 'XianYang', 'XY', 'X'),
(208, '渭南', 10, 'WeiNan', 'WN', 'W'),
(209, '延安', 10, 'YanAn', 'YA', 'Y'),
(210, '汉中', 10, 'HanZhong', 'HZ', 'H'),
(211, '榆林', 10, 'YuLin', 'YL', 'Y'),
(212, '安康', 10, 'AnKang', 'AK', 'A'),
(213, '商洛', 10, 'ShangLuo', 'SL', 'S'),
(214, '太原', 11, 'TaiYuan', 'TY', 'T'),
(215, '大同', 11, 'DaTong', 'DT', 'D'),
(216, '阳泉', 11, 'YangQuan', 'YQ', 'Y'),
(217, '长治', 11, 'ChangZhi', 'CZ', 'C'),
(218, '晋城', 11, 'JinCheng', 'JC', 'J'),
(219, '朔州', 11, 'ShuoZhou', 'SZ', 'S'),
(220, '晋中', 11, 'JinZhong', 'JZ', 'J'),
(221, '运城', 11, 'YunCheng', 'YC', 'Y'),
(222, '忻州', 11, 'XinZhou', 'XZ', 'X'),
(223, '临汾', 11, 'LinFen', 'LF', 'L'),
(224, '雁北', 11, 'YanBei', 'YB', 'Y'),
(225, '忻县', 11, 'XinXian', 'XX', 'X'),
(226, '吕梁', 11, 'LvLiang', 'LL', 'L'),
(227, '晋东南', 11, 'JinDongNan', 'JDN', 'J'),
(228, '兰州', 12, 'LanZhou', 'LZ', 'L'),
(229, '嘉峪关', 12, 'JiaYuGuan', 'JYG', 'J'),
(230, '金昌', 12, 'JinChang', 'JC', 'J'),
(231, '白银', 12, 'BaiYin', 'BY', 'B'),
(232, '天水', 12, 'TianShui', 'TS', 'T'),
(233, '武威', 12, 'WuWei', 'WW', 'W'),
(234, '酒泉', 12, 'JiuQuan', 'JQ', 'J'),
(235, '张掖', 12, 'ZhangYe', 'ZY', 'Z'),
(236, '定西', 12, 'DingXi', 'DX', 'D'),
(237, '陇南', 12, 'LongNan', 'LN', 'L'),
(238, '平凉', 12, 'PingLiang', 'PL', 'P'),
(239, '庆阳', 12, 'QingYang', 'QY', 'Q'),
(240, '临夏', 12, 'LinXia', 'LX', 'L'),
(241, '甘南', 12, 'GanNan', 'GN', 'G'),
(242, '银川', 13, 'YinChuan', 'YC', 'Y'),
(243, '石嘴山', 13, 'ShiZuiShan', 'SZS', 'S'),
(244, '吴忠', 13, 'WuZhong', 'WZ', 'W'),
(245, '固原', 13, 'GuYuan', 'GY', 'G'),
(246, '银南', 13, 'YinNan', 'YN', 'Y'),
(247, '乌鲁木齐', 14, 'WuLuMuQi', 'WLMQ', 'W'),
(248, '克拉玛依', 14, 'KeLaMaYi', 'KLMY', 'K'),
(249, '石河子', 14, 'ShiHeZi', 'SHZ', 'S'),
(250, '吐鲁番', 14, 'TuLuFan', 'TLF', 'T'),
(251, '哈密', 14, 'HaMi', 'HM', 'H'),
(252, '昌吉', 14, 'ChangJi', 'CJ', 'C'),
(253, '伊犁', 14, 'YiLi', 'YL', 'Y'),
(254, '塔城', 14, 'TaCheng', 'TC', 'T'),
(255, '阿勒泰', 14, 'ALeTai', 'ALT', 'A'),
(256, '博尔塔拉', 14, 'BoErTaLa', 'BETL', 'B'),
(257, '巴音郭楞', 14, 'BaYinGuoLeng', 'BYGL', 'B'),
(258, '阿克苏', 14, 'AKeSu', 'AKS', 'A'),
(259, '克孜勒苏', 14, 'KeZiLeSu', 'KZLS', 'K'),
(260, '喀什', 14, 'KaShi', 'KS', 'K'),
(261, '和田', 14, 'HeTian', 'HT', 'H'),
(262, '拉萨', 15, 'LaSa', 'LS', 'L'),
(263, '昌都', 15, 'ChangDu', 'CD', 'C'),
(264, '山南', 15, 'ShanNan', 'SN', 'S'),
(265, '日喀则', 15, 'RiKaZe', 'RKZ', 'R'),
(266, '那曲', 15, 'NaQu', 'NQ', 'N'),
(267, '阿里', 15, 'ALi', 'AL', 'A'),
(268, '林芝', 15, 'LinZhi', 'LZ', 'L'),
(269, '江孜', 15, 'JiangZi', 'JZ', 'J'),
(270, '西宁', 16, 'XiNing', 'XN', 'X'),
(271, '海东', 16, 'HaiDong', 'HD', 'H'),
(272, '海北', 16, 'HaiBei', 'HB', 'H'),
(273, '黄南', 16, 'HuangNan', 'HN', 'H'),
(274, '海南', 16, 'HaiNan', 'HN', 'H'),
(275, '果洛', 16, 'GuoLuo', 'GL', 'G'),
(276, '玉树', 16, 'YuShu', 'YS', 'Y'),
(277, '海西', 16, 'HaiXi', 'HX', 'H'),
(278, '成都', 17, 'ChengDu', 'CD', 'C'),
(279, '自贡', 17, 'ZiGong', 'ZG', 'Z'),
(280, '攀枝花', 17, 'PanZhiHua', 'PZH', 'P'),
(281, '泸州', 17, 'Zhou', 'Z', 'Z'),
(282, '德阳', 17, 'DeYang', 'DY', 'D'),
(283, '绵阳', 17, 'MianYang', 'MY', 'M'),
(284, '广元', 17, 'GuangYuan', 'GY', 'G'),
(285, '遂宁', 17, 'SuiNing', 'SN', 'S'),
(286, '内江', 17, 'NeiJiang', 'NJ', 'N'),
(287, '乐山', 17, 'LeShan', 'LS', 'L'),
(288, '万县', 17, 'WanXian', 'WX', 'W'),
(289, '南充', 17, 'NanChong', 'NC', 'N'),
(290, '眉山', 17, 'MeiShan', 'MS', 'M'),
(291, '宜宾', 17, 'YiBin', 'YB', 'Y'),
(292, '广安', 17, 'GuangAn', 'GA', 'G'),
(293, '达州', 17, 'DaZhou', 'DZ', 'D'),
(294, '雅安', 17, 'YaAn', 'YA', 'Y'),
(295, '巴中', 17, 'BaZhong', 'BZ', 'B'),
(296, '资阳', 17, 'ZiYang', 'ZY', 'Z'),
(297, '永川', 17, 'YongChuan', 'YC', 'Y'),
(298, '涪陵', 17, 'FuLing', 'FL', 'F'),
(299, '内江', 17, 'NeiJiang', 'NJ', 'N'),
(300, '温江', 17, 'WenJiang', 'WJ', 'W'),
(301, '绵阳', 17, 'MianYang', 'MY', 'M'),
(302, '达县', 17, 'DaXian', 'DX', 'D'),
(303, '黔江', 17, 'QianJiang', 'QJ', 'Q'),
(304, '凉山', 17, 'LiangShan', 'LS', 'L'),
(305, '昆明', 18, 'KunMing', 'KM', 'K'),
(306, '东川', 18, 'DongChuan', 'DC', 'D'),
(307, '曲靖', 18, 'QuJing', 'QJ', 'Q'),
(308, '玉溪', 18, 'YuXi', 'YX', 'Y'),
(309, '保山', 18, 'BaoShan', 'BS', 'B'),
(310, '昭通', 18, 'ZhaoTong', 'ZT', 'Z'),
(311, '楚雄', 18, 'ChuXiong', 'CX', 'C'),
(312, '红河', 18, 'HongHe', 'HH', 'H'),
(313, '文山', 18, 'WenShan', 'WS', 'W'),
(314, '思茅', 18, 'SiMao', 'SM', 'S'),
(315, '西双版纳', 18, 'XiShuangBanNa', 'XSBN', 'X'),
(316, '大理', 18, 'DaLi', 'DL', 'D'),
(317, '德宏', 18, 'DeHong', 'DH', 'D'),
(318, '丽江', 18, 'LiJiang', 'LJ', 'L'),
(319, '怒江', 18, 'NuJiang', 'NJ', 'N'),
(320, '迪庆', 18, 'DiQing', 'DQ', 'D'),
(321, '临沧', 18, 'LinCang', 'LC', 'L'),
(322, '贵阳', 19, 'GuiYang', 'GY', 'G'),
(323, '六盘水', 19, 'LiuPanShui', 'LPS', 'L'),
(324, '遵义', 19, 'ZunYi', 'ZY', 'Z'),
(325, '安顺', 19, 'AnShun', 'AS', 'A'),
(326, '铜仁', 19, 'TongRen', 'TR', 'T'),
(327, '黔西南', 19, 'QianXiNan', 'QXN', 'Q'),
(328, '毕节', 19, 'BiJie', 'BJ', 'B'),
(329, '黔东南', 19, 'QianDongNan', 'QDN', 'Q'),
(330, '黔南', 19, 'QianNan', 'QN', 'Q'),
(331, '长沙', 20, 'ChangSha', 'CS', 'C'),
(332, '株洲', 20, 'ZhuZhou', 'ZZ', 'Z'),
(333, '湘潭', 20, 'XiangTan', 'XT', 'X'),
(334, '衡阳', 20, 'HengYang', 'HY', 'H'),
(335, '邵阳', 20, 'ShaoYang', 'SY', 'S'),
(336, '岳阳', 20, 'YueYang', 'YY', 'Y'),
(337, '常德', 20, 'ChangDe', 'CD', 'C'),
(338, '张家界', 20, 'ZhangJiaJie', 'ZJJ', 'Z'),
(339, '益阳', 20, 'YiYang', 'YY', 'Y'),
(340, '郴州', 20, 'ChenZhou', 'CZ', 'C'),
(341, '永州', 20, 'YongZhou', 'YZ', 'Y'),
(342, '怀化', 20, 'HuaiHua', 'HH', 'H'),
(343, '娄底', 20, 'LouDi', 'LD', 'L'),
(344, '零陵', 20, 'LingLing', 'LL', 'L'),
(345, '怀北', 20, 'HuaiBei', 'HB', 'H'),
(346, '湘西', 20, 'XiangXi', 'XX', 'X'),
(347, '武汉', 21, 'WuHan', 'WH', 'W'),
(348, '黄石', 21, 'HuangShi', 'HS', 'H'),
(349, '十堰', 21, 'ShiYan', 'SY', 'S'),
(350, '沙市', 21, 'ShaShi', 'SS', 'S'),
(351, '宜昌', 21, 'YiChang', 'YC', 'Y'),
(352, '襄樊', 21, 'XiangFan', 'XF', 'X'),
(353, '鄂州', 21, 'EZhou', 'EZ', 'E'),
(354, '荆门', 21, 'JingMen', 'JM', 'J'),
(355, '孝感', 21, 'XiaoGan', 'XG', 'X'),
(356, '荆州', 21, 'JingZhou', 'JZ', 'J'),
(357, '黄冈', 21, 'HuangGang', 'HG', 'H'),
(358, '咸宁', 21, 'XianNing', 'XN', 'X'),
(359, '随州', 21, 'SuiZhou', 'SZ', 'S'),
(360, '襄阳', 21, 'XiangYang', 'XY', 'X'),
(361, '郧阳', 21, 'YunYang', 'YY', 'Y'),
(362, '郑州', 22, 'ZhengZhou', 'ZZ', 'Z'),
(363, '开封', 22, 'KaiFeng', 'KF', 'K'),
(364, '洛阳', 22, 'LuoYang', 'LY', 'L'),
(365, '平顶山', 22, 'PingDingShan', 'PDS', 'P'),
(366, '安阳', 22, 'AnYang', 'AY', 'A'),
(367, '鹤壁', 22, 'HeBi', 'HB', 'H'),
(368, '新乡', 22, 'XinXiang', 'XX', 'X'),
(369, '焦作', 22, 'JiaoZuo', 'JZ', 'J'),
(370, '濮阳', 22, 'Yang', 'Y', 'Y'),
(371, '许昌', 22, 'XuChang', 'XC', 'X'),
(372, '漯河', 22, 'He', 'H', 'H'),
(373, '三门峡', 22, 'SanMenXia', 'SMX', 'S'),
(374, '南阳', 22, 'NanYang', 'NY', 'N'),
(375, '商丘', 22, 'ShangQiu', 'SQ', 'S'),
(376, '信阳', 22, 'XinYang', 'XY', 'X'),
(377, '周口', 22, 'ZhouKou', 'ZK', 'Z'),
(378, '驻马店', 22, 'ZhuMaDian', 'ZMD', 'Z'),
(379, '济南', 23, 'JiNan', 'JN', 'J'),
(380, '青岛', 23, 'QingDao', 'QD', 'Q'),
(381, '淄博', 23, 'ZiBo', 'ZB', 'Z'),
(382, '枣庄', 23, 'ZaoZhuang', 'ZZ', 'Z'),
(383, '东营', 23, 'DongYing', 'DY', 'D'),
(384, '烟台', 23, 'YanTai', 'YT', 'Y'),
(385, '潍坊', 23, 'WeiFang', 'WF', 'W'),
(386, '济宁', 23, 'JiNing', 'JN', 'J'),
(387, '泰安', 23, 'TaiAn', 'TA', 'T'),
(388, '威海', 23, 'WeiHai', 'WH', 'W'),
(389, '日照', 23, 'RiZhao', 'RZ', 'R'),
(390, '莱芜', 23, 'LaiWu', 'LW', 'L'),
(391, '临沂', 23, 'LinYi', 'LY', 'L'),
(392, '德州', 23, 'DeZhou', 'DZ', 'D'),
(393, '聊城', 23, 'LiaoCheng', 'LC', 'L'),
(394, '滨州', 23, 'BinZhou', 'BZ', 'B'),
(395, '菏泽', 23, 'HeZe', 'HZ', 'H'),
(396, '合肥', 24, 'HeFei', 'HF', 'H'),
(397, '芜湖', 24, 'WuHu', 'WH', 'W'),
(398, '蚌埠', 24, 'BangBu', 'BB', 'B'),
(399, '淮南', 24, 'HuaiNan', 'HN', 'H'),
(400, '马鞍山', 24, 'MaAnShan', 'MAS', 'M'),
(401, '淮北', 24, 'HuaiBei', 'HB', 'H'),
(402, '铜陵', 24, 'TongLing', 'TL', 'T'),
(403, '安庆', 24, 'AnQing', 'AQ', 'A'),
(404, '黄山', 24, 'HuangShan', 'HS', 'H'),
(405, '滁州', 24, 'ChuZhou', 'CZ', 'C'),
(406, '阜阳', 24, 'FuYang', 'FY', 'F'),
(407, '宿州', 24, 'SuZhou', 'SZ', 'S'),
(408, '巢湖', 24, 'ChaoHu', 'CH', 'C'),
(409, '六安', 24, 'LiuAn', 'LA', 'L'),
(410, '亳州', 24, 'Zhou', 'Z', 'Z'),
(411, '池州', 24, 'ChiZhou', 'CZ', 'C'),
(412, '宣城', 24, 'XuanCheng', 'XC', 'X'),
(413, '徽州', 24, 'HuiZhou', 'HZ', 'H'),
(414, '南京', 25, 'NanJing', 'NJ', 'N'),
(415, '无锡', 25, 'WuXi', 'WX', 'W'),
(416, '徐州', 25, 'XuZhou', 'XZ', 'X'),
(417, '常州', 25, 'ChangZhou', 'CZ', 'C'),
(418, '苏州', 25, 'SuZhou', 'SZ', 'S'),
(419, '南通', 25, 'NanTong', 'NT', 'N'),
(420, '连云港', 25, 'LianYunGang', 'LYG', 'L'),
(421, '淮安', 25, 'HuaiAn', 'HA', 'H'),
(422, '盐城', 25, 'YanCheng', 'YC', 'Y'),
(423, '扬州', 25, 'YangZhou', 'YZ', 'Y'),
(424, '镇江', 25, 'ZhenJiang', 'ZJ', 'Z'),
(425, '泰州', 25, 'TaiZhou', 'TZ', 'T'),
(426, '宿迁', 25, 'SuQian', 'SQ', 'S'),
(427, '淮阴', 25, 'HuaiYin', 'HY', 'H'),
(428, '杭州', 26, 'HangZhou', 'HZ', 'H'),
(429, '宁波', 26, 'NingBo', 'NB', 'N'),
(430, '温州', 26, 'WenZhou', 'WZ', 'W'),
(431, '嘉兴', 26, 'JiaXing', 'JX', 'J'),
(432, '湖州', 26, 'HuZhou', 'HZ', 'H'),
(433, '绍兴', 26, 'ShaoXing', 'SX', 'S'),
(434, '金华', 26, 'JinHua', 'JH', 'J'),
(435, '衢州', 26, 'Zhou', 'Z', 'Z'),
(436, '舟山', 26, 'ZhouShan', 'ZS', 'Z'),
(437, '台州', 26, 'TaiZhou', 'TZ', 'T'),
(438, '丽水', 26, 'LiShui', 'LS', 'L'),
(439, '台湾', 27, 'TaiWan', 'TW', 'T'),
(440, '香港', 28, 'XiangGang', 'XG', 'X'),
(441, '澳门', 29, 'AoMen', 'AM', 'A'),
(442, '广州', 30, 'GuangZhou', 'GZ', 'G'),
(443, '韶关', 30, 'ShaoGuan', 'SG', 'S'),
(444, '深圳', 30, 'Shen', 'S', 'S'),
(445, '珠海', 30, 'ZhuHai', 'ZH', 'Z'),
(446, '汕头', 30, 'ShanTou', 'ST', 'S'),
(447, '佛山', 30, 'FoShan', 'FS', 'F'),
(448, '江门', 30, 'JiangMen', 'JM', 'J'),
(449, '湛江', 30, 'ZhanJiang', 'ZJ', 'Z'),
(450, '茂名', 30, 'MaoMing', 'MM', 'M'),
(451, '海口', 30, 'HaiKou', 'HK', 'H'),
(452, '肇庆', 30, 'ZhaoQing', 'ZQ', 'Z'),
(453, '惠州', 30, 'HuiZhou', 'HZ', 'H'),
(454, '梅州', 30, 'MeiZhou', 'MZ', 'M'),
(455, '汕尾', 30, 'ShanWei', 'SW', 'S'),
(456, '河源', 30, 'HeYuan', 'HY', 'H'),
(457, '阳江', 30, 'YangJiang', 'YJ', 'Y'),
(458, '清远', 30, 'QingYuan', 'QY', 'Q'),
(459, '东莞', 30, 'Dong', 'D', 'D'),
(460, '中山', 30, 'ZhongShan', 'ZS', 'Z'),
(461, '梅县', 30, 'MeiXian', 'MX', 'M'),
(462, '惠阳', 30, 'HuiYang', 'HY', 'H'),
(463, '潮州', 30, 'ChaoZhou', 'CZ', 'C'),
(464, '揭阳', 30, 'JieYang', 'JY', 'J'),
(465, '云浮', 30, 'YunFu', 'YF', 'Y'),
(466, '南宁', 31, 'NanNing', 'NN', 'N'),
(467, '柳州', 31, 'LiuZhou', 'LZ', 'L'),
(468, '桂林', 31, 'GuiLin', 'GL', 'G'),
(469, '梧州', 31, 'WuZhou', 'WZ', 'W'),
(470, '北海', 31, 'BeiHai', 'BH', 'B'),
(471, '防城港', 31, 'FangChengGang', 'FCG', 'F'),
(472, '钦州', 31, 'QinZhou', 'QZ', 'Q'),
(473, '贵港', 31, 'GuiGang', 'GG', 'G'),
(474, '玉林', 31, 'YuLin', 'YL', 'Y'),
(475, '贺州', 31, 'HeZhou', 'HZ', 'H'),
(476, '百色', 31, 'BaiSe', 'BS', 'B'),
(477, '河池', 31, 'HeChi', 'HC', 'H'),
(478, '南昌', 32, 'NanChang', 'NC', 'N'),
(479, '景德镇', 32, 'JingDeZhen', 'JDZ', 'J'),
(480, '萍乡', 32, 'PingXiang', 'PX', 'P'),
(481, '九江', 32, 'JiuJiang', 'JJ', 'J'),
(482, '新余', 32, 'XinYu', 'XY', 'X'),
(483, '鹰潭', 32, 'YingTan', 'YT', 'Y'),
(484, '抚州', 32, 'FuZhou', 'FZ', 'F'),
(485, '上饶', 32, 'ShangRao', 'SR', 'S'),
(486, '赣州', 32, 'GanZhou', 'GZ', 'G'),
(487, '宜春', 32, 'YiChun', 'YC', 'Y'),
(488, '吉安', 32, 'JiAn', 'JA', 'J'),
(489, '福州', 33, 'FuZhou', 'FZ', 'F'),
(490, '厦门', 33, 'XiaMen', 'XM', 'X'),
(491, '莆田', 33, 'PuTian', 'PT', 'P'),
(492, '三明', 33, 'SanMing', 'SM', 'S'),
(493, '泉州', 33, 'QuanZhou', 'QZ', 'Q'),
(494, '漳州', 33, 'ZhangZhou', 'ZZ', 'Z'),
(495, '南平', 33, 'NanPing', 'NP', 'N'),
(496, '龙岩', 33, 'LongYan', 'LY', 'L'),
(497, '宁德', 33, 'NingDe', 'ND', 'N'),
(498, '晋江', 33, 'JinJiang', 'JJ', 'J'),
(499, '龙溪', 33, 'LongXi', 'LX', 'L'),
(500, '海口', 34, 'HaiKou', 'HK', 'H'),
(501, '三亚', 34, 'SanYa', 'SY', 'S'),
(502, '其他', 35, 'QiTa', 'QT', 'Q');

-- --------------------------------------------------------

--
-- 表的结构 `fay_contacts`
--

CREATE TABLE IF NOT EXISTS `fay_contacts` (
  `id` int(10) unsigned NOT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_answers`
--

CREATE TABLE IF NOT EXISTS `fay_exam_answers` (
  `id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `is_right_answer` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_exams`
--

CREATE TABLE IF NOT EXISTS `fay_exam_exams` (
  `id` mediumint(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL,
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `rand` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_exam_questions`
--

CREATE TABLE IF NOT EXISTS `fay_exam_exam_questions` (
  `id` int(10) unsigned NOT NULL,
  `exam_id` mediumint(8) unsigned NOT NULL,
  `question_id` mediumint(8) unsigned NOT NULL,
  `total_score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_exam_question_answers_int`
--

CREATE TABLE IF NOT EXISTS `fay_exam_exam_question_answers_int` (
  `exam_question_id` int(10) unsigned NOT NULL,
  `user_answer_id` mediumint(8) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_exam_question_answer_text`
--

CREATE TABLE IF NOT EXISTS `fay_exam_exam_question_answer_text` (
  `exam_question_id` int(11) NOT NULL,
  `user_answer` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_papers`
--

CREATE TABLE IF NOT EXISTS `fay_exam_papers` (
  `id` mediumint(8) unsigned NOT NULL,
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
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_paper_questions`
--

CREATE TABLE IF NOT EXISTS `fay_exam_paper_questions` (
  `paper_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `question_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_exam_questions`
--

CREATE TABLE IF NOT EXISTS `fay_exam_questions` (
  `id` mediumint(8) unsigned NOT NULL,
  `question` text NOT NULL,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `rand` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_favourites`
--

CREATE TABLE IF NOT EXISTS `fay_favourites` (
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_files`
--

CREATE TABLE IF NOT EXISTS `fay_files` (
  `id` int(10) unsigned NOT NULL,
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
  `qiniu` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=1013 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_files`
--

INSERT INTO `fay_files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`, `downloads`, `type`, `qiniu`) VALUES
(1000, '4Cr42', '.png', 23624, 'image/png', './uploads/posts/2015/03/', 'img-right.png', 1, 228, 218, 1426864800, 10000, 0, 1, 0),
(1001, 'CUVC3', '.png', 76330, 'image/png', './uploads/posts/2015/03/', 'product5.png', 1, 378, 378, 1426864812, 10000, 0, 1, 0),
(1002, 'MR0ss', '.png', 76330, 'image/png', './uploads/posts/2015/03/', 'product5.png', 1, 378, 378, 1427004909, 10000, 0, 1, 0),
(1003, 'A0Ats', '.png', 58103, 'image/png', './uploads/posts/2015/03/', 'product2.png', 1, 378, 378, 1427004919, 10000, 0, 1, 0),
(1004, 'XlC06', '.png', 59447, 'image/png', './uploads/posts/2015/03/', 'product9.png', 1, 378, 378, 1427004933, 10000, 0, 1, 0),
(1005, 'z94AD', '.png', 69458, 'image/png', './uploads/posts/2015/03/', 'product8.png', 1, 378, 378, 1427004944, 10000, 0, 1, 0),
(1006, '1yRek', '.png', 49850, 'image/png', './uploads/posts/2015/03/', 'product1.png', 1, 378, 378, 1427004956, 10000, 0, 1, 0),
(1007, 'NwVUT', '.png', 58103, 'image/png', './uploads/posts/2015/03/', 'product2.png', 1, 378, 378, 1427004968, 10000, 0, 1, 0),
(1008, '44rmd', '.png', 89569, 'image/png', './uploads/posts/2015/03/', 'product4.png', 1, 378, 378, 1427004985, 10000, 0, 1, 0),
(1009, 'yW1D5', '.png', 73875, 'image/png', './uploads/posts/2015/03/', 'product6.png', 1, 378, 378, 1427004996, 10000, 0, 1, 0),
(1010, 'Qi5fv', '.jpg', 25040, 'image/jpeg', './uploads/posts/2015/03/', '2013062910273077.jpg', 1, 500, 329, 1427029563, 10000, 0, 1, 0),
(1011, 'F421m', '.jpg', 44415, 'image/jpeg', './uploads/posts/2015/03/', '20130309134540337.jpg', 1, 500, 331, 1427029615, 10000, 0, 1, 0),
(1012, 'tFTiF', '.jpg', 66235, 'image/jpeg', './uploads/posts/2015/03/', '20130309134547825.jpg', 1, 500, 330, 1427029621, 10000, 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `fay_followers`
--

CREATE TABLE IF NOT EXISTS `fay_followers` (
  `user_id` int(10) unsigned NOT NULL,
  `follower` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_goods`
--

CREATE TABLE IF NOT EXISTS `fay_goods` (
  `id` mediumint(8) unsigned NOT NULL,
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
  `seo_description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_goods_files`
--

CREATE TABLE IF NOT EXISTS `fay_goods_files` (
  `id` int(10) unsigned NOT NULL,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_goods_prop_values`
--

CREATE TABLE IF NOT EXISTS `fay_goods_prop_values` (
  `id` int(10) unsigned NOT NULL,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_value_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_value_alias` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_goods_skus`
--

CREATE TABLE IF NOT EXISTS `fay_goods_skus` (
  `id` int(10) unsigned NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `prop_value_ids` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tsces` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_item_props`
--

CREATE TABLE IF NOT EXISTS `fay_item_props` (
  `id` mediumint(8) unsigned NOT NULL,
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
  `multi` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_item_prop_values`
--

CREATE TABLE IF NOT EXISTS `fay_item_prop_values` (
  `id` int(10) unsigned NOT NULL,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_keywords`
--

CREATE TABLE IF NOT EXISTS `fay_keywords` (
  `id` int(10) unsigned NOT NULL,
  `keyword` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(500) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_likes`
--

CREATE TABLE IF NOT EXISTS `fay_likes` (
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_links`
--

CREATE TABLE IF NOT EXISTS `fay_links` (
  `id` mediumint(8) unsigned NOT NULL,
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
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_links`
--

INSERT INTO `fay_links` (`id`, `title`, `description`, `url`, `visiable`, `user_id`, `target`, `create_time`, `last_modified_time`, `sort`, `logo`, `cat_id`) VALUES
(1, '绍兴文理学院元培学院', '', 'http://ypcol.com', 1, 10000, '_blank', 1426599677, 1426599677, 100, 0, 0),
(2, 'whis', '', 'http://whis.fayfox.com', 1, 10000, '_blank', 1426599961, 1426599961, 100, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `fay_logs`
--

CREATE TABLE IF NOT EXISTS `fay_logs` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_logs`
--

INSERT INTO `fay_logs` (`id`, `user_id`, `type`, `code`, `data`, `create_date`, `create_time`, `ip_int`, `user_agent`) VALUES
(1, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-16', 1426437494, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(2, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-16', 1426512906, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(3, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-17', 1426555635, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(4, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-17', 1426594939, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(5, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-17', 1426595239, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(6, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-17', 1426598613, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(7, 0, 0, 'admin:action:login.success', '{"fmac":"a51c5fa11c6de014e60a87026cbfcc4d"}', '2015-03-17', 1426604907, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'),
(8, 0, 0, 'admin:action:login.success', '{"fmac":"6181fdee1fc9b33344a827709aa9171e"}', '2015-03-19', 1426775093, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36'),
(9, 0, 0, 'admin:action:login.success', '{"fmac":"fd675612f11abe2d85d1e5e329132989"}', '2015-03-19', 1426777221, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36'),
(10, 0, 0, 'admin:action:login.success', '{"fmac":"fd675612f11abe2d85d1e5e329132989"}', '2015-03-22', 1427004877, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36'),
(11, 0, 0, 'admin:action:login.success', '{"fmac":"fd675612f11abe2d85d1e5e329132989"}', '2015-03-22', 1427027966, 2130706433, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36');

-- --------------------------------------------------------

--
-- 表的结构 `fay_menus`
--

CREATE TABLE IF NOT EXISTS `fay_menus` (
  `id` smallint(5) unsigned NOT NULL,
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `left_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right_value` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `sub_title` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `target` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_messages`
--

CREATE TABLE IF NOT EXISTS `fay_messages` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `target` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `root` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_terminal` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_notifications`
--

CREATE TABLE IF NOT EXISTS `fay_notifications` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `from` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active_key` varchar(32) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `publish_time` int(10) unsigned NOT NULL DEFAULT '0',
  `validity_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_options`
--

CREATE TABLE IF NOT EXISTS `fay_options` (
  `id` int(10) unsigned NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` text NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_system` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_options`
--

INSERT INTO `fay_options` (`id`, `option_name`, `option_value`, `description`, `create_time`, `last_modified_time`, `is_system`) VALUES
(1, 'sitename', 'Tea and Milk', '', 1426437489, 1426866732, 0),
(2, 'copyright', '', '', 1426512932, 1426866732, 0),
(3, 'beian', '', '', 1426512932, 1426866732, 0),
(4, 'phone', '0575-88888888', '', 1426512932, 1426866732, 0),
(5, 'fax', '', '', 1426512932, 1426866732, 0),
(6, 'email', '18888888888@qq.com', '', 1426512932, 1426866732, 0),
(7, 'address', '浙江省绍兴市城南大道900号', '', 1426512932, 1426866732, 0),
(8, 'seo_index_title', 'Tea and Milk', '', 1426512932, 1426866732, 0),
(9, 'seo_index_keywords', 'Tea and Milk', '', 1426512932, 1426866732, 0),
(10, 'seo_index_description', 'Tea and Milk', '', 1426512932, 1426866732, 0);

-- --------------------------------------------------------

--
-- 表的结构 `fay_pages`
--

CREATE TABLE IF NOT EXISTS `fay_pages` (
  `id` int(10) unsigned NOT NULL,
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
  `abstract` varchar(500) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=1007 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_pages`
--

INSERT INTO `fay_pages` (`id`, `title`, `alias`, `content`, `author`, `create_time`, `last_modified_time`, `status`, `deleted`, `thumbnail`, `comments`, `views`, `sort`, `seo_title`, `seo_keywords`, `seo_description`, `abstract`) VALUES
(1003, '关于我们', 'about', '<p style="margin-top: 8px; margin-bottom: 0px; list-style-type: none; line-height: 22.2000007629395px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">深圳市性习新天然餐饮管理有限公司（Shenzhen new natural restaurant management co., ltd.）是一家专业的<strong style="list-style-type: none;">珍珠奶茶、咖啡、休闲饮品、小吃的原料供应商</strong>，集<strong style="list-style-type: none;">原料批发</strong>，<strong style="list-style-type: none;">技术培训</strong>，<strong style="list-style-type: none;">开店辅导，品牌策划，</strong>于一体的全资民营企业。公司秉承&ldquo;以质量求生存，以品种图发展&rdquo;的方针，不断提高产品质量，完善产品品种，满足市场需求。现我们企业已经有包括：<strong style="list-style-type: none;">珍珠</strong>，<strong style="list-style-type: none;">植脂末</strong>，<strong style="list-style-type: none;">果糖</strong>，<strong style="list-style-type: none;">果粉</strong>，<strong style="list-style-type: none;">果汁</strong><strong style="list-style-type: none;">，</strong><strong style="list-style-type: none;">果酱</strong><strong style="list-style-type: none;">，果泥，果粒，果露，水果茶，</strong><strong style="list-style-type: none;">咖啡</strong><strong style="list-style-type: none;">，</strong><strong style="list-style-type: none;">红茶</strong><strong style="list-style-type: none;">，</strong><strong style="list-style-type: none;">绿茶</strong><strong style="list-style-type: none;">，</strong><strong style="list-style-type: none;">乌龙茶</strong><strong style="list-style-type: none;">，台湾小吃，珍</strong><strong style="list-style-type: none;">珠奶茶设，小吃设备，奶茶器皿</strong>等千余种产品。</p>\r\n\r\n<p style="margin-top: 8px; margin-bottom: 0px; list-style-type: none; line-height: 22.2000007629395px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; 深圳市性习新天然餐饮管理有限公司成立于2010年,前身为新天然食品经营部，经过几年不断发展，现已从单一的销售网点，发展到业务区域分布全国，产品从珍珠奶茶到休闲饮品小吃等全方位发展，产品在国内市场上享有良好的信誉，深受客户青睐，我们始终与客户保持着良好的合作伙伴关系。本公司最大特点是满足客户的所有需求，以珍珠奶茶原料，珍珠奶茶设备，珍珠奶茶技术培训相结合，并且提供最专业的餐饮企业管理服务，企业策划服务，信息咨询等特色服务，让客户无任何后顾之忧。我们还拥有强大的专业技术团队和市场调研组合更是保证了公司一直处于饮品市场领先地位。 深厚的文化底蕴是新天然人创业的坚实基础，品质至尊、专业精湛、服务至上是新天然人崇尚的经营理念。</p>\r\n\r\n<p style="margin-top: 8px; margin-bottom: 0px; list-style-type: none; line-height: 22.2000007629395px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;新天然，天然的好滋味将以纯正的口感、四海咸宜的品位成为业内饮品的代名词。&ldquo;新天然&rdquo;人以&ldquo;诚信为本，质量创优、精益求精&rdquo;的发展宗旨。致力于打造出一流的好喝的<strong style="list-style-type: none;">珍珠奶茶</strong>，提供最优质的珍珠奶茶原料、最好的珍珠奶茶设备、最专业的珍珠奶茶技术培训、最完善的餐饮企业管理服务，不断迈上新的台阶。</p>\r\n', 10000, 1426778531, 0, 1, 0, 0, 0, 0, 100, '', '', '', ''),
(1004, '技术培训', 'train', '<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: center; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: large;"><strong style="list-style-type: none;">珍珠奶茶培训</strong></span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: medium;">一、<strong style="list-style-type: none;">珍珠奶茶介绍</strong></span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="list-style-type: none; font-size: small;"><strong style="list-style-type: none;">&nbsp;珍珠奶茶店</strong>是目前最流行的创业项目之一。通常以<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">奶茶</a>作为主打，加上水果花茶、雪泡、沙冰、刨冰、奶昔等系列，组合出丰富多彩的茶品种。奶茶最早在台湾和广东流行，一经推出即风靡大陆各地。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong style="list-style-type: none;">奶茶店加盟</strong>蓬勃发展的原因，除了拥有庞大的顾客群外，还有一个非常重要的原因就是行业进入门槛低，奶茶做法简单且利润丰厚。另外，该项目具有前期投入小、利润高、回本快、适合发展<strong style="list-style-type: none;">连锁加盟</strong>等特点，所以近年来奶茶店得以快速扩张。<strong style="list-style-type: none;">奶茶培训</strong>行业也得以发展。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 投资一家奶茶店，通常的投资额在3万至10万元人民币左右。此外，奶茶是饮料，相对于其他行业，现金流很快，风险较小，其主要风险来自店铺位置、品种、季节三方面。除去材料成本、租金、人工工资、水电费等，如果消费群体稳定，营业额将相当可观。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 然而，过于快速的扩张往往会导致无序竞争。奶茶店进入门槛低，所以奶茶店遍地开花。但能否坚持下来成为奶茶店面临的最严峻的问题。高质量的饮品和长期以来建立的稳定的客户群是奶茶店竞争力的根本。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 我们专注于奶茶店的开店辅导，传授目前市场主流奶茶品牌的产品制作技术，确保你学到最新的奶茶技术。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">奶茶培训</a>针对开店全过程，从产品制作、设备配置、原材料选择和购买、店铺租赁、装修设计、VI制作等各方面给予辅导，让您放心开店。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 参与我们的培训，你就能掌握全套开店技术，无需加盟费，开出有竞争力的奶茶店。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;"><strong style="list-style-type: none;">特点</strong>：根据客户量身打造，根据客户所需要制定最适合技术方案，以及后期技术支持。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;"><strong style="list-style-type: none;">技术</strong>：我们公司有专业技术研发团队，技术非常过硬。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;"><strong style="list-style-type: none;">追求</strong>：为我们客户打造最美口感的<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">珍珠奶茶</a>。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;"><strong style="list-style-type: none;">二</strong>、<strong style="list-style-type: none;">珍珠奶茶培训费用</strong></span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;"><strong style="list-style-type: none;">根据菜单订制不同培训费用有所不同，详细情况请电话咨询！</strong></span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">不限制学习时间，随到随学，学会为止，包教包会。一般4-5天左右，根据个人掌握情况而定</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><strong style="list-style-type: none;"><span style="list-style-type: none; font-size: small;">三</span><span style="list-style-type: none; font-size: small;">、&nbsp;设备配置</span></strong><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">序号 名称 规格 单价（元） 用途&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">1 电磁炉 个 100-400 煮茶、煮珍珠用&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">2&nbsp;<a href="http://www.5ufood.com/product.php?name=jzc" style="list-style-type: none; color: rgb(77, 139, 17);">电子称</a>&nbsp;台 100-300元 计量&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">3&nbsp;<a href="http://www.5ufood.com/product.php?name=yffkj" style="list-style-type: none; color: rgb(77, 139, 17);">封口机</a>&nbsp;台 220-480 封奶茶杯&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">4 冰柜 台 1200-2500 放冰块、冰水&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">5 饮水机 台 300-800 供应开水&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">6 不锈铁奶茶桶（22） 个 30 大批量泡茶、装茶&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">7 不锈钢奶茶桶（22） 个 68 大批量泡茶、装茶&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">8 量勺 个 3/5 量取<a href="http://www.5ufood.com/category/qizimo/" style="list-style-type: none; color: rgb(77, 139, 17);">奶精</a>粉等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">9 吧匙（小/大） 个 8/10 搅拌、量取<a href="http://www.5ufood.com/category/gouji/" style="list-style-type: none; color: rgb(77, 139, 17);">果汁</a>用&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">10&nbsp;<a href="http://www.5ufood.com/product.php?name=xkb" style="list-style-type: none; color: rgb(77, 139, 17);">雪克杯</a>（250/300/500） 个 15/20/25 制作花式冰奶茶</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<strong style="list-style-type: none;"><span style="list-style-type: none; font-size: small;">四、 性习新天然培训优势</span></strong><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">1 品质取胜：性习新天然培训中心的奶茶以完美而百喝不腻的口感赢得市场，我们力争学员开一家成功一家，所有来学习的学员均可以先品尝口味，再选择学习。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">2 加盟的品质，培训的价格：性习新天然培训中心的培训以一流的品质低廉的价格，响应政府的号召扶持中小创业者，帮助学员成功创业。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">3 学习过程理论结合实践：性习新天然培训中心的培训项目以实践为主，以学好为准。所学项目都要经过实际操作考核。直到学会为止。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">4 开店指导：学习完毕，免费指导设计菜单、店内装修和招牌设计。传授宣传和销售绝招，成本控制方法，财务管理技巧，星级服务标准和店内员工的管理规划。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">5 免费技术升级：我们有强大的技术开发队伍，所有技术都会随着市场的变化而不断改进升级，培训学员在学习后的3年内免费享受技术升级。</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">6 材料设备：学习期间珍珠奶茶材料，珍珠<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">奶茶设备</a>，珍珠奶茶器血由我们公司提供，不需要自己购买。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<strong style="list-style-type: none;"><span style="list-style-type: none; font-size: small;">五、奶茶技术培训内容</span></strong><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">1 基础</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">奶茶 奶茶制作的工具和必备原材，奶茶制作秘决，茶水煮法，珍珠煮法，西米煮法，布丁煮法。原味奶茶制作，果味奶茶制作，珍珠奶茶制作。&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">2 特色</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">时尚</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">奶茶 招牌奶茶●丝袜奶茶●香港奶茶●伯爵奶茶●手摇功夫奶茶●<a href="http://www.5ufood.com/category/kafei/" style="list-style-type: none; color: rgb(77, 139, 17);">咖啡</a>奶茶●鸳鸯奶茶●麦片奶茶●皇家奶茶●双拼奶茶●抹茶拿铁●抹茶奶绿●普洱奶茶●黑糖奶茶●焦糖布丁奶茶●姜片奶茶●美禄可可●QQ玄米冰奶茶●仙草奶茶●玄米可可●太妃奶茶●桂圆红枣奶茶●鸡蛋布丁奶茶●仙草奶茶●龟苓膏奶茶●小麦胚芽奶茶●福尔摩斯奶茶●乌龙奶茶●阿萨姆奶茶●巧克力奶茶●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp;3</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 十二星座奶茶</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 水瓶座-自由向往●双鱼座-罗曼蒂克●牧羊座-紧张大师●金牛座-刻苦耐劳●双子座-天生赢家●巨蟹座-温柔婉约●狮子座-领袖魅力●处女座-魔力塑身●天称座-青春活力●天蝎座-神秘达人●射手座-乐知天命●魔羯座-悠闲浪漫&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">4&nbsp; 花式奶茶冰</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 椰香果肉奶茶冰●威尼斯海岸奶茶冰●爱尔兰奶茶冰●欧式奶茶冰●摩卡可可奶茶冰●皇室奶茶冰●红衣少女奶茶冰●绿野仙踪奶茶冰●情缘奶茶冰●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">5 特色</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">茶饮 ●柠檬红茶●柠檬绿茶●蜂蜜红茶●百香果红茶●芦荟果粒茶●手摇泡沫红茶●手摇泡沫绿茶●波霸红茶●薄荷绿茶●柳橙红茶●QQ红茶●玫瑰元气茶●蜂蜜柚子茶●王牌玫瑰红茶●芒果果粒茶●清火菊花茶●阿萨姆红茶●冰爽玄米茶●一千零一夜●清香茉莉茶●香浓芒果红茶●红粉佳人●立顿茶饮●普洱茶●疯狂黑加仑等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">6</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 特色鲜茶爽</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 柳橙茶爽●香浓芒果茶爽●柠檬茶爽●梦露茶爽●水蜜桃茶爽●QQ玄米茶爽●菠萝茶爽●柚子茶爽●薄荷茶爽●百香果茶爽●草莓茶爽●情人果茶爽●葡萄茶爽●青苹果茶爽●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">7</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 果缤纷系列</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 柳橙果缤纷●凤梨果缤纷●蓝莓果缤纷●草莓果缤纷●暖身生姜茶●薄荷芦荟果缤纷●双果果缤纷●柚子果缤纷●乌梅果缤纷●情人果缤纷●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">8</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 柠檬优V系列</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">&nbsp; 冰柠茶●冰柠乐●冰柠七●冰柠宾●冰爽柠檬水●冰金桔茶●蜂蜜金桔红茶●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">9 西米露系列 ●芒果西米露●什果西米露●荔枝西米露●龟苓膏西米露●青苹果西米露●椰子西米露●香芋西米露●哈密瓜西米露●木瓜西米露●鲜果西米露●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">10 果冻</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">布丁 果味果冻●果味布丁●烧仙草●红豆双皮奶●<a href="http://www.5ufood.com/category/yeguo/" style="list-style-type: none; color: rgb(77, 139, 17);">椰果</a>双皮奶●芒果龟苓膏●红豆龟苓膏●酸奶龟苓膏●草莓双皮奶●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">11 水果捞 ●芒果西米捞●椰果西米捞●芒果珍珠捞●杂果凉粉捞●哈密瓜水果捞●什锦水果捞●西瓜水果捞●布丁烧水果捞●珍珠西米杂果捞●等&nbsp;</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">12 咖啡</span><br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<span style="list-style-type: none; font-size: small;">系列 咖啡文化、咖啡豆的选用、咖啡器具使用，现磨咖啡、蓝山咖啡、焦糖玛奇朵、珍珠拿铁冰咖啡、牛奶冰咖啡、卡布基诺冰咖啡、啤酒咖啡等制作</span></p>\r\n', 10000, 1427034115, 0, 1, 0, 0, 0, 0, 100, '', '', '', ''),
(1005, '人才招聘', 'employ', '<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">&nbsp; &nbsp; &nbsp; 深圳市性习新天然餐饮管理有限公司，因业力扩大需要，诚招各类人才。欢迎广大朋友来加盟我公司。现招聘如下职务：</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">客服：包吃住，工资面议</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">要求：20岁以上，男女不限，普通话流利，形象气质佳，责任心强，懂办公软件，沟通能力佳，具有亲和力，做事主动有责任感，有相关工作经验者优先</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">司机：包吃住，工资面议</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">要求：25-40岁，男性，最少1年驾驶经验，具有深户担保；身体健康，勤快能吃苦，有良好的职业道德和敬业精神；熟悉深圳路况。</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">仓库理货员：包吃住，工资面议</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">要求：对数字敏感，思维敏捷，有高度责任感；工作细心，吃苦耐劳，认真负责，有较好的沟通能力；具有较强的团队合作意识</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">送货跟车员：包吃住，工资面议</span></p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-size: small;">要求：主要负责短途跟车，路上保证货物安全，确保货物及时、安全、准确送到客户手中，对好单据，收取账款，并及时回公司交回。身体健康，有责任心，能吃苦耐劳，反应灵活，无不良记录。</span></p>\r\n', 10000, 1427034152, 0, 1, 0, 0, 0, 0, 100, '', '', '', ''),
(1006, '联系我们', 'contact', '<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><strong style="list-style-type: none;">地址</strong>：深圳市龙岗区坂田街道布龙路445号格泰隆工业园Ｂ栋一楼<br style="margin: 0px; padding: 0px; list-style-type: none;" />\r\n<strong style="list-style-type: none;">咨询电话</strong>：0755-25920268&nbsp; 25920118</p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);"><strong style="list-style-type: none;">联系人</strong>：江建友（销售代表）：&nbsp;&nbsp;&nbsp;手机：13316868268&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;卢&nbsp; 丹（销售代表）&nbsp;：&nbsp; 手机：13316811296&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;伍培华（销售代表）：&nbsp; 手机：18923863408&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 黄 炜（网络客服）&nbsp;&nbsp;： 手机：18938666181&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; QQ:13316884611&nbsp; 旺旺：lmsh6868</p>\r\n\r\n<p style="margin-bottom: 0px; padding-top: 8px; padding-bottom: 8px; list-style-type: none; font-size: 14px; line-height: 26.6000003814697px; text-align: justify; color: rgb(51, 51, 51); font-family: Verdana, Arial, Helvetica, sans-serif; background-color: rgb(255, 255, 255);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 微信公众号：sz_xxxtr &nbsp;</p>\r\n', 10000, 1427034198, 0, 1, 0, 0, 0, 0, 100, '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `fay_page_categories`
--

CREATE TABLE IF NOT EXISTS `fay_page_categories` (
  `page_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_posts`
--

CREATE TABLE IF NOT EXISTS `fay_posts` (
  `id` int(10) unsigned NOT NULL,
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
  `seo_description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=1025 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_posts`
--

INSERT INTO `fay_posts` (`id`, `cat_id`, `title`, `alias`, `content`, `content_type`, `create_time`, `last_modified_time`, `publish_date`, `publish_time`, `last_view_time`, `user_id`, `is_top`, `status`, `deleted`, `thumbnail`, `abstract`, `comments`, `sort`, `views`, `likes`, `seo_title`, `seo_keywords`, `seo_description`) VALUES
(1000, 10000, '111', '', '<p>11</p>\r\n', 0, 1426517808, 1426517808, '2015-03-16', 1426517808, 1426594930, 10000, 0, 1, 0, 0, '', 0, 100, 1, 0, '', '', ''),
(1001, 10000, 's''d''fs''d', '', '<p>发水电费</p>\r\n', 0, 1426517815, 1426517815, '2015-03-16', 1426517815, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1002, 10000, '水电费', '', '<p>水电费</p>\r\n', 0, 1426517818, 1426517818, '2015-03-16', 1426517818, 1426604562, 10000, 0, 1, 0, 0, '', 0, 100, 8, 0, '', '', ''),
(1003, 10002, 'hot', '', '<p>hot</p>\r\n', 0, 1426599523, 1426599523, '2015-03-17', 1426599523, 1426599970, 10000, 0, 1, 0, 0, '', 0, 100, 2, 0, '', '', ''),
(1004, 10000, '2222', '', '<p><span style="color: rgb(85, 85, 85); font-size: 16.7999992370605px; letter-spacing: 3px; line-height: 25px; background-color: rgb(245, 251, 255);">15日是国际消费者权益保护日，为深入宣传《中华人民共和国消费者权益保护法》，让广大市民更多的了解消费维权知识，并切实帮助我市消费者解决日常生活中的实际难题，我院大学生法律援助中心参加了由绍兴市市场监督管理局、绍兴市消费者权益保护委员会等单位主办的以&ldquo;携手共治，畅想消费&rdquo;为主题的3&bull;15消法宣传咨询活动。</span></p>\r\n', 0, 1426604941, 1426604963, '2015-03-17', 1426604941, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1005, 10000, 'reg', '', '<p><span style="color: rgb(85, 85, 85); font-size: 16.7999992370605px; letter-spacing: 3px; line-height: 25px; background-color: rgb(245, 251, 255);">15日是国际消费者权益保护日，为深入宣传《中华人民共和国消费者权益保护法》，让广大市民更多的了解消费维权知识，并切实帮助我市消费者解决日常生活中的实际难题，我院大学生法律援助中心参加了由绍兴市市场监督管理局、绍兴市消费者权益保护委员会等单位主办的以&ldquo;携手共治，畅想消费&rdquo;为主题的3&bull;15消法宣传咨询活动。</span></p>\r\n', 0, 1426604970, 1426604970, '2015-03-17', 1426604970, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1006, 10000, 'fdh h', '', '<p>多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈</p>\r\n', 0, 1426604993, 1426604993, '2015-03-17', 1426604993, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1007, 10000, '就是妇女节', '', '<p>多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈</p>\r\n', 0, 1426605004, 1426605004, '2015-03-17', 1426605004, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1008, 10000, '就是部分', '', '<p>vdggv</p>\r\n', 0, 1426605010, 1426605010, '2015-03-17', 1426605010, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1009, 10000, 'djkbiu但是从南京', '', '<p>多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈</p>\r\n', 0, 1426605016, 1426605025, '2015-03-17', 1426605016, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1010, 10000, '从不恶报', '', '<p>多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈多少分 个阿四弟哦快点发你好热你哈是的你还哦符合非双方将份额为方便额外个办法是的那就看你份额无奈</p>\r\n', 0, 1426605030, 1426605030, '2015-03-17', 1426605030, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1011, 10000, '奋斗姐夫呢', '', '<p>发jewbfuief</p>\r\n', 0, 1426605037, 1426605037, '2015-03-17', 1426605037, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1012, 10005, '通知', '', '<p>奶茶卖疯了</p>\r\n', 0, 1426778958, 1426778958, '2015-03-19', 1426778958, 0, 10000, 0, 1, 0, 0, '', 0, 100, 0, 0, '', '', ''),
(1013, 10005, '开奶茶店需要多少钱?', '', '<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 2em; font-family: ''Microsoft YaHei''; text-indent: 2em; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">很多准备开奶店或刚开奶店的朋友都会在网上搜索&ldquo;<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">奶茶加盟</a>需要多少钱?&rdquo;，&ldquo;<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">开奶茶店需要多少钱</a>?&rdquo;，&ldquo;开<a href="http://www.5ufood.com/" style="list-style-type: none; color: rgb(77, 139, 17);">奶茶</a>装修要多少钱?&rdquo;等等一系列相关问题，下在我们性习新天然根据我们自己在奶茶行业从业多年的经验，一起帮大家客观的问题一下这个问题。</span></p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 24px; text-align: justify; font-family: ''Microsoft YaHei''; background-color: rgb(255, 255, 255);">&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 24px; text-align: center; font-family: ''Microsoft YaHei''; background-color: rgb(255, 255, 255);"><img alt="" border="0" height="317" hspace="0" src="http://www.xxxtr.cn/include/ueditor/php/upload/83911425644674.jpg" style="margin: 0px; padding: 0px; list-style-type: none; text-align: left; width: 400px; height: 317px;" title="dm.jpg" width="400" /></p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 2em; font-family: ''Microsoft YaHei''; text-indent: 2em; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">首先每一个加盟商的地理位置，还有面积都不一样，所以不能有统一的回答，今天就以这一家奶茶店为例，让大家参考一下</span><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">如：店面宽</span><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">2.5米，深度10米，在一校园内; 吧台在门口是肯定的，由于店面宽度比较小，深度相多过剩，可以考虑门内5米左右设吧台。但是灯光效果要到位，不要让人有种拥挤的感觉。尽量不要开台，</span><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">这个面积最多开3张，除了拥挤还是拥挤，而且学生容易因为争台子吵闹。装修要以粉色为主，带有时代感的曲线与字体，这样比较醒目而且受年轻人欢迎。</span></p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 24px; text-align: justify; font-family: ''Microsoft YaHei''; background-color: rgb(255, 255, 255);">&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 2em; font-family: ''Microsoft YaHei''; text-indent: 2em; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">以外买为主，人多时叫号供应。口味尽量多，价钱要从低价到高价都能胜任。学生喜欢个性化，可以考虑自定义口味的奶茶选择，按价加料。甚至可以办会员卡，会员可以来电提前准备好货，可以避免放学时过多人排队浪费时间等等。</span></p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 24px; text-align: justify; font-family: ''Microsoft YaHei''; background-color: rgb(255, 255, 255);">&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 2em; font-family: ''Microsoft YaHei''; text-indent: 2em; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">每星期或者每天随机或者规律循环指定某口味特价，这样可以让低层消费者可以品尝多种口味，产生广告效应。</span></p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 24px; text-align: justify; font-family: ''Microsoft YaHei''; background-color: rgb(255, 255, 255);">&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 2em; font-family: ''Microsoft YaHei''; text-indent: 2em; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">如果人流大，这样买奶茶的人可能会堵塞门口。如果人流一般，这样的设计可行。但是2.5米的内厅左右墙壁都安装吧台，坐满了非常拥挤。如果让我设计，我会只安装一边。虽然位置少了点，但是至少有走动的空间。</span></p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 24px; text-align: justify; font-family: ''Microsoft YaHei''; background-color: rgb(255, 255, 255);">&nbsp;</p>\r\n\r\n<p style="margin-bottom: 0px; list-style-type: none; font-size: 14px; line-height: 2em; font-family: ''Microsoft YaHei''; text-indent: 2em; background-color: rgb(255, 255, 255);"><span style="list-style-type: none; font-family: 微软雅黑, ''Microsoft YaHei'';">不管是恋之味奶茶加盟店，台吧是不可少的一部分，房屋装修及大小，可以根据实际情况再定做，一般情况下，面积不到十平米的加盟店，以外卖的形式经营;而二十到三十平米的加盟店，以小吧形式经营，可以设置简单的桌椅;而三十平米以上的。</span></p>\r\n', 0, 1426779312, 1426779312, '2015-03-19', 1426779312, 1427007466, 10000, 0, 1, 0, 0, '', 0, 100, 1, 0, '', '', ''),
(1014, 10006, '奶茶1', '', '<p>奶茶1</p>\r\n\r\n<p><img alt="" src="http://milk/uploads/posts/2015/03/CUVC3.png" style="width: 378px; height: 378px;" /></p>\r\n', 0, 1426864815, 1426864815, '2015-03-20', 1426864815, 0, 10000, 0, 1, 0, 1000, '', 0, 100, 0, 0, '', '', ''),
(1015, 10006, 'naicha1', '', '<p>你的家吃饭纳斯哦那是付出是的烦恼可是当你覅是你</p>\r\n', 0, 1427004910, 1427004910, '2015-03-22', 1427004910, 0, 10000, 0, 1, 0, 1002, '', 0, 100, 0, 0, '', '', ''),
(1016, 10006, 'uigyug', '', '<p>guhiu</p>\r\n', 0, 1427004922, 1427004922, '2015-03-22', 1427004922, 0, 10000, 0, 1, 0, 1003, '', 0, 100, 0, 0, '', '', ''),
(1017, 10006, 'safbiuchsifu', '', '<p>dsiugfusnsjdfv是的愤怒sndfiu</p>\r\n', 0, 1427004934, 1427004934, '2015-03-22', 1427004934, 1427006288, 10000, 0, 1, 0, 1004, '', 0, 100, 1, 0, '', '', ''),
(1018, 10006, '发错阿城我的钱', '', '<p>东方擦封测</p>\r\n', 0, 1427004944, 1427004944, '2015-03-22', 1427004944, 1427010750, 10000, 0, 1, 0, 1005, '', 0, 100, 2, 0, '', '', ''),
(1019, 10006, '阿迪', '', '<p>大阿斯顿</p>\r\n', 0, 1427004957, 1427004957, '2015-03-22', 1427004957, 1427028942, 10000, 0, 1, 0, 1006, '', 0, 100, 3, 0, '', '', ''),
(1020, 10006, '奋斗擦', '', '<p>阿飞擦擦</p>\r\n', 0, 1427004969, 1427004969, '2015-03-22', 1427004969, 1427006279, 10000, 0, 1, 0, 1007, '', 0, 100, 1, 0, '', '', ''),
(1021, 10006, '你在醋啊额', '', '<p>你的经适房腐败</p>\r\n', 0, 1427004985, 1427004985, '2015-03-22', 1427004985, 0, 10000, 0, 1, 0, 1008, '', 0, 100, 0, 0, '', '', ''),
(1022, 10006, '打击你啊的奶茶', '', '<p>啊打算阿迪</p>\r\n', 0, 1427004996, 1427004996, '2015-03-22', 1427004996, 0, 10000, 0, 1, 0, 1009, '', 0, 100, 0, 0, '', '', ''),
(1023, 10006, 'jbj', '', '<p>vuuy</p>\r\n', 0, 1427006840, 1427029564, '2015-03-22', 1427006840, 1427007459, 10000, 0, 1, 0, 1010, '', 0, 100, 8, 0, '', '', ''),
(1024, 10006, '不错的奶茶', '', '<p><img alt="" src="http://milk/uploads/posts/2015/03/F421m.jpg" style="width: 500px; height: 331px;" /></p>\r\n', 0, 1427029621, 1427029621, '2015-03-22', 1427029621, 1427033152, 10000, 0, 1, 0, 1012, '', 0, 100, 23, 0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `fay_posts_tags`
--

CREATE TABLE IF NOT EXISTS `fay_posts_tags` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_post_categories`
--

CREATE TABLE IF NOT EXISTS `fay_post_categories` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(9) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_post_files`
--

CREATE TABLE IF NOT EXISTS `fay_post_files` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `is_image` tinyint(1) NOT NULL DEFAULT '1',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_post_prop_int`
--

CREATE TABLE IF NOT EXISTS `fay_post_prop_int` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_post_prop_text`
--

CREATE TABLE IF NOT EXISTS `fay_post_prop_text` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_post_prop_varchar`
--

CREATE TABLE IF NOT EXISTS `fay_post_prop_varchar` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_profile_int`
--

CREATE TABLE IF NOT EXISTS `fay_profile_int` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_profile_text`
--

CREATE TABLE IF NOT EXISTS `fay_profile_text` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_profile_varchar`
--

CREATE TABLE IF NOT EXISTS `fay_profile_varchar` (
  `user_id` int(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_props`
--

CREATE TABLE IF NOT EXISTS `fay_props` (
  `id` mediumint(8) unsigned NOT NULL,
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `element` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_prop_values`
--

CREATE TABLE IF NOT EXISTS `fay_prop_values` (
  `id` int(10) unsigned NOT NULL,
  `refer` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prop_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_regions`
--

CREATE TABLE IF NOT EXISTS `fay_regions` (
  `id` smallint(5) unsigned NOT NULL,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(120) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=MyISAM AUTO_INCREMENT=3409 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_regions`
--

INSERT INTO `fay_regions` (`id`, `parent_id`, `name`, `type`) VALUES
(1, 0, '中国', 0),
(2, 1, '北京', 1),
(3, 1, '安徽', 1),
(4, 1, '福建', 1),
(5, 1, '甘肃', 1),
(6, 1, '广东', 1),
(7, 1, '广西', 1),
(8, 1, '贵州', 1),
(9, 1, '海南', 1),
(10, 1, '河北', 1),
(11, 1, '河南', 1),
(12, 1, '黑龙江', 1),
(13, 1, '湖北', 1),
(14, 1, '湖南', 1),
(15, 1, '吉林', 1),
(16, 1, '江苏', 1),
(17, 1, '江西', 1),
(18, 1, '辽宁', 1),
(19, 1, '内蒙古', 1),
(20, 1, '宁夏', 1),
(21, 1, '青海', 1),
(22, 1, '山东', 1),
(23, 1, '山西', 1),
(24, 1, '陕西', 1),
(25, 1, '上海', 1),
(26, 1, '四川', 1),
(27, 1, '天津', 1),
(28, 1, '西藏', 1),
(29, 1, '新疆', 1),
(30, 1, '云南', 1),
(31, 1, '浙江', 1),
(32, 1, '重庆', 1),
(33, 1, '香港', 1),
(34, 1, '澳门', 1),
(35, 1, '台湾', 1),
(36, 3, '安庆', 2),
(37, 3, '蚌埠', 2),
(38, 3, '巢湖', 2),
(39, 3, '池州', 2),
(40, 3, '滁州', 2),
(41, 3, '阜阳', 2),
(42, 3, '淮北', 2),
(43, 3, '淮南', 2),
(44, 3, '黄山', 2),
(45, 3, '六安', 2),
(46, 3, '马鞍山', 2),
(47, 3, '宿州', 2),
(48, 3, '铜陵', 2),
(49, 3, '芜湖', 2),
(50, 3, '宣城', 2),
(51, 3, '亳州', 2),
(52, 2, '北京', 2),
(53, 4, '福州', 2),
(54, 4, '龙岩', 2),
(55, 4, '南平', 2),
(56, 4, '宁德', 2),
(57, 4, '莆田', 2),
(58, 4, '泉州', 2),
(59, 4, '三明', 2),
(60, 4, '厦门', 2),
(61, 4, '漳州', 2),
(62, 5, '兰州', 2),
(63, 5, '白银', 2),
(64, 5, '定西', 2),
(65, 5, '甘南', 2),
(66, 5, '嘉峪关', 2),
(67, 5, '金昌', 2),
(68, 5, '酒泉', 2),
(69, 5, '临夏', 2),
(70, 5, '陇南', 2),
(71, 5, '平凉', 2),
(72, 5, '庆阳', 2),
(73, 5, '天水', 2),
(74, 5, '武威', 2),
(75, 5, '张掖', 2),
(76, 6, '广州', 2),
(77, 6, '深圳', 2),
(78, 6, '潮州', 2),
(79, 6, '东莞', 2),
(80, 6, '佛山', 2),
(81, 6, '河源', 2),
(82, 6, '惠州', 2),
(83, 6, '江门', 2),
(84, 6, '揭阳', 2),
(85, 6, '茂名', 2),
(86, 6, '梅州', 2),
(87, 6, '清远', 2),
(88, 6, '汕头', 2),
(89, 6, '汕尾', 2),
(90, 6, '韶关', 2),
(91, 6, '阳江', 2),
(92, 6, '云浮', 2),
(93, 6, '湛江', 2),
(94, 6, '肇庆', 2),
(95, 6, '中山', 2),
(96, 6, '珠海', 2),
(97, 7, '南宁', 2),
(98, 7, '桂林', 2),
(99, 7, '百色', 2),
(100, 7, '北海', 2),
(101, 7, '崇左', 2),
(102, 7, '防城港', 2),
(103, 7, '贵港', 2),
(104, 7, '河池', 2),
(105, 7, '贺州', 2),
(106, 7, '来宾', 2),
(107, 7, '柳州', 2),
(108, 7, '钦州', 2),
(109, 7, '梧州', 2),
(110, 7, '玉林', 2),
(111, 8, '贵阳', 2),
(112, 8, '安顺', 2),
(113, 8, '毕节', 2),
(114, 8, '六盘水', 2),
(115, 8, '黔东南', 2),
(116, 8, '黔南', 2),
(117, 8, '黔西南', 2),
(118, 8, '铜仁', 2),
(119, 8, '遵义', 2),
(120, 9, '海口', 2),
(121, 9, '三亚', 2),
(122, 9, '白沙', 2),
(123, 9, '保亭', 2),
(124, 9, '昌江', 2),
(125, 9, '澄迈县', 2),
(126, 9, '定安县', 2),
(127, 9, '东方', 2),
(128, 9, '乐东', 2),
(129, 9, '临高县', 2),
(130, 9, '陵水', 2),
(131, 9, '琼海', 2),
(132, 9, '琼中', 2),
(133, 9, '屯昌县', 2),
(134, 9, '万宁', 2),
(135, 9, '文昌', 2),
(136, 9, '五指山', 2),
(137, 9, '儋州', 2),
(138, 10, '石家庄', 2),
(139, 10, '保定', 2),
(140, 10, '沧州', 2),
(141, 10, '承德', 2),
(142, 10, '邯郸', 2),
(143, 10, '衡水', 2),
(144, 10, '廊坊', 2),
(145, 10, '秦皇岛', 2),
(146, 10, '唐山', 2),
(147, 10, '邢台', 2),
(148, 10, '张家口', 2),
(149, 11, '郑州', 2),
(150, 11, '洛阳', 2),
(151, 11, '开封', 2),
(152, 11, '安阳', 2),
(153, 11, '鹤壁', 2),
(154, 11, '济源', 2),
(155, 11, '焦作', 2),
(156, 11, '南阳', 2),
(157, 11, '平顶山', 2),
(158, 11, '三门峡', 2),
(159, 11, '商丘', 2),
(160, 11, '新乡', 2),
(161, 11, '信阳', 2),
(162, 11, '许昌', 2),
(163, 11, '周口', 2),
(164, 11, '驻马店', 2),
(165, 11, '漯河', 2),
(166, 11, '濮阳', 2),
(167, 12, '哈尔滨', 2),
(168, 12, '大庆', 2),
(169, 12, '大兴安岭', 2),
(170, 12, '鹤岗', 2),
(171, 12, '黑河', 2),
(172, 12, '鸡西', 2),
(173, 12, '佳木斯', 2),
(174, 12, '牡丹江', 2),
(175, 12, '七台河', 2),
(176, 12, '齐齐哈尔', 2),
(177, 12, '双鸭山', 2),
(178, 12, '绥化', 2),
(179, 12, '伊春', 2),
(180, 13, '武汉', 2),
(181, 13, '仙桃', 2),
(182, 13, '鄂州', 2),
(183, 13, '黄冈', 2),
(184, 13, '黄石', 2),
(185, 13, '荆门', 2),
(186, 13, '荆州', 2),
(187, 13, '潜江', 2),
(188, 13, '神农架林区', 2),
(189, 13, '十堰', 2),
(190, 13, '随州', 2),
(191, 13, '天门', 2),
(192, 13, '咸宁', 2),
(193, 13, '襄樊', 2),
(194, 13, '孝感', 2),
(195, 13, '宜昌', 2),
(196, 13, '恩施', 2),
(197, 14, '长沙', 2),
(198, 14, '张家界', 2),
(199, 14, '常德', 2),
(200, 14, '郴州', 2),
(201, 14, '衡阳', 2),
(202, 14, '怀化', 2),
(203, 14, '娄底', 2),
(204, 14, '邵阳', 2),
(205, 14, '湘潭', 2),
(206, 14, '湘西', 2),
(207, 14, '益阳', 2),
(208, 14, '永州', 2),
(209, 14, '岳阳', 2),
(210, 14, '株洲', 2),
(211, 15, '长春', 2),
(212, 15, '吉林', 2),
(213, 15, '白城', 2),
(214, 15, '白山', 2),
(215, 15, '辽源', 2),
(216, 15, '四平', 2),
(217, 15, '松原', 2),
(218, 15, '通化', 2),
(219, 15, '延边', 2),
(220, 16, '南京', 2),
(221, 16, '苏州', 2),
(222, 16, '无锡', 2),
(223, 16, '常州', 2),
(224, 16, '淮安', 2),
(225, 16, '连云港', 2),
(226, 16, '南通', 2),
(227, 16, '宿迁', 2),
(228, 16, '泰州', 2),
(229, 16, '徐州', 2),
(230, 16, '盐城', 2),
(231, 16, '扬州', 2),
(232, 16, '镇江', 2),
(233, 17, '南昌', 2),
(234, 17, '抚州', 2),
(235, 17, '赣州', 2),
(236, 17, '吉安', 2),
(237, 17, '景德镇', 2),
(238, 17, '九江', 2),
(239, 17, '萍乡', 2),
(240, 17, '上饶', 2),
(241, 17, '新余', 2),
(242, 17, '宜春', 2),
(243, 17, '鹰潭', 2),
(244, 18, '沈阳', 2),
(245, 18, '大连', 2),
(246, 18, '鞍山', 2),
(247, 18, '本溪', 2),
(248, 18, '朝阳', 2),
(249, 18, '丹东', 2),
(250, 18, '抚顺', 2),
(251, 18, '阜新', 2),
(252, 18, '葫芦岛', 2),
(253, 18, '锦州', 2),
(254, 18, '辽阳', 2),
(255, 18, '盘锦', 2),
(256, 18, '铁岭', 2),
(257, 18, '营口', 2),
(258, 19, '呼和浩特', 2),
(259, 19, '阿拉善盟', 2),
(260, 19, '巴彦淖尔盟', 2),
(261, 19, '包头', 2),
(262, 19, '赤峰', 2),
(263, 19, '鄂尔多斯', 2),
(264, 19, '呼伦贝尔', 2),
(265, 19, '通辽', 2),
(266, 19, '乌海', 2),
(267, 19, '乌兰察布市', 2),
(268, 19, '锡林郭勒盟', 2),
(269, 19, '兴安盟', 2),
(270, 20, '银川', 2),
(271, 20, '固原', 2),
(272, 20, '石嘴山', 2),
(273, 20, '吴忠', 2),
(274, 20, '中卫', 2),
(275, 21, '西宁', 2),
(276, 21, '果洛', 2),
(277, 21, '海北', 2),
(278, 21, '海东', 2),
(279, 21, '海南', 2),
(280, 21, '海西', 2),
(281, 21, '黄南', 2),
(282, 21, '玉树', 2),
(283, 22, '济南', 2),
(284, 22, '青岛', 2),
(285, 22, '滨州', 2),
(286, 22, '德州', 2),
(287, 22, '东营', 2),
(288, 22, '菏泽', 2),
(289, 22, '济宁', 2),
(290, 22, '莱芜', 2),
(291, 22, '聊城', 2),
(292, 22, '临沂', 2),
(293, 22, '日照', 2),
(294, 22, '泰安', 2),
(295, 22, '威海', 2),
(296, 22, '潍坊', 2),
(297, 22, '烟台', 2),
(298, 22, '枣庄', 2),
(299, 22, '淄博', 2),
(300, 23, '太原', 2),
(301, 23, '长治', 2),
(302, 23, '大同', 2),
(303, 23, '晋城', 2),
(304, 23, '晋中', 2),
(305, 23, '临汾', 2),
(306, 23, '吕梁', 2),
(307, 23, '朔州', 2),
(308, 23, '忻州', 2),
(309, 23, '阳泉', 2),
(310, 23, '运城', 2),
(311, 24, '西安', 2),
(312, 24, '安康', 2),
(313, 24, '宝鸡', 2),
(314, 24, '汉中', 2),
(315, 24, '商洛', 2),
(316, 24, '铜川', 2),
(317, 24, '渭南', 2),
(318, 24, '咸阳', 2),
(319, 24, '延安', 2),
(320, 24, '榆林', 2),
(321, 25, '上海', 2),
(322, 26, '成都', 2),
(323, 26, '绵阳', 2),
(324, 26, '阿坝', 2),
(325, 26, '巴中', 2),
(326, 26, '达州', 2),
(327, 26, '德阳', 2),
(328, 26, '甘孜', 2),
(329, 26, '广安', 2),
(330, 26, '广元', 2),
(331, 26, '乐山', 2),
(332, 26, '凉山', 2),
(333, 26, '眉山', 2),
(334, 26, '南充', 2),
(335, 26, '内江', 2),
(336, 26, '攀枝花', 2),
(337, 26, '遂宁', 2),
(338, 26, '雅安', 2),
(339, 26, '宜宾', 2),
(340, 26, '资阳', 2),
(341, 26, '自贡', 2),
(342, 26, '泸州', 2),
(343, 27, '天津', 2),
(344, 28, '拉萨', 2),
(345, 28, '阿里', 2),
(346, 28, '昌都', 2),
(347, 28, '林芝', 2),
(348, 28, '那曲', 2),
(349, 28, '日喀则', 2),
(350, 28, '山南', 2),
(351, 29, '乌鲁木齐', 2),
(352, 29, '阿克苏', 2),
(353, 29, '阿拉尔', 2),
(354, 29, '巴音郭楞', 2),
(355, 29, '博尔塔拉', 2),
(356, 29, '昌吉', 2),
(357, 29, '哈密', 2),
(358, 29, '和田', 2),
(359, 29, '喀什', 2),
(360, 29, '克拉玛依', 2),
(361, 29, '克孜勒苏', 2),
(362, 29, '石河子', 2),
(363, 29, '图木舒克', 2),
(364, 29, '吐鲁番', 2),
(365, 29, '五家渠', 2),
(366, 29, '伊犁', 2),
(367, 30, '昆明', 2),
(368, 30, '怒江', 2),
(369, 30, '普洱', 2),
(370, 30, '丽江', 2),
(371, 30, '保山', 2),
(372, 30, '楚雄', 2),
(373, 30, '大理', 2),
(374, 30, '德宏', 2),
(375, 30, '迪庆', 2),
(376, 30, '红河', 2),
(377, 30, '临沧', 2),
(378, 30, '曲靖', 2),
(379, 30, '文山', 2),
(380, 30, '西双版纳', 2),
(381, 30, '玉溪', 2),
(382, 30, '昭通', 2),
(383, 31, '杭州', 2),
(384, 31, '湖州', 2),
(385, 31, '嘉兴', 2),
(386, 31, '金华', 2),
(387, 31, '丽水', 2),
(388, 31, '宁波', 2),
(389, 31, '绍兴', 2),
(390, 31, '台州', 2),
(391, 31, '温州', 2),
(392, 31, '舟山', 2),
(393, 31, '衢州', 2),
(394, 32, '重庆', 2),
(395, 33, '香港', 2),
(396, 34, '澳门', 2),
(397, 35, '台湾', 2),
(398, 36, '迎江区', 3),
(399, 36, '大观区', 3),
(400, 36, '宜秀区', 3),
(401, 36, '桐城市', 3),
(402, 36, '怀宁县', 3),
(403, 36, '枞阳县', 3),
(404, 36, '潜山县', 3),
(405, 36, '太湖县', 3),
(406, 36, '宿松县', 3),
(407, 36, '望江县', 3),
(408, 36, '岳西县', 3),
(409, 37, '中市区', 3),
(410, 37, '东市区', 3),
(411, 37, '西市区', 3),
(412, 37, '郊区', 3),
(413, 37, '怀远县', 3),
(414, 37, '五河县', 3),
(415, 37, '固镇县', 3),
(416, 38, '居巢区', 3),
(417, 38, '庐江县', 3),
(418, 38, '无为县', 3),
(419, 38, '含山县', 3),
(420, 38, '和县', 3),
(421, 39, '贵池区', 3),
(422, 39, '东至县', 3),
(423, 39, '石台县', 3),
(424, 39, '青阳县', 3),
(425, 40, '琅琊区', 3),
(426, 40, '南谯区', 3),
(427, 40, '天长市', 3),
(428, 40, '明光市', 3),
(429, 40, '来安县', 3),
(430, 40, '全椒县', 3),
(431, 40, '定远县', 3),
(432, 40, '凤阳县', 3),
(433, 41, '蚌山区', 3),
(434, 41, '龙子湖区', 3),
(435, 41, '禹会区', 3),
(436, 41, '淮上区', 3),
(437, 41, '颍州区', 3),
(438, 41, '颍东区', 3),
(439, 41, '颍泉区', 3),
(440, 41, '界首市', 3),
(441, 41, '临泉县', 3),
(442, 41, '太和县', 3),
(443, 41, '阜南县', 3),
(444, 41, '颖上县', 3),
(445, 42, '相山区', 3),
(446, 42, '杜集区', 3),
(447, 42, '烈山区', 3),
(448, 42, '濉溪县', 3),
(449, 43, '田家庵区', 3),
(450, 43, '大通区', 3),
(451, 43, '谢家集区', 3),
(452, 43, '八公山区', 3),
(453, 43, '潘集区', 3),
(454, 43, '凤台县', 3),
(455, 44, '屯溪区', 3),
(456, 44, '黄山区', 3),
(457, 44, '徽州区', 3),
(458, 44, '歙县', 3),
(459, 44, '休宁县', 3),
(460, 44, '黟县', 3),
(461, 44, '祁门县', 3),
(462, 45, '金安区', 3),
(463, 45, '裕安区', 3),
(464, 45, '寿县', 3),
(465, 45, '霍邱县', 3),
(466, 45, '舒城县', 3),
(467, 45, '金寨县', 3),
(468, 45, '霍山县', 3),
(469, 46, '雨山区', 3),
(470, 46, '花山区', 3),
(471, 46, '金家庄区', 3),
(472, 46, '当涂县', 3),
(473, 47, '埇桥区', 3),
(474, 47, '砀山县', 3),
(475, 47, '萧县', 3),
(476, 47, '灵璧县', 3),
(477, 47, '泗县', 3),
(478, 48, '铜官山区', 3),
(479, 48, '狮子山区', 3),
(480, 48, '郊区', 3),
(481, 48, '铜陵县', 3),
(482, 49, '镜湖区', 3),
(483, 49, '弋江区', 3),
(484, 49, '鸠江区', 3),
(485, 49, '三山区', 3),
(486, 49, '芜湖县', 3),
(487, 49, '繁昌县', 3),
(488, 49, '南陵县', 3),
(489, 50, '宣州区', 3),
(490, 50, '宁国市', 3),
(491, 50, '郎溪县', 3),
(492, 50, '广德县', 3),
(493, 50, '泾县', 3),
(494, 50, '绩溪县', 3),
(495, 50, '旌德县', 3),
(496, 51, '涡阳县', 3),
(497, 51, '蒙城县', 3),
(498, 51, '利辛县', 3),
(499, 51, '谯城区', 3),
(500, 52, '东城区', 3),
(501, 52, '西城区', 3),
(502, 52, '海淀区', 3),
(503, 52, '朝阳区', 3),
(504, 52, '崇文区', 3),
(505, 52, '宣武区', 3),
(506, 52, '丰台区', 3),
(507, 52, '石景山区', 3),
(508, 52, '房山区', 3),
(509, 52, '门头沟区', 3),
(510, 52, '通州区', 3),
(511, 52, '顺义区', 3),
(512, 52, '昌平区', 3),
(513, 52, '怀柔区', 3),
(514, 52, '平谷区', 3),
(515, 52, '大兴区', 3),
(516, 52, '密云县', 3),
(517, 52, '延庆县', 3),
(518, 53, '鼓楼区', 3),
(519, 53, '台江区', 3),
(520, 53, '仓山区', 3),
(521, 53, '马尾区', 3),
(522, 53, '晋安区', 3),
(523, 53, '福清市', 3),
(524, 53, '长乐市', 3),
(525, 53, '闽侯县', 3),
(526, 53, '连江县', 3),
(527, 53, '罗源县', 3),
(528, 53, '闽清县', 3),
(529, 53, '永泰县', 3),
(530, 53, '平潭县', 3),
(531, 54, '新罗区', 3),
(532, 54, '漳平市', 3),
(533, 54, '长汀县', 3),
(534, 54, '永定县', 3),
(535, 54, '上杭县', 3),
(536, 54, '武平县', 3),
(537, 54, '连城县', 3),
(538, 55, '延平区', 3),
(539, 55, '邵武市', 3),
(540, 55, '武夷山市', 3),
(541, 55, '建瓯市', 3),
(542, 55, '建阳市', 3),
(543, 55, '顺昌县', 3),
(544, 55, '浦城县', 3),
(545, 55, '光泽县', 3),
(546, 55, '松溪县', 3),
(547, 55, '政和县', 3),
(548, 56, '蕉城区', 3),
(549, 56, '福安市', 3),
(550, 56, '福鼎市', 3),
(551, 56, '霞浦县', 3),
(552, 56, '古田县', 3),
(553, 56, '屏南县', 3),
(554, 56, '寿宁县', 3),
(555, 56, '周宁县', 3),
(556, 56, '柘荣县', 3),
(557, 57, '城厢区', 3),
(558, 57, '涵江区', 3),
(559, 57, '荔城区', 3),
(560, 57, '秀屿区', 3),
(561, 57, '仙游县', 3),
(562, 58, '鲤城区', 3),
(563, 58, '丰泽区', 3),
(564, 58, '洛江区', 3),
(565, 58, '清濛开发区', 3),
(566, 58, '泉港区', 3),
(567, 58, '石狮市', 3),
(568, 58, '晋江市', 3),
(569, 58, '南安市', 3),
(570, 58, '惠安县', 3),
(571, 58, '安溪县', 3),
(572, 58, '永春县', 3),
(573, 58, '德化县', 3),
(574, 58, '金门县', 3),
(575, 59, '梅列区', 3),
(576, 59, '三元区', 3),
(577, 59, '永安市', 3),
(578, 59, '明溪县', 3),
(579, 59, '清流县', 3),
(580, 59, '宁化县', 3),
(581, 59, '大田县', 3),
(582, 59, '尤溪县', 3),
(583, 59, '沙县', 3),
(584, 59, '将乐县', 3),
(585, 59, '泰宁县', 3),
(586, 59, '建宁县', 3),
(587, 60, '思明区', 3),
(588, 60, '海沧区', 3),
(589, 60, '湖里区', 3),
(590, 60, '集美区', 3),
(591, 60, '同安区', 3),
(592, 60, '翔安区', 3),
(593, 61, '芗城区', 3),
(594, 61, '龙文区', 3),
(595, 61, '龙海市', 3),
(596, 61, '云霄县', 3),
(597, 61, '漳浦县', 3),
(598, 61, '诏安县', 3),
(599, 61, '长泰县', 3),
(600, 61, '东山县', 3),
(601, 61, '南靖县', 3),
(602, 61, '平和县', 3),
(603, 61, '华安县', 3),
(604, 62, '皋兰县', 3),
(605, 62, '城关区', 3),
(606, 62, '七里河区', 3),
(607, 62, '西固区', 3),
(608, 62, '安宁区', 3),
(609, 62, '红古区', 3),
(610, 62, '永登县', 3),
(611, 62, '榆中县', 3),
(612, 63, '白银区', 3),
(613, 63, '平川区', 3),
(614, 63, '会宁县', 3),
(615, 63, '景泰县', 3),
(616, 63, '靖远县', 3),
(617, 64, '临洮县', 3),
(618, 64, '陇西县', 3),
(619, 64, '通渭县', 3),
(620, 64, '渭源县', 3),
(621, 64, '漳县', 3),
(622, 64, '岷县', 3),
(623, 64, '安定区', 3),
(624, 64, '安定区', 3),
(625, 65, '合作市', 3),
(626, 65, '临潭县', 3),
(627, 65, '卓尼县', 3),
(628, 65, '舟曲县', 3),
(629, 65, '迭部县', 3),
(630, 65, '玛曲县', 3),
(631, 65, '碌曲县', 3),
(632, 65, '夏河县', 3),
(633, 66, '嘉峪关市', 3),
(634, 67, '金川区', 3),
(635, 67, '永昌县', 3),
(636, 68, '肃州区', 3),
(637, 68, '玉门市', 3),
(638, 68, '敦煌市', 3),
(639, 68, '金塔县', 3),
(640, 68, '瓜州县', 3),
(641, 68, '肃北', 3),
(642, 68, '阿克塞', 3),
(643, 69, '临夏市', 3),
(644, 69, '临夏县', 3),
(645, 69, '康乐县', 3),
(646, 69, '永靖县', 3),
(647, 69, '广河县', 3),
(648, 69, '和政县', 3),
(649, 69, '东乡族自治县', 3),
(650, 69, '积石山', 3),
(651, 70, '成县', 3),
(652, 70, '徽县', 3),
(653, 70, '康县', 3),
(654, 70, '礼县', 3),
(655, 70, '两当县', 3),
(656, 70, '文县', 3),
(657, 70, '西和县', 3),
(658, 70, '宕昌县', 3),
(659, 70, '武都区', 3),
(660, 71, '崇信县', 3),
(661, 71, '华亭县', 3),
(662, 71, '静宁县', 3),
(663, 71, '灵台县', 3),
(664, 71, '崆峒区', 3),
(665, 71, '庄浪县', 3),
(666, 71, '泾川县', 3),
(667, 72, '合水县', 3),
(668, 72, '华池县', 3),
(669, 72, '环县', 3),
(670, 72, '宁县', 3),
(671, 72, '庆城县', 3),
(672, 72, '西峰区', 3),
(673, 72, '镇原县', 3),
(674, 72, '正宁县', 3),
(675, 73, '甘谷县', 3),
(676, 73, '秦安县', 3),
(677, 73, '清水县', 3),
(678, 73, '秦州区', 3),
(679, 73, '麦积区', 3),
(680, 73, '武山县', 3),
(681, 73, '张家川', 3),
(682, 74, '古浪县', 3),
(683, 74, '民勤县', 3),
(684, 74, '天祝', 3),
(685, 74, '凉州区', 3),
(686, 75, '高台县', 3),
(687, 75, '临泽县', 3),
(688, 75, '民乐县', 3),
(689, 75, '山丹县', 3),
(690, 75, '肃南', 3),
(691, 75, '甘州区', 3),
(692, 76, '从化市', 3),
(693, 76, '天河区', 3),
(694, 76, '东山区', 3),
(695, 76, '白云区', 3),
(696, 76, '海珠区', 3),
(697, 76, '荔湾区', 3),
(698, 76, '越秀区', 3),
(699, 76, '黄埔区', 3),
(700, 76, '番禺区', 3),
(701, 76, '花都区', 3),
(702, 76, '增城区', 3),
(703, 76, '从化区', 3),
(704, 76, '市郊', 3),
(705, 77, '福田区', 3),
(706, 77, '罗湖区', 3),
(707, 77, '南山区', 3),
(708, 77, '宝安区', 3),
(709, 77, '龙岗区', 3),
(710, 77, '盐田区', 3),
(711, 78, '湘桥区', 3),
(712, 78, '潮安县', 3),
(713, 78, '饶平县', 3),
(714, 79, '南城区', 3),
(715, 79, '东城区', 3),
(716, 79, '万江区', 3),
(717, 79, '莞城区', 3),
(718, 79, '石龙镇', 3),
(719, 79, '虎门镇', 3),
(720, 79, '麻涌镇', 3),
(721, 79, '道滘镇', 3),
(722, 79, '石碣镇', 3),
(723, 79, '沙田镇', 3),
(724, 79, '望牛墩镇', 3),
(725, 79, '洪梅镇', 3),
(726, 79, '茶山镇', 3),
(727, 79, '寮步镇', 3),
(728, 79, '大岭山镇', 3),
(729, 79, '大朗镇', 3),
(730, 79, '黄江镇', 3),
(731, 79, '樟木头', 3),
(732, 79, '凤岗镇', 3),
(733, 79, '塘厦镇', 3),
(734, 79, '谢岗镇', 3),
(735, 79, '厚街镇', 3),
(736, 79, '清溪镇', 3),
(737, 79, '常平镇', 3),
(738, 79, '桥头镇', 3),
(739, 79, '横沥镇', 3),
(740, 79, '东坑镇', 3),
(741, 79, '企石镇', 3),
(742, 79, '石排镇', 3),
(743, 79, '长安镇', 3),
(744, 79, '中堂镇', 3),
(745, 79, '高埗镇', 3),
(746, 80, '禅城区', 3),
(747, 80, '南海区', 3),
(748, 80, '顺德区', 3),
(749, 80, '三水区', 3),
(750, 80, '高明区', 3),
(751, 81, '东源县', 3),
(752, 81, '和平县', 3),
(753, 81, '源城区', 3),
(754, 81, '连平县', 3),
(755, 81, '龙川县', 3),
(756, 81, '紫金县', 3),
(757, 82, '惠阳区', 3),
(758, 82, '惠城区', 3),
(759, 82, '大亚湾', 3),
(760, 82, '博罗县', 3),
(761, 82, '惠东县', 3),
(762, 82, '龙门县', 3),
(763, 83, '江海区', 3),
(764, 83, '蓬江区', 3),
(765, 83, '新会区', 3),
(766, 83, '台山市', 3),
(767, 83, '开平市', 3),
(768, 83, '鹤山市', 3),
(769, 83, '恩平市', 3),
(770, 84, '榕城区', 3),
(771, 84, '普宁市', 3),
(772, 84, '揭东县', 3),
(773, 84, '揭西县', 3),
(774, 84, '惠来县', 3),
(775, 85, '茂南区', 3),
(776, 85, '茂港区', 3),
(777, 85, '高州市', 3),
(778, 85, '化州市', 3),
(779, 85, '信宜市', 3),
(780, 85, '电白县', 3),
(781, 86, '梅县', 3),
(782, 86, '梅江区', 3),
(783, 86, '兴宁市', 3),
(784, 86, '大埔县', 3),
(785, 86, '丰顺县', 3),
(786, 86, '五华县', 3),
(787, 86, '平远县', 3),
(788, 86, '蕉岭县', 3),
(789, 87, '清城区', 3),
(790, 87, '英德市', 3),
(791, 87, '连州市', 3),
(792, 87, '佛冈县', 3),
(793, 87, '阳山县', 3),
(794, 87, '清新县', 3),
(795, 87, '连山', 3),
(796, 87, '连南', 3),
(797, 88, '南澳县', 3),
(798, 88, '潮阳区', 3),
(799, 88, '澄海区', 3),
(800, 88, '龙湖区', 3),
(801, 88, '金平区', 3),
(802, 88, '濠江区', 3),
(803, 88, '潮南区', 3),
(804, 89, '城区', 3),
(805, 89, '陆丰市', 3),
(806, 89, '海丰县', 3),
(807, 89, '陆河县', 3),
(808, 90, '曲江县', 3),
(809, 90, '浈江区', 3),
(810, 90, '武江区', 3),
(811, 90, '曲江区', 3),
(812, 90, '乐昌市', 3),
(813, 90, '南雄市', 3),
(814, 90, '始兴县', 3),
(815, 90, '仁化县', 3),
(816, 90, '翁源县', 3),
(817, 90, '新丰县', 3),
(818, 90, '乳源', 3),
(819, 91, '江城区', 3),
(820, 91, '阳春市', 3),
(821, 91, '阳西县', 3),
(822, 91, '阳东县', 3),
(823, 92, '云城区', 3),
(824, 92, '罗定市', 3),
(825, 92, '新兴县', 3),
(826, 92, '郁南县', 3),
(827, 92, '云安县', 3),
(828, 93, '赤坎区', 3),
(829, 93, '霞山区', 3),
(830, 93, '坡头区', 3),
(831, 93, '麻章区', 3),
(832, 93, '廉江市', 3),
(833, 93, '雷州市', 3),
(834, 93, '吴川市', 3),
(835, 93, '遂溪县', 3),
(836, 93, '徐闻县', 3),
(837, 94, '肇庆市', 3),
(838, 94, '高要市', 3),
(839, 94, '四会市', 3),
(840, 94, '广宁县', 3),
(841, 94, '怀集县', 3),
(842, 94, '封开县', 3),
(843, 94, '德庆县', 3),
(844, 95, '石岐街道', 3),
(845, 95, '东区街道', 3),
(846, 95, '西区街道', 3),
(847, 95, '环城街道', 3),
(848, 95, '中山港街道', 3),
(849, 95, '五桂山街道', 3),
(850, 96, '香洲区', 3),
(851, 96, '斗门区', 3),
(852, 96, '金湾区', 3),
(853, 97, '邕宁区', 3),
(854, 97, '青秀区', 3),
(855, 97, '兴宁区', 3),
(856, 97, '良庆区', 3),
(857, 97, '西乡塘区', 3),
(858, 97, '江南区', 3),
(859, 97, '武鸣县', 3),
(860, 97, '隆安县', 3),
(861, 97, '马山县', 3),
(862, 97, '上林县', 3),
(863, 97, '宾阳县', 3),
(864, 97, '横县', 3),
(865, 98, '秀峰区', 3),
(866, 98, '叠彩区', 3),
(867, 98, '象山区', 3),
(868, 98, '七星区', 3),
(869, 98, '雁山区', 3),
(870, 98, '阳朔县', 3),
(871, 98, '临桂县', 3),
(872, 98, '灵川县', 3),
(873, 98, '全州县', 3),
(874, 98, '平乐县', 3),
(875, 98, '兴安县', 3),
(876, 98, '灌阳县', 3),
(877, 98, '荔浦县', 3),
(878, 98, '资源县', 3),
(879, 98, '永福县', 3),
(880, 98, '龙胜', 3),
(881, 98, '恭城', 3),
(882, 99, '右江区', 3),
(883, 99, '凌云县', 3),
(884, 99, '平果县', 3),
(885, 99, '西林县', 3),
(886, 99, '乐业县', 3),
(887, 99, '德保县', 3),
(888, 99, '田林县', 3),
(889, 99, '田阳县', 3),
(890, 99, '靖西县', 3),
(891, 99, '田东县', 3),
(892, 99, '那坡县', 3),
(893, 99, '隆林', 3),
(894, 100, '海城区', 3),
(895, 100, '银海区', 3),
(896, 100, '铁山港区', 3),
(897, 100, '合浦县', 3),
(898, 101, '江州区', 3),
(899, 101, '凭祥市', 3),
(900, 101, '宁明县', 3),
(901, 101, '扶绥县', 3),
(902, 101, '龙州县', 3),
(903, 101, '大新县', 3),
(904, 101, '天等县', 3),
(905, 102, '港口区', 3),
(906, 102, '防城区', 3),
(907, 102, '东兴市', 3),
(908, 102, '上思县', 3),
(909, 103, '港北区', 3),
(910, 103, '港南区', 3),
(911, 103, '覃塘区', 3),
(912, 103, '桂平市', 3),
(913, 103, '平南县', 3),
(914, 104, '金城江区', 3),
(915, 104, '宜州市', 3),
(916, 104, '天峨县', 3),
(917, 104, '凤山县', 3),
(918, 104, '南丹县', 3),
(919, 104, '东兰县', 3),
(920, 104, '都安', 3),
(921, 104, '罗城', 3),
(922, 104, '巴马', 3),
(923, 104, '环江', 3),
(924, 104, '大化', 3),
(925, 105, '八步区', 3),
(926, 105, '钟山县', 3),
(927, 105, '昭平县', 3),
(928, 105, '富川', 3),
(929, 106, '兴宾区', 3),
(930, 106, '合山市', 3),
(931, 106, '象州县', 3),
(932, 106, '武宣县', 3),
(933, 106, '忻城县', 3),
(934, 106, '金秀', 3),
(935, 107, '城中区', 3),
(936, 107, '鱼峰区', 3),
(937, 107, '柳北区', 3),
(938, 107, '柳南区', 3),
(939, 107, '柳江县', 3),
(940, 107, '柳城县', 3),
(941, 107, '鹿寨县', 3),
(942, 107, '融安县', 3),
(943, 107, '融水', 3),
(944, 107, '三江', 3),
(945, 108, '钦南区', 3),
(946, 108, '钦北区', 3),
(947, 108, '灵山县', 3),
(948, 108, '浦北县', 3),
(949, 109, '万秀区', 3),
(950, 109, '蝶山区', 3),
(951, 109, '长洲区', 3),
(952, 109, '岑溪市', 3),
(953, 109, '苍梧县', 3),
(954, 109, '藤县', 3),
(955, 109, '蒙山县', 3),
(956, 110, '玉州区', 3),
(957, 110, '北流市', 3),
(958, 110, '容县', 3),
(959, 110, '陆川县', 3),
(960, 110, '博白县', 3),
(961, 110, '兴业县', 3),
(962, 111, '南明区', 3),
(963, 111, '云岩区', 3),
(964, 111, '花溪区', 3),
(965, 111, '乌当区', 3),
(966, 111, '白云区', 3),
(967, 111, '小河区', 3),
(968, 111, '金阳新区', 3),
(969, 111, '新天园区', 3),
(970, 111, '清镇市', 3),
(971, 111, '开阳县', 3),
(972, 111, '修文县', 3),
(973, 111, '息烽县', 3),
(974, 112, '西秀区', 3),
(975, 112, '关岭', 3),
(976, 112, '镇宁', 3),
(977, 112, '紫云', 3),
(978, 112, '平坝县', 3),
(979, 112, '普定县', 3),
(980, 113, '毕节市', 3),
(981, 113, '大方县', 3),
(982, 113, '黔西县', 3),
(983, 113, '金沙县', 3),
(984, 113, '织金县', 3),
(985, 113, '纳雍县', 3),
(986, 113, '赫章县', 3),
(987, 113, '威宁', 3),
(988, 114, '钟山区', 3),
(989, 114, '六枝特区', 3),
(990, 114, '水城县', 3),
(991, 114, '盘县', 3),
(992, 115, '凯里市', 3),
(993, 115, '黄平县', 3),
(994, 115, '施秉县', 3),
(995, 115, '三穗县', 3),
(996, 115, '镇远县', 3),
(997, 115, '岑巩县', 3),
(998, 115, '天柱县', 3),
(999, 115, '锦屏县', 3),
(1000, 115, '剑河县', 3),
(1001, 115, '台江县', 3),
(1002, 115, '黎平县', 3),
(1003, 115, '榕江县', 3),
(1004, 115, '从江县', 3),
(1005, 115, '雷山县', 3),
(1006, 115, '麻江县', 3),
(1007, 115, '丹寨县', 3),
(1008, 116, '都匀市', 3),
(1009, 116, '福泉市', 3),
(1010, 116, '荔波县', 3),
(1011, 116, '贵定县', 3),
(1012, 116, '瓮安县', 3),
(1013, 116, '独山县', 3),
(1014, 116, '平塘县', 3),
(1015, 116, '罗甸县', 3),
(1016, 116, '长顺县', 3),
(1017, 116, '龙里县', 3),
(1018, 116, '惠水县', 3),
(1019, 116, '三都', 3),
(1020, 117, '兴义市', 3),
(1021, 117, '兴仁县', 3),
(1022, 117, '普安县', 3),
(1023, 117, '晴隆县', 3),
(1024, 117, '贞丰县', 3),
(1025, 117, '望谟县', 3),
(1026, 117, '册亨县', 3),
(1027, 117, '安龙县', 3),
(1028, 118, '铜仁市', 3),
(1029, 118, '江口县', 3),
(1030, 118, '石阡县', 3),
(1031, 118, '思南县', 3),
(1032, 118, '德江县', 3),
(1033, 118, '玉屏', 3),
(1034, 118, '印江', 3),
(1035, 118, '沿河', 3),
(1036, 118, '松桃', 3),
(1037, 118, '万山特区', 3),
(1038, 119, '红花岗区', 3),
(1039, 119, '务川县', 3),
(1040, 119, '道真县', 3),
(1041, 119, '汇川区', 3),
(1042, 119, '赤水市', 3),
(1043, 119, '仁怀市', 3),
(1044, 119, '遵义县', 3),
(1045, 119, '桐梓县', 3),
(1046, 119, '绥阳县', 3),
(1047, 119, '正安县', 3),
(1048, 119, '凤冈县', 3),
(1049, 119, '湄潭县', 3),
(1050, 119, '余庆县', 3),
(1051, 119, '习水县', 3),
(1052, 119, '道真', 3),
(1053, 119, '务川', 3),
(1054, 120, '秀英区', 3),
(1055, 120, '龙华区', 3),
(1056, 120, '琼山区', 3),
(1057, 120, '美兰区', 3),
(1058, 137, '市区', 3),
(1059, 137, '洋浦开发区', 3),
(1060, 137, '那大镇', 3),
(1061, 137, '王五镇', 3),
(1062, 137, '雅星镇', 3),
(1063, 137, '大成镇', 3),
(1064, 137, '中和镇', 3),
(1065, 137, '峨蔓镇', 3),
(1066, 137, '南丰镇', 3),
(1067, 137, '白马井镇', 3),
(1068, 137, '兰洋镇', 3),
(1069, 137, '和庆镇', 3),
(1070, 137, '海头镇', 3),
(1071, 137, '排浦镇', 3),
(1072, 137, '东成镇', 3),
(1073, 137, '光村镇', 3),
(1074, 137, '木棠镇', 3),
(1075, 137, '新州镇', 3),
(1076, 137, '三都镇', 3),
(1077, 137, '其他', 3),
(1078, 138, '长安区', 3),
(1079, 138, '桥东区', 3),
(1080, 138, '桥西区', 3),
(1081, 138, '新华区', 3),
(1082, 138, '裕华区', 3),
(1083, 138, '井陉矿区', 3),
(1084, 138, '高新区', 3),
(1085, 138, '辛集市', 3),
(1086, 138, '藁城市', 3),
(1087, 138, '晋州市', 3),
(1088, 138, '新乐市', 3),
(1089, 138, '鹿泉市', 3),
(1090, 138, '井陉县', 3),
(1091, 138, '正定县', 3),
(1092, 138, '栾城县', 3),
(1093, 138, '行唐县', 3),
(1094, 138, '灵寿县', 3),
(1095, 138, '高邑县', 3),
(1096, 138, '深泽县', 3),
(1097, 138, '赞皇县', 3),
(1098, 138, '无极县', 3),
(1099, 138, '平山县', 3),
(1100, 138, '元氏县', 3),
(1101, 138, '赵县', 3),
(1102, 139, '新市区', 3),
(1103, 139, '南市区', 3),
(1104, 139, '北市区', 3),
(1105, 139, '涿州市', 3),
(1106, 139, '定州市', 3),
(1107, 139, '安国市', 3),
(1108, 139, '高碑店市', 3),
(1109, 139, '满城县', 3),
(1110, 139, '清苑县', 3),
(1111, 139, '涞水县', 3),
(1112, 139, '阜平县', 3),
(1113, 139, '徐水县', 3),
(1114, 139, '定兴县', 3),
(1115, 139, '唐县', 3),
(1116, 139, '高阳县', 3),
(1117, 139, '容城县', 3),
(1118, 139, '涞源县', 3),
(1119, 139, '望都县', 3),
(1120, 139, '安新县', 3),
(1121, 139, '易县', 3),
(1122, 139, '曲阳县', 3),
(1123, 139, '蠡县', 3),
(1124, 139, '顺平县', 3),
(1125, 139, '博野县', 3),
(1126, 139, '雄县', 3),
(1127, 140, '运河区', 3),
(1128, 140, '新华区', 3),
(1129, 140, '泊头市', 3),
(1130, 140, '任丘市', 3),
(1131, 140, '黄骅市', 3),
(1132, 140, '河间市', 3),
(1133, 140, '沧县', 3),
(1134, 140, '青县', 3),
(1135, 140, '东光县', 3),
(1136, 140, '海兴县', 3),
(1137, 140, '盐山县', 3),
(1138, 140, '肃宁县', 3),
(1139, 140, '南皮县', 3),
(1140, 140, '吴桥县', 3),
(1141, 140, '献县', 3),
(1142, 140, '孟村', 3),
(1143, 141, '双桥区', 3),
(1144, 141, '双滦区', 3),
(1145, 141, '鹰手营子矿区', 3),
(1146, 141, '承德县', 3),
(1147, 141, '兴隆县', 3),
(1148, 141, '平泉县', 3),
(1149, 141, '滦平县', 3),
(1150, 141, '隆化县', 3),
(1151, 141, '丰宁', 3),
(1152, 141, '宽城', 3),
(1153, 141, '围场', 3),
(1154, 142, '从台区', 3),
(1155, 142, '复兴区', 3),
(1156, 142, '邯山区', 3),
(1157, 142, '峰峰矿区', 3),
(1158, 142, '武安市', 3),
(1159, 142, '邯郸县', 3),
(1160, 142, '临漳县', 3),
(1161, 142, '成安县', 3),
(1162, 142, '大名县', 3),
(1163, 142, '涉县', 3),
(1164, 142, '磁县', 3),
(1165, 142, '肥乡县', 3),
(1166, 142, '永年县', 3),
(1167, 142, '邱县', 3),
(1168, 142, '鸡泽县', 3),
(1169, 142, '广平县', 3),
(1170, 142, '馆陶县', 3),
(1171, 142, '魏县', 3),
(1172, 142, '曲周县', 3),
(1173, 143, '桃城区', 3),
(1174, 143, '冀州市', 3),
(1175, 143, '深州市', 3),
(1176, 143, '枣强县', 3),
(1177, 143, '武邑县', 3),
(1178, 143, '武强县', 3),
(1179, 143, '饶阳县', 3),
(1180, 143, '安平县', 3),
(1181, 143, '故城县', 3),
(1182, 143, '景县', 3),
(1183, 143, '阜城县', 3),
(1184, 144, '安次区', 3),
(1185, 144, '广阳区', 3),
(1186, 144, '霸州市', 3),
(1187, 144, '三河市', 3),
(1188, 144, '固安县', 3),
(1189, 144, '永清县', 3),
(1190, 144, '香河县', 3),
(1191, 144, '大城县', 3),
(1192, 144, '文安县', 3),
(1193, 144, '大厂', 3),
(1194, 145, '海港区', 3),
(1195, 145, '山海关区', 3),
(1196, 145, '北戴河区', 3),
(1197, 145, '昌黎县', 3),
(1198, 145, '抚宁县', 3),
(1199, 145, '卢龙县', 3),
(1200, 145, '青龙', 3),
(1201, 146, '路北区', 3),
(1202, 146, '路南区', 3),
(1203, 146, '古冶区', 3),
(1204, 146, '开平区', 3),
(1205, 146, '丰南区', 3),
(1206, 146, '丰润区', 3),
(1207, 146, '遵化市', 3),
(1208, 146, '迁安市', 3),
(1209, 146, '滦县', 3),
(1210, 146, '滦南县', 3),
(1211, 146, '乐亭县', 3),
(1212, 146, '迁西县', 3),
(1213, 146, '玉田县', 3),
(1214, 146, '唐海县', 3),
(1215, 147, '桥东区', 3),
(1216, 147, '桥西区', 3),
(1217, 147, '南宫市', 3),
(1218, 147, '沙河市', 3),
(1219, 147, '邢台县', 3),
(1220, 147, '临城县', 3),
(1221, 147, '内丘县', 3),
(1222, 147, '柏乡县', 3),
(1223, 147, '隆尧县', 3),
(1224, 147, '任县', 3),
(1225, 147, '南和县', 3),
(1226, 147, '宁晋县', 3),
(1227, 147, '巨鹿县', 3),
(1228, 147, '新河县', 3),
(1229, 147, '广宗县', 3),
(1230, 147, '平乡县', 3),
(1231, 147, '威县', 3),
(1232, 147, '清河县', 3),
(1233, 147, '临西县', 3),
(1234, 148, '桥西区', 3),
(1235, 148, '桥东区', 3),
(1236, 148, '宣化区', 3),
(1237, 148, '下花园区', 3),
(1238, 148, '宣化县', 3),
(1239, 148, '张北县', 3),
(1240, 148, '康保县', 3),
(1241, 148, '沽源县', 3),
(1242, 148, '尚义县', 3),
(1243, 148, '蔚县', 3),
(1244, 148, '阳原县', 3),
(1245, 148, '怀安县', 3),
(1246, 148, '万全县', 3),
(1247, 148, '怀来县', 3),
(1248, 148, '涿鹿县', 3),
(1249, 148, '赤城县', 3),
(1250, 148, '崇礼县', 3),
(1251, 149, '金水区', 3),
(1252, 149, '邙山区', 3),
(1253, 149, '二七区', 3),
(1254, 149, '管城区', 3),
(1255, 149, '中原区', 3),
(1256, 149, '上街区', 3),
(1257, 149, '惠济区', 3),
(1258, 149, '郑东新区', 3),
(1259, 149, '经济技术开发区', 3),
(1260, 149, '高新开发区', 3),
(1261, 149, '出口加工区', 3),
(1262, 149, '巩义市', 3),
(1263, 149, '荥阳市', 3),
(1264, 149, '新密市', 3),
(1265, 149, '新郑市', 3),
(1266, 149, '登封市', 3),
(1267, 149, '中牟县', 3),
(1268, 150, '西工区', 3),
(1269, 150, '老城区', 3),
(1270, 150, '涧西区', 3),
(1271, 150, '瀍河回族区', 3),
(1272, 150, '洛龙区', 3),
(1273, 150, '吉利区', 3),
(1274, 150, '偃师市', 3),
(1275, 150, '孟津县', 3),
(1276, 150, '新安县', 3),
(1277, 150, '栾川县', 3),
(1278, 150, '嵩县', 3),
(1279, 150, '汝阳县', 3),
(1280, 150, '宜阳县', 3),
(1281, 150, '洛宁县', 3),
(1282, 150, '伊川县', 3),
(1283, 151, '鼓楼区', 3),
(1284, 151, '龙亭区', 3),
(1285, 151, '顺河回族区', 3),
(1286, 151, '金明区', 3),
(1287, 151, '禹王台区', 3),
(1288, 151, '杞县', 3),
(1289, 151, '通许县', 3),
(1290, 151, '尉氏县', 3),
(1291, 151, '开封县', 3),
(1292, 151, '兰考县', 3),
(1293, 152, '北关区', 3),
(1294, 152, '文峰区', 3),
(1295, 152, '殷都区', 3),
(1296, 152, '龙安区', 3),
(1297, 152, '林州市', 3),
(1298, 152, '安阳县', 3),
(1299, 152, '汤阴县', 3),
(1300, 152, '滑县', 3),
(1301, 152, '内黄县', 3),
(1302, 153, '淇滨区', 3),
(1303, 153, '山城区', 3),
(1304, 153, '鹤山区', 3),
(1305, 153, '浚县', 3),
(1306, 153, '淇县', 3),
(1307, 154, '济源市', 3),
(1308, 155, '解放区', 3),
(1309, 155, '中站区', 3),
(1310, 155, '马村区', 3),
(1311, 155, '山阳区', 3),
(1312, 155, '沁阳市', 3),
(1313, 155, '孟州市', 3),
(1314, 155, '修武县', 3),
(1315, 155, '博爱县', 3),
(1316, 155, '武陟县', 3),
(1317, 155, '温县', 3),
(1318, 156, '卧龙区', 3),
(1319, 156, '宛城区', 3),
(1320, 156, '邓州市', 3),
(1321, 156, '南召县', 3),
(1322, 156, '方城县', 3),
(1323, 156, '西峡县', 3),
(1324, 156, '镇平县', 3),
(1325, 156, '内乡县', 3),
(1326, 156, '淅川县', 3),
(1327, 156, '社旗县', 3),
(1328, 156, '唐河县', 3),
(1329, 156, '新野县', 3),
(1330, 156, '桐柏县', 3),
(1331, 157, '新华区', 3),
(1332, 157, '卫东区', 3),
(1333, 157, '湛河区', 3),
(1334, 157, '石龙区', 3),
(1335, 157, '舞钢市', 3),
(1336, 157, '汝州市', 3),
(1337, 157, '宝丰县', 3),
(1338, 157, '叶县', 3),
(1339, 157, '鲁山县', 3),
(1340, 157, '郏县', 3),
(1341, 158, '湖滨区', 3),
(1342, 158, '义马市', 3),
(1343, 158, '灵宝市', 3),
(1344, 158, '渑池县', 3),
(1345, 158, '陕县', 3),
(1346, 158, '卢氏县', 3),
(1347, 159, '梁园区', 3),
(1348, 159, '睢阳区', 3),
(1349, 159, '永城市', 3),
(1350, 159, '民权县', 3),
(1351, 159, '睢县', 3),
(1352, 159, '宁陵县', 3),
(1353, 159, '虞城县', 3),
(1354, 159, '柘城县', 3),
(1355, 159, '夏邑县', 3),
(1356, 160, '卫滨区', 3),
(1357, 160, '红旗区', 3),
(1358, 160, '凤泉区', 3),
(1359, 160, '牧野区', 3),
(1360, 160, '卫辉市', 3),
(1361, 160, '辉县市', 3),
(1362, 160, '新乡县', 3),
(1363, 160, '获嘉县', 3),
(1364, 160, '原阳县', 3),
(1365, 160, '延津县', 3),
(1366, 160, '封丘县', 3),
(1367, 160, '长垣县', 3),
(1368, 161, '浉河区', 3),
(1369, 161, '平桥区', 3),
(1370, 161, '罗山县', 3),
(1371, 161, '光山县', 3),
(1372, 161, '新县', 3),
(1373, 161, '商城县', 3),
(1374, 161, '固始县', 3),
(1375, 161, '潢川县', 3),
(1376, 161, '淮滨县', 3),
(1377, 161, '息县', 3),
(1378, 162, '魏都区', 3),
(1379, 162, '禹州市', 3),
(1380, 162, '长葛市', 3),
(1381, 162, '许昌县', 3),
(1382, 162, '鄢陵县', 3),
(1383, 162, '襄城县', 3),
(1384, 163, '川汇区', 3),
(1385, 163, '项城市', 3),
(1386, 163, '扶沟县', 3),
(1387, 163, '西华县', 3),
(1388, 163, '商水县', 3),
(1389, 163, '沈丘县', 3),
(1390, 163, '郸城县', 3),
(1391, 163, '淮阳县', 3),
(1392, 163, '太康县', 3),
(1393, 163, '鹿邑县', 3),
(1394, 164, '驿城区', 3),
(1395, 164, '西平县', 3),
(1396, 164, '上蔡县', 3),
(1397, 164, '平舆县', 3),
(1398, 164, '正阳县', 3),
(1399, 164, '确山县', 3),
(1400, 164, '泌阳县', 3),
(1401, 164, '汝南县', 3),
(1402, 164, '遂平县', 3),
(1403, 164, '新蔡县', 3),
(1404, 165, '郾城区', 3),
(1405, 165, '源汇区', 3),
(1406, 165, '召陵区', 3),
(1407, 165, '舞阳县', 3),
(1408, 165, '临颍县', 3),
(1409, 166, '华龙区', 3),
(1410, 166, '清丰县', 3),
(1411, 166, '南乐县', 3),
(1412, 166, '范县', 3),
(1413, 166, '台前县', 3),
(1414, 166, '濮阳县', 3),
(1415, 167, '道里区', 3),
(1416, 167, '南岗区', 3),
(1417, 167, '动力区', 3),
(1418, 167, '平房区', 3),
(1419, 167, '香坊区', 3),
(1420, 167, '太平区', 3),
(1421, 167, '道外区', 3),
(1422, 167, '阿城区', 3),
(1423, 167, '呼兰区', 3),
(1424, 167, '松北区', 3),
(1425, 167, '尚志市', 3),
(1426, 167, '双城市', 3),
(1427, 167, '五常市', 3),
(1428, 167, '方正县', 3),
(1429, 167, '宾县', 3),
(1430, 167, '依兰县', 3),
(1431, 167, '巴彦县', 3),
(1432, 167, '通河县', 3),
(1433, 167, '木兰县', 3),
(1434, 167, '延寿县', 3),
(1435, 168, '萨尔图区', 3),
(1436, 168, '红岗区', 3),
(1437, 168, '龙凤区', 3),
(1438, 168, '让胡路区', 3),
(1439, 168, '大同区', 3),
(1440, 168, '肇州县', 3),
(1441, 168, '肇源县', 3),
(1442, 168, '林甸县', 3),
(1443, 168, '杜尔伯特', 3),
(1444, 169, '呼玛县', 3),
(1445, 169, '漠河县', 3),
(1446, 169, '塔河县', 3),
(1447, 170, '兴山区', 3),
(1448, 170, '工农区', 3),
(1449, 170, '南山区', 3),
(1450, 170, '兴安区', 3),
(1451, 170, '向阳区', 3),
(1452, 170, '东山区', 3),
(1453, 170, '萝北县', 3),
(1454, 170, '绥滨县', 3),
(1455, 171, '爱辉区', 3),
(1456, 171, '五大连池市', 3),
(1457, 171, '北安市', 3),
(1458, 171, '嫩江县', 3),
(1459, 171, '逊克县', 3),
(1460, 171, '孙吴县', 3),
(1461, 172, '鸡冠区', 3),
(1462, 172, '恒山区', 3),
(1463, 172, '城子河区', 3),
(1464, 172, '滴道区', 3),
(1465, 172, '梨树区', 3),
(1466, 172, '虎林市', 3),
(1467, 172, '密山市', 3),
(1468, 172, '鸡东县', 3),
(1469, 173, '前进区', 3),
(1470, 173, '郊区', 3),
(1471, 173, '向阳区', 3),
(1472, 173, '东风区', 3),
(1473, 173, '同江市', 3),
(1474, 173, '富锦市', 3),
(1475, 173, '桦南县', 3),
(1476, 173, '桦川县', 3),
(1477, 173, '汤原县', 3),
(1478, 173, '抚远县', 3),
(1479, 174, '爱民区', 3),
(1480, 174, '东安区', 3),
(1481, 174, '阳明区', 3),
(1482, 174, '西安区', 3),
(1483, 174, '绥芬河市', 3),
(1484, 174, '海林市', 3),
(1485, 174, '宁安市', 3),
(1486, 174, '穆棱市', 3),
(1487, 174, '东宁县', 3),
(1488, 174, '林口县', 3),
(1489, 175, '桃山区', 3),
(1490, 175, '新兴区', 3),
(1491, 175, '茄子河区', 3),
(1492, 175, '勃利县', 3),
(1493, 176, '龙沙区', 3),
(1494, 176, '昂昂溪区', 3),
(1495, 176, '铁峰区', 3),
(1496, 176, '建华区', 3),
(1497, 176, '富拉尔基区', 3),
(1498, 176, '碾子山区', 3),
(1499, 176, '梅里斯达斡尔区', 3),
(1500, 176, '讷河市', 3),
(1501, 176, '龙江县', 3),
(1502, 176, '依安县', 3),
(1503, 176, '泰来县', 3),
(1504, 176, '甘南县', 3),
(1505, 176, '富裕县', 3),
(1506, 176, '克山县', 3),
(1507, 176, '克东县', 3),
(1508, 176, '拜泉县', 3),
(1509, 177, '尖山区', 3),
(1510, 177, '岭东区', 3),
(1511, 177, '四方台区', 3),
(1512, 177, '宝山区', 3),
(1513, 177, '集贤县', 3),
(1514, 177, '友谊县', 3),
(1515, 177, '宝清县', 3),
(1516, 177, '饶河县', 3),
(1517, 178, '北林区', 3),
(1518, 178, '安达市', 3),
(1519, 178, '肇东市', 3),
(1520, 178, '海伦市', 3),
(1521, 178, '望奎县', 3),
(1522, 178, '兰西县', 3),
(1523, 178, '青冈县', 3),
(1524, 178, '庆安县', 3),
(1525, 178, '明水县', 3),
(1526, 178, '绥棱县', 3),
(1527, 179, '伊春区', 3),
(1528, 179, '带岭区', 3),
(1529, 179, '南岔区', 3),
(1530, 179, '金山屯区', 3),
(1531, 179, '西林区', 3),
(1532, 179, '美溪区', 3),
(1533, 179, '乌马河区', 3),
(1534, 179, '翠峦区', 3),
(1535, 179, '友好区', 3),
(1536, 179, '上甘岭区', 3),
(1537, 179, '五营区', 3),
(1538, 179, '红星区', 3),
(1539, 179, '新青区', 3),
(1540, 179, '汤旺河区', 3),
(1541, 179, '乌伊岭区', 3),
(1542, 179, '铁力市', 3),
(1543, 179, '嘉荫县', 3),
(1544, 180, '江岸区', 3),
(1545, 180, '武昌区', 3),
(1546, 180, '江汉区', 3),
(1547, 180, '硚口区', 3),
(1548, 180, '汉阳区', 3),
(1549, 180, '青山区', 3),
(1550, 180, '洪山区', 3),
(1551, 180, '东西湖区', 3),
(1552, 180, '汉南区', 3),
(1553, 180, '蔡甸区', 3),
(1554, 180, '江夏区', 3),
(1555, 180, '黄陂区', 3),
(1556, 180, '新洲区', 3),
(1557, 180, '经济开发区', 3),
(1558, 181, '仙桃市', 3),
(1559, 182, '鄂城区', 3),
(1560, 182, '华容区', 3),
(1561, 182, '梁子湖区', 3),
(1562, 183, '黄州区', 3),
(1563, 183, '麻城市', 3),
(1564, 183, '武穴市', 3),
(1565, 183, '团风县', 3),
(1566, 183, '红安县', 3),
(1567, 183, '罗田县', 3),
(1568, 183, '英山县', 3),
(1569, 183, '浠水县', 3),
(1570, 183, '蕲春县', 3),
(1571, 183, '黄梅县', 3),
(1572, 184, '黄石港区', 3),
(1573, 184, '西塞山区', 3),
(1574, 184, '下陆区', 3),
(1575, 184, '铁山区', 3),
(1576, 184, '大冶市', 3),
(1577, 184, '阳新县', 3),
(1578, 185, '东宝区', 3),
(1579, 185, '掇刀区', 3),
(1580, 185, '钟祥市', 3),
(1581, 185, '京山县', 3),
(1582, 185, '沙洋县', 3),
(1583, 186, '沙市区', 3),
(1584, 186, '荆州区', 3),
(1585, 186, '石首市', 3),
(1586, 186, '洪湖市', 3),
(1587, 186, '松滋市', 3),
(1588, 186, '公安县', 3),
(1589, 186, '监利县', 3),
(1590, 186, '江陵县', 3),
(1591, 187, '潜江市', 3),
(1592, 188, '神农架林区', 3),
(1593, 189, '张湾区', 3),
(1594, 189, '茅箭区', 3),
(1595, 189, '丹江口市', 3),
(1596, 189, '郧县', 3),
(1597, 189, '郧西县', 3),
(1598, 189, '竹山县', 3),
(1599, 189, '竹溪县', 3),
(1600, 189, '房县', 3),
(1601, 190, '曾都区', 3),
(1602, 190, '广水市', 3),
(1603, 191, '天门市', 3),
(1604, 192, '咸安区', 3),
(1605, 192, '赤壁市', 3),
(1606, 192, '嘉鱼县', 3),
(1607, 192, '通城县', 3),
(1608, 192, '崇阳县', 3),
(1609, 192, '通山县', 3),
(1610, 193, '襄城区', 3),
(1611, 193, '樊城区', 3),
(1612, 193, '襄阳区', 3),
(1613, 193, '老河口市', 3),
(1614, 193, '枣阳市', 3),
(1615, 193, '宜城市', 3),
(1616, 193, '南漳县', 3),
(1617, 193, '谷城县', 3),
(1618, 193, '保康县', 3),
(1619, 194, '孝南区', 3),
(1620, 194, '应城市', 3),
(1621, 194, '安陆市', 3),
(1622, 194, '汉川市', 3),
(1623, 194, '孝昌县', 3),
(1624, 194, '大悟县', 3),
(1625, 194, '云梦县', 3),
(1626, 195, '长阳', 3),
(1627, 195, '五峰', 3),
(1628, 195, '西陵区', 3),
(1629, 195, '伍家岗区', 3),
(1630, 195, '点军区', 3),
(1631, 195, '猇亭区', 3),
(1632, 195, '夷陵区', 3),
(1633, 195, '宜都市', 3),
(1634, 195, '当阳市', 3),
(1635, 195, '枝江市', 3),
(1636, 195, '远安县', 3),
(1637, 195, '兴山县', 3),
(1638, 195, '秭归县', 3),
(1639, 196, '恩施市', 3),
(1640, 196, '利川市', 3),
(1641, 196, '建始县', 3),
(1642, 196, '巴东县', 3),
(1643, 196, '宣恩县', 3),
(1644, 196, '咸丰县', 3),
(1645, 196, '来凤县', 3),
(1646, 196, '鹤峰县', 3),
(1647, 197, '岳麓区', 3),
(1648, 197, '芙蓉区', 3),
(1649, 197, '天心区', 3),
(1650, 197, '开福区', 3),
(1651, 197, '雨花区', 3),
(1652, 197, '开发区', 3),
(1653, 197, '浏阳市', 3),
(1654, 197, '长沙县', 3),
(1655, 197, '望城县', 3),
(1656, 197, '宁乡县', 3),
(1657, 198, '永定区', 3),
(1658, 198, '武陵源区', 3),
(1659, 198, '慈利县', 3),
(1660, 198, '桑植县', 3),
(1661, 199, '武陵区', 3),
(1662, 199, '鼎城区', 3),
(1663, 199, '津市市', 3),
(1664, 199, '安乡县', 3),
(1665, 199, '汉寿县', 3),
(1666, 199, '澧县', 3),
(1667, 199, '临澧县', 3),
(1668, 199, '桃源县', 3),
(1669, 199, '石门县', 3),
(1670, 200, '北湖区', 3),
(1671, 200, '苏仙区', 3),
(1672, 200, '资兴市', 3),
(1673, 200, '桂阳县', 3),
(1674, 200, '宜章县', 3),
(1675, 200, '永兴县', 3),
(1676, 200, '嘉禾县', 3),
(1677, 200, '临武县', 3),
(1678, 200, '汝城县', 3),
(1679, 200, '桂东县', 3),
(1680, 200, '安仁县', 3),
(1681, 201, '雁峰区', 3),
(1682, 201, '珠晖区', 3),
(1683, 201, '石鼓区', 3),
(1684, 201, '蒸湘区', 3),
(1685, 201, '南岳区', 3),
(1686, 201, '耒阳市', 3),
(1687, 201, '常宁市', 3),
(1688, 201, '衡阳县', 3),
(1689, 201, '衡南县', 3),
(1690, 201, '衡山县', 3),
(1691, 201, '衡东县', 3),
(1692, 201, '祁东县', 3),
(1693, 202, '鹤城区', 3),
(1694, 202, '靖州', 3),
(1695, 202, '麻阳', 3),
(1696, 202, '通道', 3),
(1697, 202, '新晃', 3),
(1698, 202, '芷江', 3),
(1699, 202, '沅陵县', 3),
(1700, 202, '辰溪县', 3),
(1701, 202, '溆浦县', 3),
(1702, 202, '中方县', 3),
(1703, 202, '会同县', 3),
(1704, 202, '洪江市', 3),
(1705, 203, '娄星区', 3),
(1706, 203, '冷水江市', 3),
(1707, 203, '涟源市', 3),
(1708, 203, '双峰县', 3),
(1709, 203, '新化县', 3),
(1710, 204, '城步', 3),
(1711, 204, '双清区', 3),
(1712, 204, '大祥区', 3),
(1713, 204, '北塔区', 3),
(1714, 204, '武冈市', 3),
(1715, 204, '邵东县', 3),
(1716, 204, '新邵县', 3),
(1717, 204, '邵阳县', 3),
(1718, 204, '隆回县', 3),
(1719, 204, '洞口县', 3),
(1720, 204, '绥宁县', 3),
(1721, 204, '新宁县', 3),
(1722, 205, '岳塘区', 3),
(1723, 205, '雨湖区', 3),
(1724, 205, '湘乡市', 3),
(1725, 205, '韶山市', 3),
(1726, 205, '湘潭县', 3),
(1727, 206, '吉首市', 3),
(1728, 206, '泸溪县', 3),
(1729, 206, '凤凰县', 3),
(1730, 206, '花垣县', 3),
(1731, 206, '保靖县', 3),
(1732, 206, '古丈县', 3),
(1733, 206, '永顺县', 3),
(1734, 206, '龙山县', 3),
(1735, 207, '赫山区', 3),
(1736, 207, '资阳区', 3),
(1737, 207, '沅江市', 3),
(1738, 207, '南县', 3),
(1739, 207, '桃江县', 3),
(1740, 207, '安化县', 3),
(1741, 208, '江华', 3),
(1742, 208, '冷水滩区', 3),
(1743, 208, '零陵区', 3),
(1744, 208, '祁阳县', 3),
(1745, 208, '东安县', 3),
(1746, 208, '双牌县', 3),
(1747, 208, '道县', 3),
(1748, 208, '江永县', 3),
(1749, 208, '宁远县', 3),
(1750, 208, '蓝山县', 3),
(1751, 208, '新田县', 3),
(1752, 209, '岳阳楼区', 3),
(1753, 209, '君山区', 3),
(1754, 209, '云溪区', 3),
(1755, 209, '汨罗市', 3),
(1756, 209, '临湘市', 3),
(1757, 209, '岳阳县', 3),
(1758, 209, '华容县', 3),
(1759, 209, '湘阴县', 3),
(1760, 209, '平江县', 3),
(1761, 210, '天元区', 3),
(1762, 210, '荷塘区', 3),
(1763, 210, '芦淞区', 3),
(1764, 210, '石峰区', 3),
(1765, 210, '醴陵市', 3),
(1766, 210, '株洲县', 3),
(1767, 210, '攸县', 3),
(1768, 210, '茶陵县', 3),
(1769, 210, '炎陵县', 3),
(1770, 211, '朝阳区', 3),
(1771, 211, '宽城区', 3),
(1772, 211, '二道区', 3),
(1773, 211, '南关区', 3),
(1774, 211, '绿园区', 3),
(1775, 211, '双阳区', 3),
(1776, 211, '净月潭开发区', 3),
(1777, 211, '高新技术开发区', 3),
(1778, 211, '经济技术开发区', 3),
(1779, 211, '汽车产业开发区', 3),
(1780, 211, '德惠市', 3),
(1781, 211, '九台市', 3),
(1782, 211, '榆树市', 3),
(1783, 211, '农安县', 3),
(1784, 212, '船营区', 3),
(1785, 212, '昌邑区', 3),
(1786, 212, '龙潭区', 3),
(1787, 212, '丰满区', 3),
(1788, 212, '蛟河市', 3),
(1789, 212, '桦甸市', 3),
(1790, 212, '舒兰市', 3),
(1791, 212, '磐石市', 3),
(1792, 212, '永吉县', 3),
(1793, 213, '洮北区', 3),
(1794, 213, '洮南市', 3),
(1795, 213, '大安市', 3),
(1796, 213, '镇赉县', 3),
(1797, 213, '通榆县', 3),
(1798, 214, '江源区', 3),
(1799, 214, '八道江区', 3),
(1800, 214, '长白', 3),
(1801, 214, '临江市', 3),
(1802, 214, '抚松县', 3),
(1803, 214, '靖宇县', 3),
(1804, 215, '龙山区', 3),
(1805, 215, '西安区', 3),
(1806, 215, '东丰县', 3),
(1807, 215, '东辽县', 3),
(1808, 216, '铁西区', 3),
(1809, 216, '铁东区', 3),
(1810, 216, '伊通', 3),
(1811, 216, '公主岭市', 3),
(1812, 216, '双辽市', 3),
(1813, 216, '梨树县', 3),
(1814, 217, '前郭尔罗斯', 3),
(1815, 217, '宁江区', 3),
(1816, 217, '长岭县', 3),
(1817, 217, '乾安县', 3),
(1818, 217, '扶余县', 3),
(1819, 218, '东昌区', 3),
(1820, 218, '二道江区', 3),
(1821, 218, '梅河口市', 3),
(1822, 218, '集安市', 3),
(1823, 218, '通化县', 3),
(1824, 218, '辉南县', 3),
(1825, 218, '柳河县', 3),
(1826, 219, '延吉市', 3),
(1827, 219, '图们市', 3),
(1828, 219, '敦化市', 3),
(1829, 219, '珲春市', 3),
(1830, 219, '龙井市', 3),
(1831, 219, '和龙市', 3),
(1832, 219, '安图县', 3),
(1833, 219, '汪清县', 3),
(1834, 220, '玄武区', 3),
(1835, 220, '鼓楼区', 3),
(1836, 220, '白下区', 3),
(1837, 220, '建邺区', 3),
(1838, 220, '秦淮区', 3),
(1839, 220, '雨花台区', 3),
(1840, 220, '下关区', 3),
(1841, 220, '栖霞区', 3),
(1842, 220, '浦口区', 3),
(1843, 220, '江宁区', 3),
(1844, 220, '六合区', 3),
(1845, 220, '溧水县', 3),
(1846, 220, '高淳县', 3),
(1847, 221, '沧浪区', 3),
(1848, 221, '金阊区', 3),
(1849, 221, '平江区', 3),
(1850, 221, '虎丘区', 3),
(1851, 221, '吴中区', 3),
(1852, 221, '相城区', 3),
(1853, 221, '园区', 3),
(1854, 221, '新区', 3),
(1855, 221, '常熟市', 3),
(1856, 221, '张家港市', 3),
(1857, 221, '玉山镇', 3),
(1858, 221, '巴城镇', 3),
(1859, 221, '周市镇', 3),
(1860, 221, '陆家镇', 3),
(1861, 221, '花桥镇', 3),
(1862, 221, '淀山湖镇', 3),
(1863, 221, '张浦镇', 3),
(1864, 221, '周庄镇', 3),
(1865, 221, '千灯镇', 3),
(1866, 221, '锦溪镇', 3),
(1867, 221, '开发区', 3),
(1868, 221, '吴江市', 3),
(1869, 221, '太仓市', 3),
(1870, 222, '崇安区', 3),
(1871, 222, '北塘区', 3),
(1872, 222, '南长区', 3),
(1873, 222, '锡山区', 3),
(1874, 222, '惠山区', 3),
(1875, 222, '滨湖区', 3),
(1876, 222, '新区', 3),
(1877, 222, '江阴市', 3),
(1878, 222, '宜兴市', 3),
(1879, 223, '天宁区', 3),
(1880, 223, '钟楼区', 3),
(1881, 223, '戚墅堰区', 3),
(1882, 223, '郊区', 3),
(1883, 223, '新北区', 3),
(1884, 223, '武进区', 3),
(1885, 223, '溧阳市', 3),
(1886, 223, '金坛市', 3),
(1887, 224, '清河区', 3),
(1888, 224, '清浦区', 3),
(1889, 224, '楚州区', 3),
(1890, 224, '淮阴区', 3),
(1891, 224, '涟水县', 3),
(1892, 224, '洪泽县', 3),
(1893, 224, '盱眙县', 3),
(1894, 224, '金湖县', 3),
(1895, 225, '新浦区', 3),
(1896, 225, '连云区', 3),
(1897, 225, '海州区', 3),
(1898, 225, '赣榆县', 3),
(1899, 225, '东海县', 3),
(1900, 225, '灌云县', 3),
(1901, 225, '灌南县', 3),
(1902, 226, '崇川区', 3),
(1903, 226, '港闸区', 3),
(1904, 226, '经济开发区', 3),
(1905, 226, '启东市', 3),
(1906, 226, '如皋市', 3),
(1907, 226, '通州市', 3),
(1908, 226, '海门市', 3),
(1909, 226, '海安县', 3),
(1910, 226, '如东县', 3),
(1911, 227, '宿城区', 3),
(1912, 227, '宿豫区', 3),
(1913, 227, '宿豫县', 3),
(1914, 227, '沭阳县', 3),
(1915, 227, '泗阳县', 3),
(1916, 227, '泗洪县', 3),
(1917, 228, '海陵区', 3),
(1918, 228, '高港区', 3),
(1919, 228, '兴化市', 3),
(1920, 228, '靖江市', 3),
(1921, 228, '泰兴市', 3),
(1922, 228, '姜堰市', 3),
(1923, 229, '云龙区', 3),
(1924, 229, '鼓楼区', 3),
(1925, 229, '九里区', 3),
(1926, 229, '贾汪区', 3),
(1927, 229, '泉山区', 3),
(1928, 229, '新沂市', 3),
(1929, 229, '邳州市', 3),
(1930, 229, '丰县', 3),
(1931, 229, '沛县', 3),
(1932, 229, '铜山县', 3),
(1933, 229, '睢宁县', 3),
(1934, 230, '城区', 3),
(1935, 230, '亭湖区', 3),
(1936, 230, '盐都区', 3),
(1937, 230, '盐都县', 3),
(1938, 230, '东台市', 3),
(1939, 230, '大丰市', 3),
(1940, 230, '响水县', 3),
(1941, 230, '滨海县', 3),
(1942, 230, '阜宁县', 3),
(1943, 230, '射阳县', 3),
(1944, 230, '建湖县', 3),
(1945, 231, '广陵区', 3),
(1946, 231, '维扬区', 3),
(1947, 231, '邗江区', 3),
(1948, 231, '仪征市', 3),
(1949, 231, '高邮市', 3),
(1950, 231, '江都市', 3),
(1951, 231, '宝应县', 3),
(1952, 232, '京口区', 3),
(1953, 232, '润州区', 3),
(1954, 232, '丹徒区', 3),
(1955, 232, '丹阳市', 3),
(1956, 232, '扬中市', 3),
(1957, 232, '句容市', 3),
(1958, 233, '东湖区', 3),
(1959, 233, '西湖区', 3),
(1960, 233, '青云谱区', 3),
(1961, 233, '湾里区', 3),
(1962, 233, '青山湖区', 3),
(1963, 233, '红谷滩新区', 3),
(1964, 233, '昌北区', 3),
(1965, 233, '高新区', 3),
(1966, 233, '南昌县', 3),
(1967, 233, '新建县', 3),
(1968, 233, '安义县', 3),
(1969, 233, '进贤县', 3),
(1970, 234, '临川区', 3),
(1971, 234, '南城县', 3),
(1972, 234, '黎川县', 3),
(1973, 234, '南丰县', 3),
(1974, 234, '崇仁县', 3),
(1975, 234, '乐安县', 3),
(1976, 234, '宜黄县', 3),
(1977, 234, '金溪县', 3),
(1978, 234, '资溪县', 3),
(1979, 234, '东乡县', 3),
(1980, 234, '广昌县', 3),
(1981, 235, '章贡区', 3),
(1982, 235, '于都县', 3),
(1983, 235, '瑞金市', 3),
(1984, 235, '南康市', 3),
(1985, 235, '赣县', 3),
(1986, 235, '信丰县', 3),
(1987, 235, '大余县', 3),
(1988, 235, '上犹县', 3),
(1989, 235, '崇义县', 3),
(1990, 235, '安远县', 3),
(1991, 235, '龙南县', 3),
(1992, 235, '定南县', 3),
(1993, 235, '全南县', 3),
(1994, 235, '宁都县', 3),
(1995, 235, '兴国县', 3),
(1996, 235, '会昌县', 3),
(1997, 235, '寻乌县', 3),
(1998, 235, '石城县', 3),
(1999, 236, '安福县', 3),
(2000, 236, '吉州区', 3),
(2001, 236, '青原区', 3),
(2002, 236, '井冈山市', 3),
(2003, 236, '吉安县', 3),
(2004, 236, '吉水县', 3),
(2005, 236, '峡江县', 3),
(2006, 236, '新干县', 3),
(2007, 236, '永丰县', 3),
(2008, 236, '泰和县', 3),
(2009, 236, '遂川县', 3),
(2010, 236, '万安县', 3),
(2011, 236, '永新县', 3),
(2012, 237, '珠山区', 3),
(2013, 237, '昌江区', 3),
(2014, 237, '乐平市', 3),
(2015, 237, '浮梁县', 3),
(2016, 238, '浔阳区', 3),
(2017, 238, '庐山区', 3),
(2018, 238, '瑞昌市', 3),
(2019, 238, '九江县', 3),
(2020, 238, '武宁县', 3),
(2021, 238, '修水县', 3),
(2022, 238, '永修县', 3),
(2023, 238, '德安县', 3),
(2024, 238, '星子县', 3),
(2025, 238, '都昌县', 3),
(2026, 238, '湖口县', 3),
(2027, 238, '彭泽县', 3),
(2028, 239, '安源区', 3),
(2029, 239, '湘东区', 3),
(2030, 239, '莲花县', 3),
(2031, 239, '芦溪县', 3),
(2032, 239, '上栗县', 3),
(2033, 240, '信州区', 3),
(2034, 240, '德兴市', 3),
(2035, 240, '上饶县', 3),
(2036, 240, '广丰县', 3),
(2037, 240, '玉山县', 3),
(2038, 240, '铅山县', 3),
(2039, 240, '横峰县', 3),
(2040, 240, '弋阳县', 3),
(2041, 240, '余干县', 3),
(2042, 240, '波阳县', 3),
(2043, 240, '万年县', 3),
(2044, 240, '婺源县', 3),
(2045, 241, '渝水区', 3),
(2046, 241, '分宜县', 3),
(2047, 242, '袁州区', 3),
(2048, 242, '丰城市', 3),
(2049, 242, '樟树市', 3),
(2050, 242, '高安市', 3),
(2051, 242, '奉新县', 3),
(2052, 242, '万载县', 3),
(2053, 242, '上高县', 3),
(2054, 242, '宜丰县', 3),
(2055, 242, '靖安县', 3),
(2056, 242, '铜鼓县', 3),
(2057, 243, '月湖区', 3),
(2058, 243, '贵溪市', 3),
(2059, 243, '余江县', 3),
(2060, 244, '沈河区', 3),
(2061, 244, '皇姑区', 3),
(2062, 244, '和平区', 3),
(2063, 244, '大东区', 3),
(2064, 244, '铁西区', 3),
(2065, 244, '苏家屯区', 3),
(2066, 244, '东陵区', 3),
(2067, 244, '沈北新区', 3),
(2068, 244, '于洪区', 3),
(2069, 244, '浑南新区', 3),
(2070, 244, '新民市', 3),
(2071, 244, '辽中县', 3),
(2072, 244, '康平县', 3),
(2073, 244, '法库县', 3),
(2074, 245, '西岗区', 3),
(2075, 245, '中山区', 3),
(2076, 245, '沙河口区', 3),
(2077, 245, '甘井子区', 3),
(2078, 245, '旅顺口区', 3),
(2079, 245, '金州区', 3),
(2080, 245, '开发区', 3),
(2081, 245, '瓦房店市', 3),
(2082, 245, '普兰店市', 3),
(2083, 245, '庄河市', 3),
(2084, 245, '长海县', 3),
(2085, 246, '铁东区', 3),
(2086, 246, '铁西区', 3),
(2087, 246, '立山区', 3),
(2088, 246, '千山区', 3),
(2089, 246, '岫岩', 3),
(2090, 246, '海城市', 3),
(2091, 246, '台安县', 3),
(2092, 247, '本溪', 3),
(2093, 247, '平山区', 3),
(2094, 247, '明山区', 3),
(2095, 247, '溪湖区', 3),
(2096, 247, '南芬区', 3),
(2097, 247, '桓仁', 3),
(2098, 248, '双塔区', 3),
(2099, 248, '龙城区', 3),
(2100, 248, '喀喇沁左翼蒙古族自治县', 3),
(2101, 248, '北票市', 3),
(2102, 248, '凌源市', 3),
(2103, 248, '朝阳县', 3),
(2104, 248, '建平县', 3),
(2105, 249, '振兴区', 3),
(2106, 249, '元宝区', 3),
(2107, 249, '振安区', 3),
(2108, 249, '宽甸', 3),
(2109, 249, '东港市', 3),
(2110, 249, '凤城市', 3),
(2111, 250, '顺城区', 3),
(2112, 250, '新抚区', 3),
(2113, 250, '东洲区', 3),
(2114, 250, '望花区', 3),
(2115, 250, '清原', 3),
(2116, 250, '新宾', 3),
(2117, 250, '抚顺县', 3),
(2118, 251, '阜新', 3),
(2119, 251, '海州区', 3),
(2120, 251, '新邱区', 3),
(2121, 251, '太平区', 3),
(2122, 251, '清河门区', 3),
(2123, 251, '细河区', 3),
(2124, 251, '彰武县', 3),
(2125, 252, '龙港区', 3),
(2126, 252, '南票区', 3),
(2127, 252, '连山区', 3),
(2128, 252, '兴城市', 3),
(2129, 252, '绥中县', 3),
(2130, 252, '建昌县', 3),
(2131, 253, '太和区', 3),
(2132, 253, '古塔区', 3),
(2133, 253, '凌河区', 3),
(2134, 253, '凌海市', 3),
(2135, 253, '北镇市', 3),
(2136, 253, '黑山县', 3),
(2137, 253, '义县', 3),
(2138, 254, '白塔区', 3),
(2139, 254, '文圣区', 3),
(2140, 254, '宏伟区', 3),
(2141, 254, '太子河区', 3),
(2142, 254, '弓长岭区', 3),
(2143, 254, '灯塔市', 3),
(2144, 254, '辽阳县', 3),
(2145, 255, '双台子区', 3),
(2146, 255, '兴隆台区', 3),
(2147, 255, '大洼县', 3),
(2148, 255, '盘山县', 3),
(2149, 256, '银州区', 3),
(2150, 256, '清河区', 3),
(2151, 256, '调兵山市', 3),
(2152, 256, '开原市', 3),
(2153, 256, '铁岭县', 3),
(2154, 256, '西丰县', 3),
(2155, 256, '昌图县', 3),
(2156, 257, '站前区', 3),
(2157, 257, '西市区', 3),
(2158, 257, '鲅鱼圈区', 3),
(2159, 257, '老边区', 3),
(2160, 257, '盖州市', 3),
(2161, 257, '大石桥市', 3),
(2162, 258, '回民区', 3),
(2163, 258, '玉泉区', 3),
(2164, 258, '新城区', 3),
(2165, 258, '赛罕区', 3),
(2166, 258, '清水河县', 3),
(2167, 258, '土默特左旗', 3),
(2168, 258, '托克托县', 3),
(2169, 258, '和林格尔县', 3),
(2170, 258, '武川县', 3),
(2171, 259, '阿拉善左旗', 3),
(2172, 259, '阿拉善右旗', 3),
(2173, 259, '额济纳旗', 3),
(2174, 260, '临河区', 3),
(2175, 260, '五原县', 3),
(2176, 260, '磴口县', 3),
(2177, 260, '乌拉特前旗', 3),
(2178, 260, '乌拉特中旗', 3),
(2179, 260, '乌拉特后旗', 3),
(2180, 260, '杭锦后旗', 3),
(2181, 261, '昆都仑区', 3),
(2182, 261, '青山区', 3),
(2183, 261, '东河区', 3),
(2184, 261, '九原区', 3),
(2185, 261, '石拐区', 3),
(2186, 261, '白云矿区', 3),
(2187, 261, '土默特右旗', 3),
(2188, 261, '固阳县', 3),
(2189, 261, '达尔罕茂明安联合旗', 3),
(2190, 262, '红山区', 3),
(2191, 262, '元宝山区', 3),
(2192, 262, '松山区', 3),
(2193, 262, '阿鲁科尔沁旗', 3),
(2194, 262, '巴林左旗', 3),
(2195, 262, '巴林右旗', 3),
(2196, 262, '林西县', 3),
(2197, 262, '克什克腾旗', 3),
(2198, 262, '翁牛特旗', 3),
(2199, 262, '喀喇沁旗', 3),
(2200, 262, '宁城县', 3),
(2201, 262, '敖汉旗', 3),
(2202, 263, '东胜区', 3),
(2203, 263, '达拉特旗', 3),
(2204, 263, '准格尔旗', 3),
(2205, 263, '鄂托克前旗', 3),
(2206, 263, '鄂托克旗', 3),
(2207, 263, '杭锦旗', 3),
(2208, 263, '乌审旗', 3),
(2209, 263, '伊金霍洛旗', 3),
(2210, 264, '海拉尔区', 3),
(2211, 264, '莫力达瓦', 3),
(2212, 264, '满洲里市', 3),
(2213, 264, '牙克石市', 3),
(2214, 264, '扎兰屯市', 3),
(2215, 264, '额尔古纳市', 3),
(2216, 264, '根河市', 3),
(2217, 264, '阿荣旗', 3),
(2218, 264, '鄂伦春自治旗', 3),
(2219, 264, '鄂温克族自治旗', 3),
(2220, 264, '陈巴尔虎旗', 3),
(2221, 264, '新巴尔虎左旗', 3),
(2222, 264, '新巴尔虎右旗', 3),
(2223, 265, '科尔沁区', 3),
(2224, 265, '霍林郭勒市', 3),
(2225, 265, '科尔沁左翼中旗', 3),
(2226, 265, '科尔沁左翼后旗', 3),
(2227, 265, '开鲁县', 3),
(2228, 265, '库伦旗', 3),
(2229, 265, '奈曼旗', 3),
(2230, 265, '扎鲁特旗', 3),
(2231, 266, '海勃湾区', 3),
(2232, 266, '乌达区', 3),
(2233, 266, '海南区', 3),
(2234, 267, '化德县', 3),
(2235, 267, '集宁区', 3),
(2236, 267, '丰镇市', 3),
(2237, 267, '卓资县', 3),
(2238, 267, '商都县', 3),
(2239, 267, '兴和县', 3),
(2240, 267, '凉城县', 3),
(2241, 267, '察哈尔右翼前旗', 3),
(2242, 267, '察哈尔右翼中旗', 3),
(2243, 267, '察哈尔右翼后旗', 3),
(2244, 267, '四子王旗', 3),
(2245, 268, '二连浩特市', 3),
(2246, 268, '锡林浩特市', 3),
(2247, 268, '阿巴嘎旗', 3),
(2248, 268, '苏尼特左旗', 3),
(2249, 268, '苏尼特右旗', 3),
(2250, 268, '东乌珠穆沁旗', 3),
(2251, 268, '西乌珠穆沁旗', 3),
(2252, 268, '太仆寺旗', 3),
(2253, 268, '镶黄旗', 3),
(2254, 268, '正镶白旗', 3),
(2255, 268, '正蓝旗', 3),
(2256, 268, '多伦县', 3),
(2257, 269, '乌兰浩特市', 3),
(2258, 269, '阿尔山市', 3),
(2259, 269, '科尔沁右翼前旗', 3),
(2260, 269, '科尔沁右翼中旗', 3),
(2261, 269, '扎赉特旗', 3),
(2262, 269, '突泉县', 3),
(2263, 270, '西夏区', 3),
(2264, 270, '金凤区', 3),
(2265, 270, '兴庆区', 3),
(2266, 270, '灵武市', 3),
(2267, 270, '永宁县', 3),
(2268, 270, '贺兰县', 3),
(2269, 271, '原州区', 3),
(2270, 271, '海原县', 3),
(2271, 271, '西吉县', 3),
(2272, 271, '隆德县', 3),
(2273, 271, '泾源县', 3),
(2274, 271, '彭阳县', 3),
(2275, 272, '惠农县', 3),
(2276, 272, '大武口区', 3),
(2277, 272, '惠农区', 3),
(2278, 272, '陶乐县', 3),
(2279, 272, '平罗县', 3),
(2280, 273, '利通区', 3),
(2281, 273, '中卫县', 3),
(2282, 273, '青铜峡市', 3),
(2283, 273, '中宁县', 3),
(2284, 273, '盐池县', 3),
(2285, 273, '同心县', 3),
(2286, 274, '沙坡头区', 3),
(2287, 274, '海原县', 3),
(2288, 274, '中宁县', 3),
(2289, 275, '城中区', 3),
(2290, 275, '城东区', 3),
(2291, 275, '城西区', 3),
(2292, 275, '城北区', 3),
(2293, 275, '湟中县', 3),
(2294, 275, '湟源县', 3),
(2295, 275, '大通', 3),
(2296, 276, '玛沁县', 3),
(2297, 276, '班玛县', 3),
(2298, 276, '甘德县', 3),
(2299, 276, '达日县', 3),
(2300, 276, '久治县', 3),
(2301, 276, '玛多县', 3),
(2302, 277, '海晏县', 3),
(2303, 277, '祁连县', 3),
(2304, 277, '刚察县', 3),
(2305, 277, '门源', 3),
(2306, 278, '平安县', 3),
(2307, 278, '乐都县', 3),
(2308, 278, '民和', 3),
(2309, 278, '互助', 3),
(2310, 278, '化隆', 3),
(2311, 278, '循化', 3),
(2312, 279, '共和县', 3),
(2313, 279, '同德县', 3),
(2314, 279, '贵德县', 3),
(2315, 279, '兴海县', 3),
(2316, 279, '贵南县', 3),
(2317, 280, '德令哈市', 3),
(2318, 280, '格尔木市', 3),
(2319, 280, '乌兰县', 3),
(2320, 280, '都兰县', 3),
(2321, 280, '天峻县', 3),
(2322, 281, '同仁县', 3),
(2323, 281, '尖扎县', 3),
(2324, 281, '泽库县', 3),
(2325, 281, '河南蒙古族自治县', 3),
(2326, 282, '玉树县', 3),
(2327, 282, '杂多县', 3),
(2328, 282, '称多县', 3),
(2329, 282, '治多县', 3),
(2330, 282, '囊谦县', 3),
(2331, 282, '曲麻莱县', 3),
(2332, 283, '市中区', 3),
(2333, 283, '历下区', 3),
(2334, 283, '天桥区', 3),
(2335, 283, '槐荫区', 3),
(2336, 283, '历城区', 3),
(2337, 283, '长清区', 3),
(2338, 283, '章丘市', 3),
(2339, 283, '平阴县', 3),
(2340, 283, '济阳县', 3),
(2341, 283, '商河县', 3),
(2342, 284, '市南区', 3),
(2343, 284, '市北区', 3),
(2344, 284, '城阳区', 3),
(2345, 284, '四方区', 3),
(2346, 284, '李沧区', 3),
(2347, 284, '黄岛区', 3),
(2348, 284, '崂山区', 3),
(2349, 284, '胶州市', 3),
(2350, 284, '即墨市', 3),
(2351, 284, '平度市', 3),
(2352, 284, '胶南市', 3),
(2353, 284, '莱西市', 3),
(2354, 285, '滨城区', 3),
(2355, 285, '惠民县', 3),
(2356, 285, '阳信县', 3),
(2357, 285, '无棣县', 3),
(2358, 285, '沾化县', 3),
(2359, 285, '博兴县', 3),
(2360, 285, '邹平县', 3),
(2361, 286, '德城区', 3),
(2362, 286, '陵县', 3),
(2363, 286, '乐陵市', 3),
(2364, 286, '禹城市', 3),
(2365, 286, '宁津县', 3),
(2366, 286, '庆云县', 3),
(2367, 286, '临邑县', 3),
(2368, 286, '齐河县', 3),
(2369, 286, '平原县', 3),
(2370, 286, '夏津县', 3),
(2371, 286, '武城县', 3),
(2372, 287, '东营区', 3),
(2373, 287, '河口区', 3),
(2374, 287, '垦利县', 3),
(2375, 287, '利津县', 3),
(2376, 287, '广饶县', 3),
(2377, 288, '牡丹区', 3),
(2378, 288, '曹县', 3),
(2379, 288, '单县', 3),
(2380, 288, '成武县', 3),
(2381, 288, '巨野县', 3),
(2382, 288, '郓城县', 3),
(2383, 288, '鄄城县', 3),
(2384, 288, '定陶县', 3),
(2385, 288, '东明县', 3),
(2386, 289, '市中区', 3),
(2387, 289, '任城区', 3),
(2388, 289, '曲阜市', 3),
(2389, 289, '兖州市', 3),
(2390, 289, '邹城市', 3),
(2391, 289, '微山县', 3),
(2392, 289, '鱼台县', 3),
(2393, 289, '金乡县', 3),
(2394, 289, '嘉祥县', 3),
(2395, 289, '汶上县', 3),
(2396, 289, '泗水县', 3),
(2397, 289, '梁山县', 3),
(2398, 290, '莱城区', 3),
(2399, 290, '钢城区', 3),
(2400, 291, '东昌府区', 3),
(2401, 291, '临清市', 3),
(2402, 291, '阳谷县', 3),
(2403, 291, '莘县', 3),
(2404, 291, '茌平县', 3),
(2405, 291, '东阿县', 3),
(2406, 291, '冠县', 3),
(2407, 291, '高唐县', 3),
(2408, 292, '兰山区', 3),
(2409, 292, '罗庄区', 3),
(2410, 292, '河东区', 3),
(2411, 292, '沂南县', 3),
(2412, 292, '郯城县', 3),
(2413, 292, '沂水县', 3),
(2414, 292, '苍山县', 3),
(2415, 292, '费县', 3),
(2416, 292, '平邑县', 3),
(2417, 292, '莒南县', 3),
(2418, 292, '蒙阴县', 3),
(2419, 292, '临沭县', 3),
(2420, 293, '东港区', 3),
(2421, 293, '岚山区', 3),
(2422, 293, '五莲县', 3),
(2423, 293, '莒县', 3),
(2424, 294, '泰山区', 3),
(2425, 294, '岱岳区', 3),
(2426, 294, '新泰市', 3),
(2427, 294, '肥城市', 3),
(2428, 294, '宁阳县', 3),
(2429, 294, '东平县', 3),
(2430, 295, '荣成市', 3),
(2431, 295, '乳山市', 3),
(2432, 295, '环翠区', 3),
(2433, 295, '文登市', 3),
(2434, 296, '潍城区', 3),
(2435, 296, '寒亭区', 3),
(2436, 296, '坊子区', 3),
(2437, 296, '奎文区', 3),
(2438, 296, '青州市', 3),
(2439, 296, '诸城市', 3),
(2440, 296, '寿光市', 3),
(2441, 296, '安丘市', 3),
(2442, 296, '高密市', 3),
(2443, 296, '昌邑市', 3),
(2444, 296, '临朐县', 3),
(2445, 296, '昌乐县', 3),
(2446, 297, '芝罘区', 3),
(2447, 297, '福山区', 3),
(2448, 297, '牟平区', 3),
(2449, 297, '莱山区', 3),
(2450, 297, '开发区', 3),
(2451, 297, '龙口市', 3),
(2452, 297, '莱阳市', 3),
(2453, 297, '莱州市', 3),
(2454, 297, '蓬莱市', 3),
(2455, 297, '招远市', 3),
(2456, 297, '栖霞市', 3),
(2457, 297, '海阳市', 3),
(2458, 297, '长岛县', 3),
(2459, 298, '市中区', 3),
(2460, 298, '山亭区', 3),
(2461, 298, '峄城区', 3),
(2462, 298, '台儿庄区', 3),
(2463, 298, '薛城区', 3),
(2464, 298, '滕州市', 3),
(2465, 299, '张店区', 3),
(2466, 299, '临淄区', 3),
(2467, 299, '淄川区', 3),
(2468, 299, '博山区', 3),
(2469, 299, '周村区', 3),
(2470, 299, '桓台县', 3),
(2471, 299, '高青县', 3),
(2472, 299, '沂源县', 3),
(2473, 300, '杏花岭区', 3),
(2474, 300, '小店区', 3),
(2475, 300, '迎泽区', 3),
(2476, 300, '尖草坪区', 3),
(2477, 300, '万柏林区', 3),
(2478, 300, '晋源区', 3),
(2479, 300, '高新开发区', 3),
(2480, 300, '民营经济开发区', 3),
(2481, 300, '经济技术开发区', 3),
(2482, 300, '清徐县', 3),
(2483, 300, '阳曲县', 3),
(2484, 300, '娄烦县', 3),
(2485, 300, '古交市', 3),
(2486, 301, '城区', 3),
(2487, 301, '郊区', 3);
INSERT INTO `fay_regions` (`id`, `parent_id`, `name`, `type`) VALUES
(2488, 301, '沁县', 3),
(2489, 301, '潞城市', 3),
(2490, 301, '长治县', 3),
(2491, 301, '襄垣县', 3),
(2492, 301, '屯留县', 3),
(2493, 301, '平顺县', 3),
(2494, 301, '黎城县', 3),
(2495, 301, '壶关县', 3),
(2496, 301, '长子县', 3),
(2497, 301, '武乡县', 3),
(2498, 301, '沁源县', 3),
(2499, 302, '城区', 3),
(2500, 302, '矿区', 3),
(2501, 302, '南郊区', 3),
(2502, 302, '新荣区', 3),
(2503, 302, '阳高县', 3),
(2504, 302, '天镇县', 3),
(2505, 302, '广灵县', 3),
(2506, 302, '灵丘县', 3),
(2507, 302, '浑源县', 3),
(2508, 302, '左云县', 3),
(2509, 302, '大同县', 3),
(2510, 303, '城区', 3),
(2511, 303, '高平市', 3),
(2512, 303, '沁水县', 3),
(2513, 303, '阳城县', 3),
(2514, 303, '陵川县', 3),
(2515, 303, '泽州县', 3),
(2516, 304, '榆次区', 3),
(2517, 304, '介休市', 3),
(2518, 304, '榆社县', 3),
(2519, 304, '左权县', 3),
(2520, 304, '和顺县', 3),
(2521, 304, '昔阳县', 3),
(2522, 304, '寿阳县', 3),
(2523, 304, '太谷县', 3),
(2524, 304, '祁县', 3),
(2525, 304, '平遥县', 3),
(2526, 304, '灵石县', 3),
(2527, 305, '尧都区', 3),
(2528, 305, '侯马市', 3),
(2529, 305, '霍州市', 3),
(2530, 305, '曲沃县', 3),
(2531, 305, '翼城县', 3),
(2532, 305, '襄汾县', 3),
(2533, 305, '洪洞县', 3),
(2534, 305, '吉县', 3),
(2535, 305, '安泽县', 3),
(2536, 305, '浮山县', 3),
(2537, 305, '古县', 3),
(2538, 305, '乡宁县', 3),
(2539, 305, '大宁县', 3),
(2540, 305, '隰县', 3),
(2541, 305, '永和县', 3),
(2542, 305, '蒲县', 3),
(2543, 305, '汾西县', 3),
(2544, 306, '离石市', 3),
(2545, 306, '离石区', 3),
(2546, 306, '孝义市', 3),
(2547, 306, '汾阳市', 3),
(2548, 306, '文水县', 3),
(2549, 306, '交城县', 3),
(2550, 306, '兴县', 3),
(2551, 306, '临县', 3),
(2552, 306, '柳林县', 3),
(2553, 306, '石楼县', 3),
(2554, 306, '岚县', 3),
(2555, 306, '方山县', 3),
(2556, 306, '中阳县', 3),
(2557, 306, '交口县', 3),
(2558, 307, '朔城区', 3),
(2559, 307, '平鲁区', 3),
(2560, 307, '山阴县', 3),
(2561, 307, '应县', 3),
(2562, 307, '右玉县', 3),
(2563, 307, '怀仁县', 3),
(2564, 308, '忻府区', 3),
(2565, 308, '原平市', 3),
(2566, 308, '定襄县', 3),
(2567, 308, '五台县', 3),
(2568, 308, '代县', 3),
(2569, 308, '繁峙县', 3),
(2570, 308, '宁武县', 3),
(2571, 308, '静乐县', 3),
(2572, 308, '神池县', 3),
(2573, 308, '五寨县', 3),
(2574, 308, '岢岚县', 3),
(2575, 308, '河曲县', 3),
(2576, 308, '保德县', 3),
(2577, 308, '偏关县', 3),
(2578, 309, '城区', 3),
(2579, 309, '矿区', 3),
(2580, 309, '郊区', 3),
(2581, 309, '平定县', 3),
(2582, 309, '盂县', 3),
(2583, 310, '盐湖区', 3),
(2584, 310, '永济市', 3),
(2585, 310, '河津市', 3),
(2586, 310, '临猗县', 3),
(2587, 310, '万荣县', 3),
(2588, 310, '闻喜县', 3),
(2589, 310, '稷山县', 3),
(2590, 310, '新绛县', 3),
(2591, 310, '绛县', 3),
(2592, 310, '垣曲县', 3),
(2593, 310, '夏县', 3),
(2594, 310, '平陆县', 3),
(2595, 310, '芮城县', 3),
(2596, 311, '莲湖区', 3),
(2597, 311, '新城区', 3),
(2598, 311, '碑林区', 3),
(2599, 311, '雁塔区', 3),
(2600, 311, '灞桥区', 3),
(2601, 311, '未央区', 3),
(2602, 311, '阎良区', 3),
(2603, 311, '临潼区', 3),
(2604, 311, '长安区', 3),
(2605, 311, '蓝田县', 3),
(2606, 311, '周至县', 3),
(2607, 311, '户县', 3),
(2608, 311, '高陵县', 3),
(2609, 312, '汉滨区', 3),
(2610, 312, '汉阴县', 3),
(2611, 312, '石泉县', 3),
(2612, 312, '宁陕县', 3),
(2613, 312, '紫阳县', 3),
(2614, 312, '岚皋县', 3),
(2615, 312, '平利县', 3),
(2616, 312, '镇坪县', 3),
(2617, 312, '旬阳县', 3),
(2618, 312, '白河县', 3),
(2619, 313, '陈仓区', 3),
(2620, 313, '渭滨区', 3),
(2621, 313, '金台区', 3),
(2622, 313, '凤翔县', 3),
(2623, 313, '岐山县', 3),
(2624, 313, '扶风县', 3),
(2625, 313, '眉县', 3),
(2626, 313, '陇县', 3),
(2627, 313, '千阳县', 3),
(2628, 313, '麟游县', 3),
(2629, 313, '凤县', 3),
(2630, 313, '太白县', 3),
(2631, 314, '汉台区', 3),
(2632, 314, '南郑县', 3),
(2633, 314, '城固县', 3),
(2634, 314, '洋县', 3),
(2635, 314, '西乡县', 3),
(2636, 314, '勉县', 3),
(2637, 314, '宁强县', 3),
(2638, 314, '略阳县', 3),
(2639, 314, '镇巴县', 3),
(2640, 314, '留坝县', 3),
(2641, 314, '佛坪县', 3),
(2642, 315, '商州区', 3),
(2643, 315, '洛南县', 3),
(2644, 315, '丹凤县', 3),
(2645, 315, '商南县', 3),
(2646, 315, '山阳县', 3),
(2647, 315, '镇安县', 3),
(2648, 315, '柞水县', 3),
(2649, 316, '耀州区', 3),
(2650, 316, '王益区', 3),
(2651, 316, '印台区', 3),
(2652, 316, '宜君县', 3),
(2653, 317, '临渭区', 3),
(2654, 317, '韩城市', 3),
(2655, 317, '华阴市', 3),
(2656, 317, '华县', 3),
(2657, 317, '潼关县', 3),
(2658, 317, '大荔县', 3),
(2659, 317, '合阳县', 3),
(2660, 317, '澄城县', 3),
(2661, 317, '蒲城县', 3),
(2662, 317, '白水县', 3),
(2663, 317, '富平县', 3),
(2664, 318, '秦都区', 3),
(2665, 318, '渭城区', 3),
(2666, 318, '杨陵区', 3),
(2667, 318, '兴平市', 3),
(2668, 318, '三原县', 3),
(2669, 318, '泾阳县', 3),
(2670, 318, '乾县', 3),
(2671, 318, '礼泉县', 3),
(2672, 318, '永寿县', 3),
(2673, 318, '彬县', 3),
(2674, 318, '长武县', 3),
(2675, 318, '旬邑县', 3),
(2676, 318, '淳化县', 3),
(2677, 318, '武功县', 3),
(2678, 319, '吴起县', 3),
(2679, 319, '宝塔区', 3),
(2680, 319, '延长县', 3),
(2681, 319, '延川县', 3),
(2682, 319, '子长县', 3),
(2683, 319, '安塞县', 3),
(2684, 319, '志丹县', 3),
(2685, 319, '甘泉县', 3),
(2686, 319, '富县', 3),
(2687, 319, '洛川县', 3),
(2688, 319, '宜川县', 3),
(2689, 319, '黄龙县', 3),
(2690, 319, '黄陵县', 3),
(2691, 320, '榆阳区', 3),
(2692, 320, '神木县', 3),
(2693, 320, '府谷县', 3),
(2694, 320, '横山县', 3),
(2695, 320, '靖边县', 3),
(2696, 320, '定边县', 3),
(2697, 320, '绥德县', 3),
(2698, 320, '米脂县', 3),
(2699, 320, '佳县', 3),
(2700, 320, '吴堡县', 3),
(2701, 320, '清涧县', 3),
(2702, 320, '子洲县', 3),
(2703, 321, '长宁区', 3),
(2704, 321, '闸北区', 3),
(2705, 321, '闵行区', 3),
(2706, 321, '徐汇区', 3),
(2707, 321, '浦东新区', 3),
(2708, 321, '杨浦区', 3),
(2709, 321, '普陀区', 3),
(2710, 321, '静安区', 3),
(2711, 321, '卢湾区', 3),
(2712, 321, '虹口区', 3),
(2713, 321, '黄浦区', 3),
(2714, 321, '南汇区', 3),
(2715, 321, '松江区', 3),
(2716, 321, '嘉定区', 3),
(2717, 321, '宝山区', 3),
(2718, 321, '青浦区', 3),
(2719, 321, '金山区', 3),
(2720, 321, '奉贤区', 3),
(2721, 321, '崇明县', 3),
(2722, 322, '青羊区', 3),
(2723, 322, '锦江区', 3),
(2724, 322, '金牛区', 3),
(2725, 322, '武侯区', 3),
(2726, 322, '成华区', 3),
(2727, 322, '龙泉驿区', 3),
(2728, 322, '青白江区', 3),
(2729, 322, '新都区', 3),
(2730, 322, '温江区', 3),
(2731, 322, '高新区', 3),
(2732, 322, '高新西区', 3),
(2733, 322, '都江堰市', 3),
(2734, 322, '彭州市', 3),
(2735, 322, '邛崃市', 3),
(2736, 322, '崇州市', 3),
(2737, 322, '金堂县', 3),
(2738, 322, '双流县', 3),
(2739, 322, '郫县', 3),
(2740, 322, '大邑县', 3),
(2741, 322, '蒲江县', 3),
(2742, 322, '新津县', 3),
(2743, 322, '都江堰市', 3),
(2744, 322, '彭州市', 3),
(2745, 322, '邛崃市', 3),
(2746, 322, '崇州市', 3),
(2747, 322, '金堂县', 3),
(2748, 322, '双流县', 3),
(2749, 322, '郫县', 3),
(2750, 322, '大邑县', 3),
(2751, 322, '蒲江县', 3),
(2752, 322, '新津县', 3),
(2753, 323, '涪城区', 3),
(2754, 323, '游仙区', 3),
(2755, 323, '江油市', 3),
(2756, 323, '盐亭县', 3),
(2757, 323, '三台县', 3),
(2758, 323, '平武县', 3),
(2759, 323, '安县', 3),
(2760, 323, '梓潼县', 3),
(2761, 323, '北川县', 3),
(2762, 324, '马尔康县', 3),
(2763, 324, '汶川县', 3),
(2764, 324, '理县', 3),
(2765, 324, '茂县', 3),
(2766, 324, '松潘县', 3),
(2767, 324, '九寨沟县', 3),
(2768, 324, '金川县', 3),
(2769, 324, '小金县', 3),
(2770, 324, '黑水县', 3),
(2771, 324, '壤塘县', 3),
(2772, 324, '阿坝县', 3),
(2773, 324, '若尔盖县', 3),
(2774, 324, '红原县', 3),
(2775, 325, '巴州区', 3),
(2776, 325, '通江县', 3),
(2777, 325, '南江县', 3),
(2778, 325, '平昌县', 3),
(2779, 326, '通川区', 3),
(2780, 326, '万源市', 3),
(2781, 326, '达县', 3),
(2782, 326, '宣汉县', 3),
(2783, 326, '开江县', 3),
(2784, 326, '大竹县', 3),
(2785, 326, '渠县', 3),
(2786, 327, '旌阳区', 3),
(2787, 327, '广汉市', 3),
(2788, 327, '什邡市', 3),
(2789, 327, '绵竹市', 3),
(2790, 327, '罗江县', 3),
(2791, 327, '中江县', 3),
(2792, 328, '康定县', 3),
(2793, 328, '丹巴县', 3),
(2794, 328, '泸定县', 3),
(2795, 328, '炉霍县', 3),
(2796, 328, '九龙县', 3),
(2797, 328, '甘孜县', 3),
(2798, 328, '雅江县', 3),
(2799, 328, '新龙县', 3),
(2800, 328, '道孚县', 3),
(2801, 328, '白玉县', 3),
(2802, 328, '理塘县', 3),
(2803, 328, '德格县', 3),
(2804, 328, '乡城县', 3),
(2805, 328, '石渠县', 3),
(2806, 328, '稻城县', 3),
(2807, 328, '色达县', 3),
(2808, 328, '巴塘县', 3),
(2809, 328, '得荣县', 3),
(2810, 329, '广安区', 3),
(2811, 329, '华蓥市', 3),
(2812, 329, '岳池县', 3),
(2813, 329, '武胜县', 3),
(2814, 329, '邻水县', 3),
(2815, 330, '利州区', 3),
(2816, 330, '元坝区', 3),
(2817, 330, '朝天区', 3),
(2818, 330, '旺苍县', 3),
(2819, 330, '青川县', 3),
(2820, 330, '剑阁县', 3),
(2821, 330, '苍溪县', 3),
(2822, 331, '峨眉山市', 3),
(2823, 331, '乐山市', 3),
(2824, 331, '犍为县', 3),
(2825, 331, '井研县', 3),
(2826, 331, '夹江县', 3),
(2827, 331, '沐川县', 3),
(2828, 331, '峨边', 3),
(2829, 331, '马边', 3),
(2830, 332, '西昌市', 3),
(2831, 332, '盐源县', 3),
(2832, 332, '德昌县', 3),
(2833, 332, '会理县', 3),
(2834, 332, '会东县', 3),
(2835, 332, '宁南县', 3),
(2836, 332, '普格县', 3),
(2837, 332, '布拖县', 3),
(2838, 332, '金阳县', 3),
(2839, 332, '昭觉县', 3),
(2840, 332, '喜德县', 3),
(2841, 332, '冕宁县', 3),
(2842, 332, '越西县', 3),
(2843, 332, '甘洛县', 3),
(2844, 332, '美姑县', 3),
(2845, 332, '雷波县', 3),
(2846, 332, '木里', 3),
(2847, 333, '东坡区', 3),
(2848, 333, '仁寿县', 3),
(2849, 333, '彭山县', 3),
(2850, 333, '洪雅县', 3),
(2851, 333, '丹棱县', 3),
(2852, 333, '青神县', 3),
(2853, 334, '阆中市', 3),
(2854, 334, '南部县', 3),
(2855, 334, '营山县', 3),
(2856, 334, '蓬安县', 3),
(2857, 334, '仪陇县', 3),
(2858, 334, '顺庆区', 3),
(2859, 334, '高坪区', 3),
(2860, 334, '嘉陵区', 3),
(2861, 334, '西充县', 3),
(2862, 335, '市中区', 3),
(2863, 335, '东兴区', 3),
(2864, 335, '威远县', 3),
(2865, 335, '资中县', 3),
(2866, 335, '隆昌县', 3),
(2867, 336, '东  区', 3),
(2868, 336, '西  区', 3),
(2869, 336, '仁和区', 3),
(2870, 336, '米易县', 3),
(2871, 336, '盐边县', 3),
(2872, 337, '船山区', 3),
(2873, 337, '安居区', 3),
(2874, 337, '蓬溪县', 3),
(2875, 337, '射洪县', 3),
(2876, 337, '大英县', 3),
(2877, 338, '雨城区', 3),
(2878, 338, '名山县', 3),
(2879, 338, '荥经县', 3),
(2880, 338, '汉源县', 3),
(2881, 338, '石棉县', 3),
(2882, 338, '天全县', 3),
(2883, 338, '芦山县', 3),
(2884, 338, '宝兴县', 3),
(2885, 339, '翠屏区', 3),
(2886, 339, '宜宾县', 3),
(2887, 339, '南溪县', 3),
(2888, 339, '江安县', 3),
(2889, 339, '长宁县', 3),
(2890, 339, '高县', 3),
(2891, 339, '珙县', 3),
(2892, 339, '筠连县', 3),
(2893, 339, '兴文县', 3),
(2894, 339, '屏山县', 3),
(2895, 340, '雁江区', 3),
(2896, 340, '简阳市', 3),
(2897, 340, '安岳县', 3),
(2898, 340, '乐至县', 3),
(2899, 341, '大安区', 3),
(2900, 341, '自流井区', 3),
(2901, 341, '贡井区', 3),
(2902, 341, '沿滩区', 3),
(2903, 341, '荣县', 3),
(2904, 341, '富顺县', 3),
(2905, 342, '江阳区', 3),
(2906, 342, '纳溪区', 3),
(2907, 342, '龙马潭区', 3),
(2908, 342, '泸县', 3),
(2909, 342, '合江县', 3),
(2910, 342, '叙永县', 3),
(2911, 342, '古蔺县', 3),
(2912, 343, '和平区', 3),
(2913, 343, '河西区', 3),
(2914, 343, '南开区', 3),
(2915, 343, '河北区', 3),
(2916, 343, '河东区', 3),
(2917, 343, '红桥区', 3),
(2918, 343, '东丽区', 3),
(2919, 343, '津南区', 3),
(2920, 343, '西青区', 3),
(2921, 343, '北辰区', 3),
(2922, 343, '塘沽区', 3),
(2923, 343, '汉沽区', 3),
(2924, 343, '大港区', 3),
(2925, 343, '武清区', 3),
(2926, 343, '宝坻区', 3),
(2927, 343, '经济开发区', 3),
(2928, 343, '宁河县', 3),
(2929, 343, '静海县', 3),
(2930, 343, '蓟县', 3),
(2931, 344, '城关区', 3),
(2932, 344, '林周县', 3),
(2933, 344, '当雄县', 3),
(2934, 344, '尼木县', 3),
(2935, 344, '曲水县', 3),
(2936, 344, '堆龙德庆县', 3),
(2937, 344, '达孜县', 3),
(2938, 344, '墨竹工卡县', 3),
(2939, 345, '噶尔县', 3),
(2940, 345, '普兰县', 3),
(2941, 345, '札达县', 3),
(2942, 345, '日土县', 3),
(2943, 345, '革吉县', 3),
(2944, 345, '改则县', 3),
(2945, 345, '措勤县', 3),
(2946, 346, '昌都县', 3),
(2947, 346, '江达县', 3),
(2948, 346, '贡觉县', 3),
(2949, 346, '类乌齐县', 3),
(2950, 346, '丁青县', 3),
(2951, 346, '察雅县', 3),
(2952, 346, '八宿县', 3),
(2953, 346, '左贡县', 3),
(2954, 346, '芒康县', 3),
(2955, 346, '洛隆县', 3),
(2956, 346, '边坝县', 3),
(2957, 347, '林芝县', 3),
(2958, 347, '工布江达县', 3),
(2959, 347, '米林县', 3),
(2960, 347, '墨脱县', 3),
(2961, 347, '波密县', 3),
(2962, 347, '察隅县', 3),
(2963, 347, '朗县', 3),
(2964, 348, '那曲县', 3),
(2965, 348, '嘉黎县', 3),
(2966, 348, '比如县', 3),
(2967, 348, '聂荣县', 3),
(2968, 348, '安多县', 3),
(2969, 348, '申扎县', 3),
(2970, 348, '索县', 3),
(2971, 348, '班戈县', 3),
(2972, 348, '巴青县', 3),
(2973, 348, '尼玛县', 3),
(2974, 349, '日喀则市', 3),
(2975, 349, '南木林县', 3),
(2976, 349, '江孜县', 3),
(2977, 349, '定日县', 3),
(2978, 349, '萨迦县', 3),
(2979, 349, '拉孜县', 3),
(2980, 349, '昂仁县', 3),
(2981, 349, '谢通门县', 3),
(2982, 349, '白朗县', 3),
(2983, 349, '仁布县', 3),
(2984, 349, '康马县', 3),
(2985, 349, '定结县', 3),
(2986, 349, '仲巴县', 3),
(2987, 349, '亚东县', 3),
(2988, 349, '吉隆县', 3),
(2989, 349, '聂拉木县', 3),
(2990, 349, '萨嘎县', 3),
(2991, 349, '岗巴县', 3),
(2992, 350, '乃东县', 3),
(2993, 350, '扎囊县', 3),
(2994, 350, '贡嘎县', 3),
(2995, 350, '桑日县', 3),
(2996, 350, '琼结县', 3),
(2997, 350, '曲松县', 3),
(2998, 350, '措美县', 3),
(2999, 350, '洛扎县', 3),
(3000, 350, '加查县', 3),
(3001, 350, '隆子县', 3),
(3002, 350, '错那县', 3),
(3003, 350, '浪卡子县', 3),
(3004, 351, '天山区', 3),
(3005, 351, '沙依巴克区', 3),
(3006, 351, '新市区', 3),
(3007, 351, '水磨沟区', 3),
(3008, 351, '头屯河区', 3),
(3009, 351, '达坂城区', 3),
(3010, 351, '米东区', 3),
(3011, 351, '乌鲁木齐县', 3),
(3012, 352, '阿克苏市', 3),
(3013, 352, '温宿县', 3),
(3014, 352, '库车县', 3),
(3015, 352, '沙雅县', 3),
(3016, 352, '新和县', 3),
(3017, 352, '拜城县', 3),
(3018, 352, '乌什县', 3),
(3019, 352, '阿瓦提县', 3),
(3020, 352, '柯坪县', 3),
(3021, 353, '阿拉尔市', 3),
(3022, 354, '库尔勒市', 3),
(3023, 354, '轮台县', 3),
(3024, 354, '尉犁县', 3),
(3025, 354, '若羌县', 3),
(3026, 354, '且末县', 3),
(3027, 354, '焉耆', 3),
(3028, 354, '和静县', 3),
(3029, 354, '和硕县', 3),
(3030, 354, '博湖县', 3),
(3031, 355, '博乐市', 3),
(3032, 355, '精河县', 3),
(3033, 355, '温泉县', 3),
(3034, 356, '呼图壁县', 3),
(3035, 356, '米泉市', 3),
(3036, 356, '昌吉市', 3),
(3037, 356, '阜康市', 3),
(3038, 356, '玛纳斯县', 3),
(3039, 356, '奇台县', 3),
(3040, 356, '吉木萨尔县', 3),
(3041, 356, '木垒', 3),
(3042, 357, '哈密市', 3),
(3043, 357, '伊吾县', 3),
(3044, 357, '巴里坤', 3),
(3045, 358, '和田市', 3),
(3046, 358, '和田县', 3),
(3047, 358, '墨玉县', 3),
(3048, 358, '皮山县', 3),
(3049, 358, '洛浦县', 3),
(3050, 358, '策勒县', 3),
(3051, 358, '于田县', 3),
(3052, 358, '民丰县', 3),
(3053, 359, '喀什市', 3),
(3054, 359, '疏附县', 3),
(3055, 359, '疏勒县', 3),
(3056, 359, '英吉沙县', 3),
(3057, 359, '泽普县', 3),
(3058, 359, '莎车县', 3),
(3059, 359, '叶城县', 3),
(3060, 359, '麦盖提县', 3),
(3061, 359, '岳普湖县', 3),
(3062, 359, '伽师县', 3),
(3063, 359, '巴楚县', 3),
(3064, 359, '塔什库尔干', 3),
(3065, 360, '克拉玛依市', 3),
(3066, 361, '阿图什市', 3),
(3067, 361, '阿克陶县', 3),
(3068, 361, '阿合奇县', 3),
(3069, 361, '乌恰县', 3),
(3070, 362, '石河子市', 3),
(3071, 363, '图木舒克市', 3),
(3072, 364, '吐鲁番市', 3),
(3073, 364, '鄯善县', 3),
(3074, 364, '托克逊县', 3),
(3075, 365, '五家渠市', 3),
(3076, 366, '阿勒泰市', 3),
(3077, 366, '布克赛尔', 3),
(3078, 366, '伊宁市', 3),
(3079, 366, '布尔津县', 3),
(3080, 366, '奎屯市', 3),
(3081, 366, '乌苏市', 3),
(3082, 366, '额敏县', 3),
(3083, 366, '富蕴县', 3),
(3084, 366, '伊宁县', 3),
(3085, 366, '福海县', 3),
(3086, 366, '霍城县', 3),
(3087, 366, '沙湾县', 3),
(3088, 366, '巩留县', 3),
(3089, 366, '哈巴河县', 3),
(3090, 366, '托里县', 3),
(3091, 366, '青河县', 3),
(3092, 366, '新源县', 3),
(3093, 366, '裕民县', 3),
(3094, 366, '和布克赛尔', 3),
(3095, 366, '吉木乃县', 3),
(3096, 366, '昭苏县', 3),
(3097, 366, '特克斯县', 3),
(3098, 366, '尼勒克县', 3),
(3099, 366, '察布查尔', 3),
(3100, 367, '盘龙区', 3),
(3101, 367, '五华区', 3),
(3102, 367, '官渡区', 3),
(3103, 367, '西山区', 3),
(3104, 367, '东川区', 3),
(3105, 367, '安宁市', 3),
(3106, 367, '呈贡县', 3),
(3107, 367, '晋宁县', 3),
(3108, 367, '富民县', 3),
(3109, 367, '宜良县', 3),
(3110, 367, '嵩明县', 3),
(3111, 367, '石林县', 3),
(3112, 367, '禄劝', 3),
(3113, 367, '寻甸', 3),
(3114, 368, '兰坪', 3),
(3115, 368, '泸水县', 3),
(3116, 368, '福贡县', 3),
(3117, 368, '贡山', 3),
(3118, 369, '宁洱', 3),
(3119, 369, '思茅区', 3),
(3120, 369, '墨江', 3),
(3121, 369, '景东', 3),
(3122, 369, '景谷', 3),
(3123, 369, '镇沅', 3),
(3124, 369, '江城', 3),
(3125, 369, '孟连', 3),
(3126, 369, '澜沧', 3),
(3127, 369, '西盟', 3),
(3128, 370, '古城区', 3),
(3129, 370, '宁蒗', 3),
(3130, 370, '玉龙', 3),
(3131, 370, '永胜县', 3),
(3132, 370, '华坪县', 3),
(3133, 371, '隆阳区', 3),
(3134, 371, '施甸县', 3),
(3135, 371, '腾冲县', 3),
(3136, 371, '龙陵县', 3),
(3137, 371, '昌宁县', 3),
(3138, 372, '楚雄市', 3),
(3139, 372, '双柏县', 3),
(3140, 372, '牟定县', 3),
(3141, 372, '南华县', 3),
(3142, 372, '姚安县', 3),
(3143, 372, '大姚县', 3),
(3144, 372, '永仁县', 3),
(3145, 372, '元谋县', 3),
(3146, 372, '武定县', 3),
(3147, 372, '禄丰县', 3),
(3148, 373, '大理市', 3),
(3149, 373, '祥云县', 3),
(3150, 373, '宾川县', 3),
(3151, 373, '弥渡县', 3),
(3152, 373, '永平县', 3),
(3153, 373, '云龙县', 3),
(3154, 373, '洱源县', 3),
(3155, 373, '剑川县', 3),
(3156, 373, '鹤庆县', 3),
(3157, 373, '漾濞', 3),
(3158, 373, '南涧', 3),
(3159, 373, '巍山', 3),
(3160, 374, '潞西市', 3),
(3161, 374, '瑞丽市', 3),
(3162, 374, '梁河县', 3),
(3163, 374, '盈江县', 3),
(3164, 374, '陇川县', 3),
(3165, 375, '香格里拉县', 3),
(3166, 375, '德钦县', 3),
(3167, 375, '维西', 3),
(3168, 376, '泸西县', 3),
(3169, 376, '蒙自县', 3),
(3170, 376, '个旧市', 3),
(3171, 376, '开远市', 3),
(3172, 376, '绿春县', 3),
(3173, 376, '建水县', 3),
(3174, 376, '石屏县', 3),
(3175, 376, '弥勒县', 3),
(3176, 376, '元阳县', 3),
(3177, 376, '红河县', 3),
(3178, 376, '金平', 3),
(3179, 376, '河口', 3),
(3180, 376, '屏边', 3),
(3181, 377, '临翔区', 3),
(3182, 377, '凤庆县', 3),
(3183, 377, '云县', 3),
(3184, 377, '永德县', 3),
(3185, 377, '镇康县', 3),
(3186, 377, '双江', 3),
(3187, 377, '耿马', 3),
(3188, 377, '沧源', 3),
(3189, 378, '麒麟区', 3),
(3190, 378, '宣威市', 3),
(3191, 378, '马龙县', 3),
(3192, 378, '陆良县', 3),
(3193, 378, '师宗县', 3),
(3194, 378, '罗平县', 3),
(3195, 378, '富源县', 3),
(3196, 378, '会泽县', 3),
(3197, 378, '沾益县', 3),
(3198, 379, '文山县', 3),
(3199, 379, '砚山县', 3),
(3200, 379, '西畴县', 3),
(3201, 379, '麻栗坡县', 3),
(3202, 379, '马关县', 3),
(3203, 379, '丘北县', 3),
(3204, 379, '广南县', 3),
(3205, 379, '富宁县', 3),
(3206, 380, '景洪市', 3),
(3207, 380, '勐海县', 3),
(3208, 380, '勐腊县', 3),
(3209, 381, '红塔区', 3),
(3210, 381, '江川县', 3),
(3211, 381, '澄江县', 3),
(3212, 381, '通海县', 3),
(3213, 381, '华宁县', 3),
(3214, 381, '易门县', 3),
(3215, 381, '峨山', 3),
(3216, 381, '新平', 3),
(3217, 381, '元江', 3),
(3218, 382, '昭阳区', 3),
(3219, 382, '鲁甸县', 3),
(3220, 382, '巧家县', 3),
(3221, 382, '盐津县', 3),
(3222, 382, '大关县', 3),
(3223, 382, '永善县', 3),
(3224, 382, '绥江县', 3),
(3225, 382, '镇雄县', 3),
(3226, 382, '彝良县', 3),
(3227, 382, '威信县', 3),
(3228, 382, '水富县', 3),
(3229, 383, '西湖区', 3),
(3230, 383, '上城区', 3),
(3231, 383, '下城区', 3),
(3232, 383, '拱墅区', 3),
(3233, 383, '滨江区', 3),
(3234, 383, '江干区', 3),
(3235, 383, '萧山区', 3),
(3236, 383, '余杭区', 3),
(3237, 383, '市郊', 3),
(3238, 383, '建德市', 3),
(3239, 383, '富阳市', 3),
(3240, 383, '临安市', 3),
(3241, 383, '桐庐县', 3),
(3242, 383, '淳安县', 3),
(3243, 384, '吴兴区', 3),
(3244, 384, '南浔区', 3),
(3245, 384, '德清县', 3),
(3246, 384, '长兴县', 3),
(3247, 384, '安吉县', 3),
(3248, 385, '南湖区', 3),
(3249, 385, '秀洲区', 3),
(3250, 385, '海宁市', 3),
(3251, 385, '嘉善县', 3),
(3252, 385, '平湖市', 3),
(3253, 385, '桐乡市', 3),
(3254, 385, '海盐县', 3),
(3255, 386, '婺城区', 3),
(3256, 386, '金东区', 3),
(3257, 386, '兰溪市', 3),
(3258, 386, '市区', 3),
(3259, 386, '佛堂镇', 3),
(3260, 386, '上溪镇', 3),
(3261, 386, '义亭镇', 3),
(3262, 386, '大陈镇', 3),
(3263, 386, '苏溪镇', 3),
(3264, 386, '赤岸镇', 3),
(3265, 386, '东阳市', 3),
(3266, 386, '永康市', 3),
(3267, 386, '武义县', 3),
(3268, 386, '浦江县', 3),
(3269, 386, '磐安县', 3),
(3270, 387, '莲都区', 3),
(3271, 387, '龙泉市', 3),
(3272, 387, '青田县', 3),
(3273, 387, '缙云县', 3),
(3274, 387, '遂昌县', 3),
(3275, 387, '松阳县', 3),
(3276, 387, '云和县', 3),
(3277, 387, '庆元县', 3),
(3278, 387, '景宁', 3),
(3279, 388, '海曙区', 3),
(3280, 388, '江东区', 3),
(3281, 388, '江北区', 3),
(3282, 388, '镇海区', 3),
(3283, 388, '北仑区', 3),
(3284, 388, '鄞州区', 3),
(3285, 388, '余姚市', 3),
(3286, 388, '慈溪市', 3),
(3287, 388, '奉化市', 3),
(3288, 388, '象山县', 3),
(3289, 388, '宁海县', 3),
(3290, 389, '越城区', 3),
(3291, 389, '上虞市', 3),
(3292, 389, '嵊州市', 3),
(3293, 389, '绍兴县', 3),
(3294, 389, '新昌县', 3),
(3295, 389, '诸暨市', 3),
(3296, 390, '椒江区', 3),
(3297, 390, '黄岩区', 3),
(3298, 390, '路桥区', 3),
(3299, 390, '温岭市', 3),
(3300, 390, '临海市', 3),
(3301, 390, '玉环县', 3),
(3302, 390, '三门县', 3),
(3303, 390, '天台县', 3),
(3304, 390, '仙居县', 3),
(3305, 391, '鹿城区', 3),
(3306, 391, '龙湾区', 3),
(3307, 391, '瓯海区', 3),
(3308, 391, '瑞安市', 3),
(3309, 391, '乐清市', 3),
(3310, 391, '洞头县', 3),
(3311, 391, '永嘉县', 3),
(3312, 391, '平阳县', 3),
(3313, 391, '苍南县', 3),
(3314, 391, '文成县', 3),
(3315, 391, '泰顺县', 3),
(3316, 392, '定海区', 3),
(3317, 392, '普陀区', 3),
(3318, 392, '岱山县', 3),
(3319, 392, '嵊泗县', 3),
(3320, 393, '衢州市', 3),
(3321, 393, '江山市', 3),
(3322, 393, '常山县', 3),
(3323, 393, '开化县', 3),
(3324, 393, '龙游县', 3),
(3325, 394, '合川区', 3),
(3326, 394, '江津区', 3),
(3327, 394, '南川区', 3),
(3328, 394, '永川区', 3),
(3329, 394, '南岸区', 3),
(3330, 394, '渝北区', 3),
(3331, 394, '万盛区', 3),
(3332, 394, '大渡口区', 3),
(3333, 394, '万州区', 3),
(3334, 394, '北碚区', 3),
(3335, 394, '沙坪坝区', 3),
(3336, 394, '巴南区', 3),
(3337, 394, '涪陵区', 3),
(3338, 394, '江北区', 3),
(3339, 394, '九龙坡区', 3),
(3340, 394, '渝中区', 3),
(3341, 394, '黔江开发区', 3),
(3342, 394, '长寿区', 3),
(3343, 394, '双桥区', 3),
(3344, 394, '綦江县', 3),
(3345, 394, '潼南县', 3),
(3346, 394, '铜梁县', 3),
(3347, 394, '大足县', 3),
(3348, 394, '荣昌县', 3),
(3349, 394, '璧山县', 3),
(3350, 394, '垫江县', 3),
(3351, 394, '武隆县', 3),
(3352, 394, '丰都县', 3),
(3353, 394, '城口县', 3),
(3354, 394, '梁平县', 3),
(3355, 394, '开县', 3),
(3356, 394, '巫溪县', 3),
(3357, 394, '巫山县', 3),
(3358, 394, '奉节县', 3),
(3359, 394, '云阳县', 3),
(3360, 394, '忠县', 3),
(3361, 394, '石柱', 3),
(3362, 394, '彭水', 3),
(3363, 394, '酉阳', 3),
(3364, 394, '秀山', 3),
(3365, 395, '沙田区', 3),
(3366, 395, '东区', 3),
(3367, 395, '观塘区', 3),
(3368, 395, '黄大仙区', 3),
(3369, 395, '九龙城区', 3),
(3370, 395, '屯门区', 3),
(3371, 395, '葵青区', 3),
(3372, 395, '元朗区', 3),
(3373, 395, '深水埗区', 3),
(3374, 395, '西贡区', 3),
(3375, 395, '大埔区', 3),
(3376, 395, '湾仔区', 3),
(3377, 395, '油尖旺区', 3),
(3378, 395, '北区', 3),
(3379, 395, '南区', 3),
(3380, 395, '荃湾区', 3),
(3381, 395, '中西区', 3),
(3382, 395, '离岛区', 3),
(3383, 396, '澳门', 3),
(3384, 397, '台北', 3),
(3385, 397, '高雄', 3),
(3386, 397, '基隆', 3),
(3387, 397, '台中', 3),
(3388, 397, '台南', 3),
(3389, 397, '新竹', 3),
(3390, 397, '嘉义', 3),
(3391, 397, '宜兰县', 3),
(3392, 397, '桃园县', 3),
(3393, 397, '苗栗县', 3),
(3394, 397, '彰化县', 3),
(3395, 397, '南投县', 3),
(3396, 397, '云林县', 3),
(3397, 397, '屏东县', 3),
(3398, 397, '台东县', 3),
(3399, 397, '花莲县', 3),
(3400, 397, '澎湖县', 3),
(3401, 3, '合肥', 2),
(3402, 3401, '庐阳区', 3),
(3403, 3401, '瑶海区', 3),
(3404, 3401, '蜀山区', 3),
(3405, 3401, '包河区', 3),
(3406, 3401, '长丰县', 3),
(3407, 3401, '肥东县', 3),
(3408, 3401, '肥西县', 3);

-- --------------------------------------------------------

--
-- 表的结构 `fay_roles`
--

CREATE TABLE IF NOT EXISTS `fay_roles` (
  `id` smallint(5) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_roles`
--

INSERT INTO `fay_roles` (`id`, `title`, `description`, `deleted`, `is_show`) VALUES
(1, '普通用户', '', 0, 1),
(100, '系统', '', 0, 0),
(101, '超级管理员', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `fay_role_actions`
--

CREATE TABLE IF NOT EXISTS `fay_role_actions` (
  `id` mediumint(8) unsigned NOT NULL,
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `action_id` smallint(5) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_spider_logs`
--

CREATE TABLE IF NOT EXISTS `fay_spider_logs` (
  `id` int(10) unsigned NOT NULL,
  `spider` varchar(50) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `ip_int` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_tags`
--

CREATE TABLE IF NOT EXISTS `fay_tags` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_templates`
--

CREATE TABLE IF NOT EXISTS `fay_templates` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(500) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_users`
--

CREATE TABLE IF NOT EXISTS `fay_users` (
  `id` int(10) unsigned NOT NULL,
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
  `keywords` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_users`
--

INSERT INTO `fay_users` (`id`, `username`, `email`, `cellphone`, `password`, `salt`, `realname`, `nickname`, `avatar`, `reg_time`, `reg_ip`, `login_times`, `last_login_time`, `last_login_ip`, `last_time_online`, `status`, `block`, `role`, `parent`, `active_key`, `active_expire`, `sms_key`, `sms_expire`, `deleted`, `trackid`, `refer`, `se`, `keywords`) VALUES
(1, '系统', '', '', '', '', '系统', '系统', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '', 0, 0, '', '', '', ''),
(2, '用户留言收件人', '', '', '', '', '用户留言收件人', '用户留言收件人', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '', 0, 0, '', '', '', ''),
(3, '系统消息', '', '', '', '', '系统消息', '系统消息', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '', 0, 0, '', '', '', ''),
(10000, '123', '', '', '6a29cac3d10e3fc1b40dc2eb1554f17b', 'a1xDp', '', '', 0, 1426437489, 0, 11, 1427027966, 2130706433, 1427034566, 3, 0, 101, 0, '', 0, '', 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `fay_user_notifications`
--

CREATE TABLE IF NOT EXISTS `fay_user_notifications` (
  `id` int(10) unsigned NOT NULL,
  `to` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `notification_id` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `ignored` tinyint(1) NOT NULL DEFAULT '0',
  `option` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_user_settings`
--

CREATE TABLE IF NOT EXISTS `fay_user_settings` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `setting_key` varchar(255) NOT NULL DEFAULT '',
  `setting_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_vouchers`
--

CREATE TABLE IF NOT EXISTS `fay_vouchers` (
  `id` mediumint(8) unsigned NOT NULL,
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sn` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `counts` smallint(5) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `fay_widgets`
--

CREATE TABLE IF NOT EXISTS `fay_widgets` (
  `id` mediumint(8) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `options` text NOT NULL,
  `widget_name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fay_widgets`
--

INSERT INTO `fay_widgets` (`id`, `alias`, `options`, `widget_name`, `description`, `enabled`) VALUES
(8, 'index-lists', '{"hierarchical":0,"top":10009,"title":"\\u4ea7\\u54c1\\u5206\\u7c7b","uri":"cat\\/{$id}","template":"frontend\\/widget\\/index-lists"}', 'fay/categories', 'index-lists', 1),
(9, 'hot-products', '{"title":"\\u70ed\\u95e8\\u4ea7\\u54c1","top":10009,"subclassification":1,"number":8,"thumbnail":0,"order":"hand","last_view_time":0,"date_format":"","uri":"post\\/{$id}","other_uri":"","template":"frontend\\/widget\\/hot-products"}', 'fay/category_post', 'hot-products', 1),
(10, 'product-list', '{"hierarchical":0,"top":10009,"title":"\\u4ea7\\u54c1\\u5206\\u7c7b","uri":"cat\\/{$id}","template":"frontend\\/widget\\/product-list"}', 'fay/categories', '内页产品分类', 1),
(7, 'new-news', '{"title":"\\u6700\\u65b0\\u8d44\\u8baf","top":10005,"subclassification":1,"number":5,"thumbnail":0,"order":"hand","last_view_time":0,"date_format":"","uri":"post\\/{$id}","other_uri":"","template":"frontend\\/widget\\/new-news"}', 'fay/category_post', 'new-news', 1),
(11, 'hot-products-inside', '{"title":"\\u70ed\\u95e8\\u4ea7\\u54c1","top":10009,"subclassification":1,"number":8,"thumbnail":0,"order":"hand","last_view_time":0,"date_format":"","uri":"post\\/{$id}","other_uri":"","template":"frontend\\/widget\\/hot-products-inside"}', 'fay/category_post', '内页热门产品', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fay_actionlogs`
--
ALTER TABLE `fay_actionlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_actions`
--
ALTER TABLE `fay_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_analyst_caches`
--
ALTER TABLE `fay_analyst_caches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_analyst_macs`
--
ALTER TABLE `fay_analyst_macs`
  ADD PRIMARY KEY (`id`), ADD KEY `hash` (`hash`), ADD KEY `date` (`create_date`);

--
-- Indexes for table `fay_analyst_sites`
--
ALTER TABLE `fay_analyst_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_analyst_visits`
--
ALTER TABLE `fay_analyst_visits`
  ADD PRIMARY KEY (`id`), ADD KEY `pv` (`mac`,`short_url`,`create_time`), ADD KEY `date` (`create_date`,`hour`);

--
-- Indexes for table `fay_categories`
--
ALTER TABLE `fay_categories`
  ADD PRIMARY KEY (`id`), ADD KEY `alias` (`alias`), ADD KEY `left_right_value` (`left_value`,`right_value`);

--
-- Indexes for table `fay_cat_props`
--
ALTER TABLE `fay_cat_props`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_cat_prop_values`
--
ALTER TABLE `fay_cat_prop_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_cities`
--
ALTER TABLE `fay_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_contacts`
--
ALTER TABLE `fay_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_exam_answers`
--
ALTER TABLE `fay_exam_answers`
  ADD PRIMARY KEY (`id`), ADD KEY `question` (`question_id`);

--
-- Indexes for table `fay_exam_exams`
--
ALTER TABLE `fay_exam_exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_exam_exam_questions`
--
ALTER TABLE `fay_exam_exam_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_exam_exam_question_answers_int`
--
ALTER TABLE `fay_exam_exam_question_answers_int`
  ADD PRIMARY KEY (`exam_question_id`,`user_answer_id`);

--
-- Indexes for table `fay_exam_exam_question_answer_text`
--
ALTER TABLE `fay_exam_exam_question_answer_text`
  ADD PRIMARY KEY (`exam_question_id`);

--
-- Indexes for table `fay_exam_papers`
--
ALTER TABLE `fay_exam_papers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_exam_paper_questions`
--
ALTER TABLE `fay_exam_paper_questions`
  ADD PRIMARY KEY (`paper_id`,`question_id`), ADD KEY `question` (`question_id`);

--
-- Indexes for table `fay_exam_questions`
--
ALTER TABLE `fay_exam_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_favourites`
--
ALTER TABLE `fay_favourites`
  ADD PRIMARY KEY (`user_id`,`post_id`);

--
-- Indexes for table `fay_files`
--
ALTER TABLE `fay_files`
  ADD PRIMARY KEY (`id`), ADD KEY `raw_name` (`raw_name`);

--
-- Indexes for table `fay_followers`
--
ALTER TABLE `fay_followers`
  ADD PRIMARY KEY (`user_id`,`follower`);

--
-- Indexes for table `fay_goods`
--
ALTER TABLE `fay_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_goods_files`
--
ALTER TABLE `fay_goods_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_goods_prop_values`
--
ALTER TABLE `fay_goods_prop_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_goods_skus`
--
ALTER TABLE `fay_goods_skus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_item_props`
--
ALTER TABLE `fay_item_props`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_item_prop_values`
--
ALTER TABLE `fay_item_prop_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_keywords`
--
ALTER TABLE `fay_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_likes`
--
ALTER TABLE `fay_likes`
  ADD PRIMARY KEY (`post_id`,`user_id`);

--
-- Indexes for table `fay_links`
--
ALTER TABLE `fay_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_logs`
--
ALTER TABLE `fay_logs`
  ADD PRIMARY KEY (`id`), ADD KEY `type` (`type`);

--
-- Indexes for table `fay_menus`
--
ALTER TABLE `fay_menus`
  ADD PRIMARY KEY (`id`), ADD KEY `alias` (`alias`);

--
-- Indexes for table `fay_messages`
--
ALTER TABLE `fay_messages`
  ADD PRIMARY KEY (`id`), ADD KEY `root` (`root`);

--
-- Indexes for table `fay_notifications`
--
ALTER TABLE `fay_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_options`
--
ALTER TABLE `fay_options`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `option_name` (`option_name`);

--
-- Indexes for table `fay_pages`
--
ALTER TABLE `fay_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_page_categories`
--
ALTER TABLE `fay_page_categories`
  ADD PRIMARY KEY (`page_id`,`cat_id`);

--
-- Indexes for table `fay_posts`
--
ALTER TABLE `fay_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_posts_tags`
--
ALTER TABLE `fay_posts_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`);

--
-- Indexes for table `fay_post_categories`
--
ALTER TABLE `fay_post_categories`
  ADD PRIMARY KEY (`post_id`,`cat_id`);

--
-- Indexes for table `fay_post_files`
--
ALTER TABLE `fay_post_files`
  ADD PRIMARY KEY (`post_id`,`file_id`);

--
-- Indexes for table `fay_post_prop_int`
--
ALTER TABLE `fay_post_prop_int`
  ADD PRIMARY KEY (`post_id`,`prop_id`,`content`);

--
-- Indexes for table `fay_post_prop_text`
--
ALTER TABLE `fay_post_prop_text`
  ADD PRIMARY KEY (`post_id`,`prop_id`);

--
-- Indexes for table `fay_post_prop_varchar`
--
ALTER TABLE `fay_post_prop_varchar`
  ADD PRIMARY KEY (`post_id`,`prop_id`);

--
-- Indexes for table `fay_profile_int`
--
ALTER TABLE `fay_profile_int`
  ADD PRIMARY KEY (`user_id`,`prop_id`,`content`);

--
-- Indexes for table `fay_profile_text`
--
ALTER TABLE `fay_profile_text`
  ADD PRIMARY KEY (`user_id`,`prop_id`);

--
-- Indexes for table `fay_profile_varchar`
--
ALTER TABLE `fay_profile_varchar`
  ADD PRIMARY KEY (`user_id`,`prop_id`);

--
-- Indexes for table `fay_props`
--
ALTER TABLE `fay_props`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_prop_values`
--
ALTER TABLE `fay_prop_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_regions`
--
ALTER TABLE `fay_regions`
  ADD PRIMARY KEY (`id`), ADD KEY `parent_id` (`parent_id`), ADD KEY `region_type` (`type`);

--
-- Indexes for table `fay_roles`
--
ALTER TABLE `fay_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_role_actions`
--
ALTER TABLE `fay_role_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_spider_logs`
--
ALTER TABLE `fay_spider_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_tags`
--
ALTER TABLE `fay_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_templates`
--
ALTER TABLE `fay_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_users`
--
ALTER TABLE `fay_users`
  ADD PRIMARY KEY (`id`), ADD KEY `username` (`username`);

--
-- Indexes for table `fay_user_notifications`
--
ALTER TABLE `fay_user_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_user_settings`
--
ALTER TABLE `fay_user_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_vouchers`
--
ALTER TABLE `fay_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fay_widgets`
--
ALTER TABLE `fay_widgets`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `alias` (`alias`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fay_actionlogs`
--
ALTER TABLE `fay_actionlogs`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `fay_actions`
--
ALTER TABLE `fay_actions`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `fay_analyst_caches`
--
ALTER TABLE `fay_analyst_caches`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=168;
--
-- AUTO_INCREMENT for table `fay_analyst_macs`
--
ALTER TABLE `fay_analyst_macs`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fay_analyst_sites`
--
ALTER TABLE `fay_analyst_sites`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fay_analyst_visits`
--
ALTER TABLE `fay_analyst_visits`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fay_categories`
--
ALTER TABLE `fay_categories`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10012;
--
-- AUTO_INCREMENT for table `fay_cat_props`
--
ALTER TABLE `fay_cat_props`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_cat_prop_values`
--
ALTER TABLE `fay_cat_prop_values`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_cities`
--
ALTER TABLE `fay_cities`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=503;
--
-- AUTO_INCREMENT for table `fay_contacts`
--
ALTER TABLE `fay_contacts`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_exam_answers`
--
ALTER TABLE `fay_exam_answers`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_exam_exams`
--
ALTER TABLE `fay_exam_exams`
  MODIFY `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_exam_exam_questions`
--
ALTER TABLE `fay_exam_exam_questions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_exam_papers`
--
ALTER TABLE `fay_exam_papers`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_exam_questions`
--
ALTER TABLE `fay_exam_questions`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_files`
--
ALTER TABLE `fay_files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1013;
--
-- AUTO_INCREMENT for table `fay_goods`
--
ALTER TABLE `fay_goods`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_goods_files`
--
ALTER TABLE `fay_goods_files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_goods_prop_values`
--
ALTER TABLE `fay_goods_prop_values`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_goods_skus`
--
ALTER TABLE `fay_goods_skus`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_item_props`
--
ALTER TABLE `fay_item_props`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_item_prop_values`
--
ALTER TABLE `fay_item_prop_values`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_keywords`
--
ALTER TABLE `fay_keywords`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_links`
--
ALTER TABLE `fay_links`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fay_logs`
--
ALTER TABLE `fay_logs`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `fay_menus`
--
ALTER TABLE `fay_menus`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1000;
--
-- AUTO_INCREMENT for table `fay_messages`
--
ALTER TABLE `fay_messages`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_notifications`
--
ALTER TABLE `fay_notifications`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_options`
--
ALTER TABLE `fay_options`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `fay_pages`
--
ALTER TABLE `fay_pages`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1007;
--
-- AUTO_INCREMENT for table `fay_posts`
--
ALTER TABLE `fay_posts`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1025;
--
-- AUTO_INCREMENT for table `fay_props`
--
ALTER TABLE `fay_props`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_prop_values`
--
ALTER TABLE `fay_prop_values`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_regions`
--
ALTER TABLE `fay_regions`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3409;
--
-- AUTO_INCREMENT for table `fay_roles`
--
ALTER TABLE `fay_roles`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `fay_role_actions`
--
ALTER TABLE `fay_role_actions`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_spider_logs`
--
ALTER TABLE `fay_spider_logs`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_tags`
--
ALTER TABLE `fay_tags`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_templates`
--
ALTER TABLE `fay_templates`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_users`
--
ALTER TABLE `fay_users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10001;
--
-- AUTO_INCREMENT for table `fay_user_notifications`
--
ALTER TABLE `fay_user_notifications`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_user_settings`
--
ALTER TABLE `fay_user_settings`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_vouchers`
--
ALTER TABLE `fay_vouchers`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fay_widgets`
--
ALTER TABLE `fay_widgets`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
