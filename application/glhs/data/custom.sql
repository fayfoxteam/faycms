-- 页面
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (1, '关于我们', 'about');

--　基础分类
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1000', '师资力量', 'teacher', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1001', '学生作品', 'works', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1002', '招生简章', 'guide', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1003', '画室生活', 'life', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1004', '教学成果', 'achievement', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1005', '学生服务', 'service', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1006', '课程设置', 'course', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1007', '高考动态', 'examination', '1', '1', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES ('1008', '画室资讯', 'news', '1', '0', '1');