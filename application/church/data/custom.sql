-- 导航菜单
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10000', '2', '100', 'main_menu', '主导航菜单', '');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10001', '10000', '100', '', '首页', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10002', '10000', '100', '', '旅游', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10003', '10000', '100', '', '代购', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10004', '10002', '100', '', '日本', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10005', '10004', '100', '', '关西', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10006', '10004', '100', '', '关东', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10007', '10002', '100', '', '中国', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10008', '10003', '100', '', '母婴', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10009', '10003', '100', '', '美妆', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10010', '10003', '100', '', '美食', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10011', '10003', '100', '', '数码', '{$base_url}');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10012', '10000', '100', '', '联系我', '{$base_url}contact');
INSERT INTO `{{$prefix}}menus` (`id`, `parent`, `sort`, `alias`, `title`, `link`) VALUES ('10013', '10000', '100', '', '关于我', '{$base_url}about');

-- 小工具