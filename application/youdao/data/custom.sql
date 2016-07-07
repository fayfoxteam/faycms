-- 导航菜单
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10000', '2', '100', 'top', '顶部导航', '', '', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10002', '10000', '100', 'home', '首页', 'Home', '{$base_url}', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10003', '10000', '100', 'about', '关于我们', 'About Us', '{$base_url}about.html', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10004', '10000', '100', 'news', '最新资讯', 'News', '{$base_url}post/', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10005', '10000', '100', 'team', '团队介绍', 'Team', '{$base_url}team/', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10006', '10000', '100', 'case', '成功案例', 'Case', '{$base_url}case/', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10007', '10000', '100', 'service', '服务介绍', 'Service', '{$base_url}service/', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('10008', '10000', '100', 'contact', '联系我们', 'Contact', '{$base_url}contact.html', '');

-- 自定义分类
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1000', '1', '100', 'post', '资讯', '不要将文章直接发布在此目录下');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1001', '1000', '100', 'notice', '公告', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1002', '1000', '100', 'news', '新闻', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1003', '1000', '100', 'notification', '通知', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1004', '1', '100', 'team', '团队', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1005', '1', '100', 'service', '服务介绍', '');

-- 新增一篇服务（否则会报错）
INSERT INTO `{{$prefix}}posts` (id, cat_id, title, content, create_time, last_modified_time, user_id) VALUES ('1', '1005', '服务一', '', '{{$time}}', '{{$time}}', '10000');

-- 新增静态页面
INSERT INTO `{{$prefix}}pages` (id, title, alias, create_time, last_modified_time) VALUES ('1', '关于我们', 'about', '{{$time}}', '{{$time}}');
INSERT INTO `{{$prefix}}pages` (id, title, alias, create_time, last_modified_time) VALUES ('2', '联系我们', 'contact', '{{$time}}', '{{$time}}');

-- 初始化几个参数
INSERT INTO `{{$prefix}}options` VALUES ('100', 'site:copyright', 'Copyright© 2013 Siwi.Me 版权所有', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('101', 'site:phone', '13616546418', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('102', 'site:fax', '0578-3142411', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('103', 'site:email', 'admin@fayfox.com', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('104', 'site:address', '杭州市滨江区海威国际', '', '{{$time}}', '0', '0');