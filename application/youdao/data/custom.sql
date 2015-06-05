-- 导航菜单
INSERT INTO `{{$prefix}}menus` VALUES ('1', '0', '100', '1', '16', '_youdao_top', '有道顶部导航', '', '', '');
INSERT INTO `{{$prefix}}menus` VALUES ('2', '1', '100', '2', '3', 'home', '首页', 'Home', '{$base_url}', '');
INSERT INTO `{{$prefix}}menus` VALUES ('3', '1', '100', '4', '5', 'about', '关于有道', 'About Us', '{$base_url}about.html', '');
INSERT INTO `{{$prefix}}menus` VALUES ('4', '1', '100', '6', '7', 'news', '最新资讯', 'News', '{$base_url}post/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('5', '1', '100', '8', '9', 'team', '团队介绍', 'Team', '{$base_url}team/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('6', '1', '100', '10', '11', 'case', '成功案例', 'Case', '{$base_url}case/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('7', '1', '100', '12', '13', 'service', '服务介绍', 'Service', '{$base_url}service/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('8', '1', '100', '14', '15', 'contact', '联系我们', 'Contact', '{$base_url}contact.html', '');

-- 自定义分类
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1000', '1', '100', '_youdao_post', '资讯', '不要将文章直接发布在此目录下');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1001', '1000', '100', 'notice', '公告', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1002', '1000', '100', 'news', '新闻', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1003', '1000', '100', 'notification', '通知', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1004', '1', '100', '_youdao_team', '团队', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('1005', '1', '100', '_youdao_service', '服务介绍', '');

-- 新增一篇服务（否则会报错）
INSERT INTO `{{$prefix}}posts` VALUES ('1', '1005', '服务一', '', '', '1407207428', '0', '1407207428', '1', '1017', '0', '1', '0', '0', '', '0', '', '', '', '100');

-- 新增静态页面
INSERT INTO `{{$prefix}}pages` VALUES ('1', '关于我们', 'about', '', '1017', '1407207066', '1407207066', '1', '0', '', '0', '0', '4', '100', '', '', '', '');
INSERT INTO `{{$prefix}}pages` VALUES ('2', '联系我们', 'contact', '', '1017', '1407207126', '1407207126', '1', '0', '', '0', '0', '1', '100', '', '', '', '');

-- 初始化几个参数
INSERT INTO `{{$prefix}}options` VALUES ('1', 'copyright', 'Copyright© 2013 Siwi.Me 版权所有', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('2', 'youdao_phone', '13616546418', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('3', 'youdao_fax', '0578-3142411', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('4', 'youdao_email', 'admin@fayfox.com', '', '{{$time}}', '0', '0');
INSERT INTO `{{$prefix}}options` VALUES ('5', 'youdao_address', '杭州市滨江区海威国际', '', '{{$time}}', '0', '0');