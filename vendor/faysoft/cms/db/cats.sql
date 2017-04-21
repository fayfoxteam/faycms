INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('1', '未分类', '_system_post', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('2', '页面分类', '_system_page', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('3', '权限', '_system_action', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('4', '系统消息', '_system_notification', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('5', '用户留言', '_system_messages', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('6', '商品分类', '_system_goods', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('7', '优惠卷', '_system_voucher', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('8', '考试', '_system_exam', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('9', '试题', '_system_exam_question', '8', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('10', '试卷', '_system_exam_paper', '8', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('11', '友情链接', '_system_link', '0', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('12', '文件分类', '_system_file', '0', '0');

-- 权限分类
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('101', '友情链接', '_system_role_youqinglianjie', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('102', '文件', '_system_role_wenjian', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('103', '文章', '_system_role_wenzhang', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('104', '提醒', '_system_role_tixing', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('105', '用户留言', '_system_role_yonghuliuyan', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('106', '管理员', '_system_role_guanliyuan', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('107', '页面', '_system_role_jingtaiyemian', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('108', '商品', '_system_role_shangpinguanli', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('109', '用户', '_system_role_yonghuguanli', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('110', '文章评论', '_system_role_wenzhangpinglun', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('111', '站点', '_system_role_zhandian', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('112', '角色', '_system_role_juese', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('113', '访问统计', '_system_role_fangwentongji', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('114', '导航栏', '_system_role_daohanglan', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('115', '会话', '_system_role_huihua', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('116', '试题', '_system_role_shiti', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('117', '试卷', '_system_role_shijuan', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('118', '模版', '_system_role_moban', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('198', '系统修复（一般不分配）', '_system_role_xiufu', '3', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('199', '其它（一般不分配）', '_system_role_qita', '3', '1');

-- 文件分类
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('201', '文章', '_system_file_post', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('202', '静态页', '_system_file_page', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('203', '商品', '_system_file_goods', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('204', '分类插图', '_system_file_cat', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('205', '小工具', '_system_file_widget', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('206', '用户头像', '_system_file_avatar', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('207', '考试系统', '_system_file_exam', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('208', '友情链接', '_system_file_link', '12', '1');
INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('299', '其他', '_system_file_other', '12', '1');