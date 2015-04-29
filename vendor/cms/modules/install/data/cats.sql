-- 所有根分类
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system, description) VALUES ('1', '0', '_system_post', '未分类', '1', '文章分类根目录');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('2', '0', '_system_page', '页面分类', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('3', '0', '_system_action', '权限', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('4', '0', '_system_notification', '系统消息', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('5', '0', '_system_messages', '用户留言', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('6', '0', '_system_goods', '商品分类', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('7', '0', '_system_voucher', '优惠卷', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('8', '0', '_system_exam', '考试', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('9', '8', '_system_exam_question', '试题', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('10', '8', '_system_exam_paper', '试卷', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('11', '0', '_system_link', '友情链接', '1');

-- 权限分类
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('101', '3', '_role_youqinglianjie', '友情链接', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('102', '3', '_role_wenjian', '文件', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('103', '3', '_role_wenzhang', '文章', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('104', '3', '_role_tixing', '提醒', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('105', '3', '_role_yonghuliuyan', '用户留言', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('106', '3', '_role_guanliyuan', '管理员', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('107', '3', '_role_jingtaiyemian', '页面', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('108', '3', '_role_shangpinguanli', '商品', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('109', '3', '_role_yonghuguanli', '用户', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('110', '3', '_role_wenzhangpinglun', '文章评论', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('111', '3', '_role_zhandian', '站点', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('112', '3', '_role_juese', '角色', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('113', '3', '_role_fangwentongji', '访问统计', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('114', '3', '_role_daohanglan', '导航栏', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('115', '3', '_role_huihua', '会话', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('116', '3', '_role_shiti', '试题', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('117', '3', '_role_shijuan', '试卷', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('118', '3', '_role_moban', '模版', '1');
INSERT INTO `{{$prefix}}categories` (id, parent, alias, title, is_system) VALUES ('199', '3', '_role_qita', '其它', '1');