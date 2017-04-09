-- 基础分类
INSERT INTO `{{$prefix}}categories` (parent, sort, alias, title) VALUES ('1', '100', '_blog', '博客');
INSERT INTO `{{$prefix}}categories` (parent, sort, alias, title) VALUES ('1', '100', '_material', '素材');
INSERT INTO `{{$prefix}}categories` (parent, sort, alias, title) VALUES ('1', '100', '_site', '网站');
INSERT INTO `{{$prefix}}categories` (parent, sort, alias, title) VALUES ('1', '100', '_inspiration', '灵感');

-- 角色附加属性
INSERT INTO `{{$prefix}}props` (`refer`, `type`, `title`, `element`, `alias`, `create_time`) VALUES ('1', '2', '人气', '1', 'popularity', '{{$time}}');
INSERT INTO `{{$prefix}}props` (`refer`, `type`, `title`, `element`, `alias`, `create_time`) VALUES ('1', '2', '创造力', '1', 'creativity', '{{$time}}');
INSERT INTO `{{$prefix}}props` (`refer`, `type`, `title`, `element`, `alias`, `create_time`) VALUES ('1', '2', '粉丝', '1', 'fans', '{{$time}}');
INSERT INTO `{{$prefix}}props` (`refer`, `type`, `title`, `element`, `alias`, `create_time`) VALUES ('1', '2', '关注', '1', 'follow', '{{$time}}');


