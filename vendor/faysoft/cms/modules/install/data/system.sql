-- 系统用户和留言用户
INSERT INTO `{{$prefix}}users` (id, username, nickname) VALUES ('1', '系统', '系统');
INSERT INTO `{{$prefix}}users` (id, username, nickname) VALUES ('2', '用户留言收件人', '用户留言收件人');
INSERT INTO `{{$prefix}}users` (id, username, nickname) VALUES ('3', '系统消息', '系统消息');

-- roles表新增超级管理员和系统角色
INSERT INTO `{{$prefix}}roles` VALUES ('1', '超级管理员', '', '0', '1');

-- options表id从1000开始递增
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('system:post_review', '0', '是否启用文章审核功能', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('system:post_role_cats', '0', '是否启用角色文章分类权限控制', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('system:image_quality', '75', '输出图片质量', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('system:post_comment_verify', '1', '是否仅显示通过审核的文章评论', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('system:user_nickname_required', '1', '用户昵称必填', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('system:user_nickname_unique', '1', '用户昵称唯一', '{{$time}}', '{{$time}}', '1');
ALTER TABLE {{$prefix}}options AUTO_INCREMENT = 1000;

-- 访问统计本地站点
INSERT INTO `{{$prefix}}analyst_sites` VALUES ('1', 'localhost', '本站', '0');