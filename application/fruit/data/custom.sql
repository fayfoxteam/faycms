-- 页面
INSERT INTO `{{$prefix}}pages` (id, title, alias, thumbnail) VALUES (1, '关于我们', 'about', 4);
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (2, '阳光牧场', 'case-1');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (3, '优质果园', 'case-2');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (4, '运输方式', 'case-3');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (5, '联系我们', 'contact');

-- 基础参数
INSERT INTO `{{$prefix}}options` VALUES ('1', 'copyright', 'Copyright © 2012-2013 fayfox.com', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('2', 'beian', '浙ICP备12036784号-1', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('3', 'sitename', 'Fayfox', '', '{{$time}}', '0', '1');

-- 基础分类
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('1000', '产品', 'product', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('1001', '新闻', 'news', '1', '1');

-- 小工具
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('1', 'index-slides-camera', '{\"height\":\"35%\",\"transPeriod\":800,\"time\":5000,\"fx\":\"simpleFade\",\"files\":[{\"file_id\":1,\"link\":\"\",\"title\":\"slide-1.jpg\"},{\"file_id\":2,\"link\":\"\",\"title\":\"slide-2.jpg\"},{\"file_id\":3,\"link\":\"\",\"title\":\"slide-3.jpg\"}]}', 'fay/jq_camera', '首页顶部轮播图', '1');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`, `enabled`) VALUES ('2', 'contacts', '{\"data\":[{\"key\":\"\\u516c\\u53f8\\u540d\\u79f0\",\"value\":\"Faycms\"},{\"key\":\"\\u90ae\\u7f16\",\"value\":\"310000\"},{\"key\":\"\\u90ae\\u7bb1\",\"value\":\"admin@fayfox.com\"},{\"key\":\"\\u5730\\u5740\",\"value\":\"\\u6d59\\u6c5f\\u676d\\u5dde\\u6ee8\\u6c5f\\u533a\\u6d77\\u5a01\\u56fd\\u9645\"}],\"template\":\"<p><label>{$key}\\uff1a<\\/label>{$value}<\\/p>\"}', 'fay/options', '联系方式', '1');

-- 顶部导航
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('1000', '2', '_fruit_top', '顶部导航', '', '', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('1001', '1000', 'home', '首页', '', '{$base_url}', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('1002', '1000', 'product', '产品中心', '', '{$base_url}product/', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('1003', '1000', 'news', '新闻中心', '', '{$base_url}news/', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('1004', '1000', 'contact', '联系我们', '', '{$base_url}contact', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `sub_title`, `link`, `target`) VALUES ('1005', '1000', 'taobao', '淘宝店铺', '', 'http://shop68779173.taobao.com/', '_blank');

-- widget图片
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`, `downloads`, `type`, `qiniu`) VALUES ('1', 'slide-1', '.jpg', '167501', 'image/jpeg', './static/fruit/images/', 'slide-1.jpg', '1', '1920', '647', '{{$time}}', '10000', '0', '0', '0');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`, `downloads`, `type`, `qiniu`) VALUES ('2', 'slide-2', '.jpg', '91236', 'image/jpeg', './static/fruit/images/', 'slide-2.jpg', '1', '1920', '647', '{{$time}}', '10000', '0', '0', '0');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`, `downloads`, `type`, `qiniu`) VALUES ('3', 'slide-3', '.jpg', '113444', 'image/jpeg', './static/fruit/images/', 'slide-3.jpg', '1', '1920', '647', '{{$time}}', '10000', '0', '0', '0');
INSERT INTO `{{$prefix}}files` (`id`, `raw_name`, `file_ext`, `file_size`, `file_type`, `file_path`, `client_name`, `is_image`, `image_width`, `image_height`, `upload_time`, `user_id`, `downloads`, `type`, `qiniu`) VALUES ('4', 'page1-img1', '.jpg', '30040', 'image/jpeg', './static/fruit/images/', 'page1-img1.jpg', '1', '460', '388', '{{$time}}', '10000', '0', '0', '0');