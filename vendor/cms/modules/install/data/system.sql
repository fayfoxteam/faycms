-- 系统用户和留言用户
INSERT INTO `{{$prefix}}users` (id, username, nickname) VALUES ('1', '系统', '系统');
INSERT INTO `{{$prefix}}users` (id, username, nickname) VALUES ('2', '用户留言收件人', '用户留言收件人');
INSERT INTO `{{$prefix}}users` (id, username, nickname) VALUES ('3', '系统消息', '系统消息');

-- users表id从10000开始自递增
ALTER TABLE {{$prefix}}users AUTO_INCREMENT = 10000;

-- categories表id从10000开始自递增
ALTER TABLE {{$prefix}}categories AUTO_INCREMENT = 10000;

-- menus表id从10000开始自递增
ALTER TABLE {{$prefix}}menus AUTO_INCREMENT = 10000;

-- roles表新增超级管理员和系统角色
INSERT INTO `{{$prefix}}roles` VALUES ('1', '超级管理员', '', '0', '1');

-- files表id从10000开始递增
ALTER TABLE {{$prefix}}files AUTO_INCREMENT = 10000;

-- posts表id从10000开始递增
ALTER TABLE {{$prefix}}posts AUTO_INCREMENT = 10000;

-- pages表id从1000开始递增
ALTER TABLE {{$prefix}}pages AUTO_INCREMENT = 1000;

-- actions表id从10000开始递增
ALTER TABLE {{$prefix}}actions AUTO_INCREMENT = 10000;

-- post_comments表id从10000开始递增
ALTER TABLE {{$prefix}}post_comments AUTO_INCREMENT = 10000;

-- options表id从100开始递增
INSERT INTO `{{$prefix}}options` (`id`, `option_name`, `option_value`, `description`, `create_time`, `last_modified_time`, `is_system`) VALUES ('1', 'system:post_review', '0', '是否启用文章审核功能', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`id`, `option_name`, `option_value`, `description`, `create_time`, `last_modified_time`, `is_system`) VALUES ('2', 'system:role_cats', '0', '是否启用角色分类权限控制', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`id`, `option_name`, `option_value`, `description`, `create_time`, `last_modified_time`, `is_system`) VALUES ('3', 'system:image_quality', '75', '输出图片质量', '{{$time}}', '{{$time}}', '1');
ALTER TABLE {{$prefix}}options AUTO_INCREMENT = 100;


-- 访问统计本地站点
INSERT INTO `{{$prefix}}analyst_sites` VALUES ('1', 'localhost', '本站', '0');