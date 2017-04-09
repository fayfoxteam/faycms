-- 页面
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (1, '关于我们', 'about');
INSERT INTO `{{$prefix}}pages` (id, title, alias, content) VALUES (2, '联系我们', 'contact', '<p>联系电话：0553-12345678</p>\r\n\r\n<p>QQ: 383920940</p>\r\n\r\n<p>手机：13616546418</p>\r\n');

-- 师资力量自定义属性
INSERT INTO `faycms_props` (refer, type, title, element, required, alias, create_time) VALUES ('1000', '1', '职位', '1', '1', 'teacher_job', '{{$time}}');

-- 基础分类
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1000', '师资力量', 'teacher', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1001', '学生作品', 'works', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1002', '招生简章', 'guide', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1003', '画室生活', 'life', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1004', '教学成果', 'achievement', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1005', '学生服务', 'service', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1006', '课程设置', 'course', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1007', '高考动态', 'examination', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1008', '画室资讯', 'news', '1', '0', '1');

-- 初始化小工具
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('1', 'slider', '{\"height\":\"450\",\"transPeriod\":800,\"time\":5000,\"fx\":\"random\",\"files\":[{\"file_id\":1000,\"link\":\"\",\"title\":\"2013061321521811.jpg\"}]}', 'fay/jq_camera', '顶部轮播图');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('2', 'environment', '{\"title\":\"\\u52fe\\u52d2\\u753b\\u5ba4\",\"template\":\"frontend\\/index\\/widget\\/environment\",\"data\":[{\"key\":\"\\u6211\\u4eec\\u7684\\u6c1b\\u56f4\",\"value\":\"\\u6c1b\\u56f4\\u5f88\\u597d\"},{\"key\":\"\\u6211\\u4eec\\u7684\\u8d23\\u4efb\\u611f\",\"value\":\"\\u968f\\u4fbf\\u5439\\u4e24\\u53e5\"},{\"key\":\"\\u6211\\u4eec\\u7684\\u627f\\u8bfa\",\"value\":\"\\u6211\\u4eec\\u627f\\u8bfa\\uff0cXXX\"}]}', 'fay/options', '环境');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('4', 'news', '{\"title\":\"\",\"top\":1008,\"subclassification\":1,\"number\":6,\"show_empty\":1,\"thumbnail\":0,\"order\":\"hand\",\"last_view_time\":0,\"date_format\":\"Y-m-d\",\"uri\":\"news-{$id}\",\"other_uri\":\"news-{$id}\",\"template\":\"frontend\\/widget\\/news\"}', 'fay/category_posts', '资讯');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('5', 'works', '{\"title\":\"\",\"top\":1001,\"subclassification\":1,\"number\":6,\"show_empty\":0,\"thumbnail\":0,\"order\":\"hand\",\"last_view_time\":0,\"date_format\":\"pretty\",\"uri\":\"works-{$id}\",\"other_uri\":\"works-{$id}\",\"template\":\"frontend\\/widget\\/works\"}', 'fay/category_posts', '作品展');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('6', 'abstract', '{\"title\":\"\",\"top\":1000,\"subclassification\":1,\"number\":4,\"show_empty\":0,\"thumbnail\":1,\"order\":\"hand\",\"last_view_time\":0,\"date_format\":\"pretty\",\"uri\":\"tercher\",\"other_uri\":\"tercher\",\"template\":\"frontend\\/widget\\/abstract\"}', 'fay/category_posts', '我们的团队');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('7', 'about', '{\"default_page_id\":1,\"id_key\":\"\",\"alias_key\":\"page_alias\",\"inc_views\":1,\"template\":\"frontend\\/widget\\/about\"}', 'fay/page_item', '关于我们');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('3', 'index-advantage', '{\"title\":\"\\u52fe\\u52d2\\u4f18\\u52bf\",\"template\":\"frontend\\/index\\/widget\\/advantage\",\"data\":[\"\\u4f18\\u52bf\\u4e8c\",\"\\u4f18\\u52bf\\u4e00\",\"\\u4f18\\u52bf\\u4e09\",\"\\u4f18\\u52bf\\u56db\",\"\\u4f18\\u52bf\\u4e94\"]}', 'fay/listing', '勾勒优势');
INSERT INTO `{{$prefix}}widgets` (`id`, `alias`, `options`, `widget_name`, `description`) VALUES ('10', 'contact-map', '{\"ak\":\"10b033765ad00c668fcdd20902dab530\",\"point\":\"120.29229,30.312906\",\"width\":\"\",\"height\":359,\"zoom_num\":14,\"enable_scroll_wheel_zoom\":1,\"navigation_control\":1,\"scale_control\":1,\"marker_point\":\"120.29229,30.312906\",\"marker_info\":\"\"}', 'fay/baidu_map', '联系我们页面地图');