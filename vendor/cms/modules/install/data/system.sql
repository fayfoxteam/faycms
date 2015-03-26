-- 系统用户和留言用户
INSERT INTO `{{$prefix}}users` (id, username, realname, nickname) VALUES ('1', '系统', '系统', '系统');
INSERT INTO `{{$prefix}}users` (id, username, realname, nickname) VALUES ('2', '用户留言收件人', '用户留言收件人', '用户留言收件人');
INSERT INTO `{{$prefix}}users` (id, username, realname, nickname) VALUES ('3', '系统消息', '系统消息', '系统消息');

-- users表id从10000开始自递增
ALTER TABLE {{$prefix}}users AUTO_INCREMENT = 10000;

-- categories表id从10000开始自递增
ALTER TABLE {{$prefix}}categories AUTO_INCREMENT = 10000;

-- menus表id从1000开始自递增
ALTER TABLE {{$prefix}}menus AUTO_INCREMENT = 1000;

-- roles表新增超级管理员和系统角色
INSERT INTO `{{$prefix}}roles` VALUES ('1', '普通用户', '', '0', '1');
INSERT INTO `{{$prefix}}roles` VALUES ('100', '系统', '', '0', '0');
INSERT INTO `{{$prefix}}roles` VALUES ('101', '超级管理员', '', '0', '0');

-- files表id从1000开始递增
ALTER TABLE {{$prefix}}files AUTO_INCREMENT = 1000;

-- posts表id从1000开始递增
ALTER TABLE {{$prefix}}posts AUTO_INCREMENT = 1000;

-- pages表id从1000开始递增
ALTER TABLE {{$prefix}}pages AUTO_INCREMENT = 1000;

-- menus表id从1000开始递增
ALTER TABLE {{$prefix}}menus AUTO_INCREMENT = 1000;

-- actions表id从10000开始递增
ALTER TABLE {{$prefix}}actions AUTO_INCREMENT = 10000;

-- options表id从100开始递增
INSERT INTO `{{$prefix}}options` (`id`, `option_name`, `option_value`, `description`, `create_time`, `last_modified_time`, `is_system`) VALUES ('1', 'system.post_review', '0', '是否启用文章审核功能', '{{$time}}', '{{$time}}', '1');
ALTER TABLE {{$prefix}}options AUTO_INCREMENT = 100;


-- 访问统计本地站点
INSERT INTO `{{$prefix}}analyst_sites` VALUES ('1', 'localhost', '本站', '0');

-- 管理员菜单和用户自定义菜单
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('1', '0', '_admin_menu', '后台菜单集', '', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('2', '0', '_user_menu', '用户自定义菜单集', '', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('110', '100', 'role', '权限', 'fa fa-gavel', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('111', '110', '', '角色列表', '', 'admin/role/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('112', '110', '', '添加角色', '', 'admin/role/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('113', '110', '', '所有权限', '', 'admin/action/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('114', '110', '', '权限分类', '', 'admin/action/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('100', '1', '_admin_main', '后台主菜单', '', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('120', '100', 'user', '用户管理', 'fa fa-users', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('121', '120', '', '所有用户', '', 'admin/user/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('122', '120', '', '添加用户', '', 'admin/user/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('123', '120', '', '所有管理员', '', 'admin/operator/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('124', '120', '', '添加管理员', '', 'admin/operator/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('130', '100', 'post', '文章', 'fa fa-edit', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('131', '130', '', '所有文章', '', 'admin/post/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('132', '130', '', '分类（发布）', '', 'admin/post/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('133', '130', '', '标签', '', 'admin/tag/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('134', '130', '', '关键词', '', 'admin/keyword/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('140', '100', 'page', '页面', 'fa fa-bookmark', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('141', '140', '', '所有页面', '', 'admin/page/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('142', '140', '', '添加页面', '', 'admin/page/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('143', '140', '', '分类', '', 'admin/page/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('150', '100', 'message', '留言', 'fa fa-comments-o', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('151', '150', '', '文章评论', '', 'admin/comment/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('152', '150', '', '联系我们', '', 'admin/contact/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('153', '150', '', '会话', '', 'admin/chat/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('160', '100', '', '导航栏', 'fa fa-map-marker', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('161', '160', '', '自定义导航', '', 'admin/menu/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('170', '100', 'link', '友情链接', 'fa fa-unlink', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('171', '170', '', '所有友链', '', 'admin/link/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('172', '170', '', '添加友链', '', 'admin/link/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('173', '170', '', '分类', '', 'admin/link/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('180', '100', 'cat', '分类', 'fa fa-sitemap', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('181', '180', '', '所有分类', '', 'admin/category/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('190', '100', 'site', '站点', 'fa fa-cog', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('201', '200', '', '访客统计', '', 'admin/analyst/visitor');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('202', '200', '', '访问日志', '', 'admin/analyst/views');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('203', '200', '', '页面PV量', '', 'admin/analyst/pv');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('204', '200', '', '站点管理', '', 'admin/analyst-site/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('205', '200', '', '蜘蛛爬行记录', '', 'admin/analyst/spiderlog');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('210', '100', 'file', '文件', 'fa fa-files-o', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('211', '210', '', '所有文件', '', 'admin/file/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('212', '210', '', '上传文件', '', 'admin/file/do-upload');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('220', '100', 'notification', '系统通知', 'fa fa-comment', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('221', '220', '', '我的消息', '', 'admin/notification/my');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('222', '220', '', '发送消息', '', 'admin/notification/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('223', '220', '', '消息分类', '', 'admin/notification/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('230', '100', 'template', '模版', 'fa fa-envelope-o', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('231', '230', '', '添加模版', '', 'admin/template/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('232', '230', '', '模版管理', '', 'admin/template/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('240', '100', 'exam', '考试系统', 'fa fa-graduation-cap', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('241', '240', 'exam-question', '试题', '', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('242', '241', '', '试题库', '', 'admin/exam-question/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('243', '241', '', '添加试题', '', 'admin/exam-question/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('244', '241', '', '试题分类', '', 'admin/exam-question/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('245', '240', 'exam-paper', '试卷', '', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('246', '245', '', '试卷列表', '', 'admin/exam-paper/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('247', '245', '', '组卷', '', 'admin/exam-paper/create');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('248', '245', '', '阅卷', '', 'admin/exam-exam/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('249', '245', '', '试卷分类', '', 'admin/exam-paper/cat');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('200', '100', 'analyst', '访问统计', 'fa fa-bar-chart-o', 'javascript:;');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('191', '190', '', '站点参数', '', 'admin/site/options');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('192', '190', '', '参数列表', '', 'admin/option/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('193', '190', '', '系统日志', '', 'admin/log/index');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('194', '190', '', '小工具', '', 'admin/widget/instances');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `alias`, `title`, `css_class`, `link`) VALUES ('195', '190', '', '所有小工具', '', 'admin/widget/index');