-- 创建管理员角色
INSERT INTO `{{$prefix}}roles` VALUES ('102', '管理员', '', '0', '1');

-- 必须有的小工具实例
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('contact', '{\"data\":[{\"key\":\"\\u7535\\u8bdd\",\"value\":\"13616546418\"},{\"key\":\"\\u90ae\\u7bb1\",\"value\":\"admin@fayfox.com\"},{\"key\":\"QQ\",\"value\":\"369281831\"},{\"key\":\"\\u5730\\u5740\",\"value\":\"\\u676d\\u5dde\\u5e02\\u6ee8\\u6c5f\\u533a\\u6d77\\u5a01\\u56fd\\u9645\"}],\"template\":\"<p><label>{$key}\\uff1a<\\/label>{$value}<\\/p>\"}', 'fay/options', '联系我们', '1');
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('index-slides', '{\"height\":216,\"transPeriod\":800,\"time\":5000,\"fx\":\"random\",\"files\":[]}', 'fay/jq_camera', '首页轮播图', '1');
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('index-1-1', '{\"subclassification\":1,\"top\":1,\"title\":\"\",\"number\":7,\"uri\":\"post\\/{$id}\",\"template\":\"frontend\\/widget\\/category_posts\",\"date_format\":\"\",\"order\":\"hand\"}', 'fay/category_posts', '首页第一排', '1');
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('index-2-1', '{\"subclassification\":1,\"top\":1,\"title\":\"\",\"number\":6,\"uri\":\"post\\/{$id}\",\"template\":\"frontend\\/widget\\/category_posts\",\"date_format\":\"\",\"order\":\"hand\"}', 'fay/category_posts', '首页第二排第一个', '1');
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('index-2-2', '{\"subclassification\":1,\"top\":1,\"title\":\"\",\"number\":6,\"uri\":\"post\\/{$id}\",\"template\":\"frontend\\/widget\\/category_posts\",\"date_format\":\"[Y-m-d]\",\"order\":\"hand\"}', 'fay/category_posts', '首页第二排第二个', '1');
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('friendlinks', '{\"title\":\"\",\"number\":3,\"template\":\"frontend\\/widget\\/friendlinks\"}', 'fay/friendlinks', '友情链接', '1');
INSERT INTO `{{$prefix}}widgets` (`alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('index-bottom-gallery', '{\"subclassification\":1,\"top\":1,\"title\":\"\",\"number\":10,\"uri\":\"post\\/{$id}\",\"template\":\"frontend\\/widget\\/category_posts_gallery\",\"date_format\":\"\",\"thumbnail\":1,\"order\":\"hand\"}', 'fay/category_posts', '首页底部画廊', '1');

-- 必须的页面分类
INSERT INTO `{{$prefix}}categories` (`title`, `alias`, `parent`, `sort`, `is_nav`, `is_system`) VALUES ('课程概况', 'about', '2', '100', '1', '1');

-- 必须的页面
INSERT INTO `{{$prefix}}pages` (`title`, `alias`) VALUES ('课程简介', 'about');