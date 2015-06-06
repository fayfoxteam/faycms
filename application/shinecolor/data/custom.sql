-- 页面
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (1, '企业介绍', 'introduce');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (2, '企业文化', 'culture');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (3, '组织结构', 'structure');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (4, '企业介绍', 'service');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (5, '常见问题', 'qa');

-- 页面分类
INSERT INTO `{{$prefix}}pages_categories` (page_id, cat_id) VALUES ('1', '231');
INSERT INTO `{{$prefix}}pages_categories` (page_id, cat_id) VALUES ('2', '231');
INSERT INTO `{{$prefix}}pages_categories` (page_id, cat_id) VALUES ('3', '231');
INSERT INTO `{{$prefix}}pages_categories` (page_id, cat_id) VALUES ('4', '235');
INSERT INTO `{{$prefix}}pages_categories` (page_id, cat_id) VALUES ('5', '235');

-- 基础参数
INSERT INTO `{{$prefix}}options` VALUES ('4', 'contact_phone', '13616546418', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('5', 'contact_address', '浙江 杭州', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('6', 'contact_email', 'admin@fayfox.com', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('7', 'contact_qq', '369281831', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('8', 'sitename', '辉煌彩印', '', '{{$time}}', '0', '1');

-- 基础分类
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('230', '1', '100', 'news', '新闻中心', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('231', '2', '100', 'about', '关于辉煌', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('232', '1', '100', 'product', '产品', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('233', '230', '100', 'company', '公司动态', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('234', '230', '100', 'project', '项目动态', '');
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('235', '2', '100', 'service', '服务介绍', '');

-- 小工具
INSERT INTO `{{$prefix}}widgets` VALUES ('15', 'index-slides-camera', '[{\\\"file_id\\\":\\\"1\\\",\\\"link\\\":\\\"http:\\\\/\\\\/localhost\\\\/shinecolor\\\\/uploads\\\\/widget\\\\/2014\\\\/04\\\\/c82468556e0ddec39d5f5a5e1e443582.jpg\\\",\\\"title\\\":\\\"slide.jpg\\\"},{\\\"file_id\\\":\\\"4\\\",\\\"link\\\":\\\"http:\\\\/\\\\/localhost\\\\/shinecolor\\\\/uploads\\\\/widget\\\\/2014\\\\/04\\\\/ba368ad65dc565d7763e831242fa1406.jpg\\\",\\\"title\\\":\\\"2013042413362843.jpg\\\"},{\\\"file_id\\\":\\\"3\\\",\\\"link\\\":\\\"http:\\\\/\\\\/localhost\\\\/shinecolor\\\\/uploads\\\\/widget\\\\/2014\\\\/04\\\\/cd2ddd64882629f5f6ebd8f86fe1c134.jpg\\\",\\\"title\\\":\\\"slide.jpg\\\"}]', 'common/jq_camera', '首页-轮播图', '1');
INSERT INTO `{{$prefix}}widgets` VALUES ('16', 'index-abstract', '{\\\"content\\\":\\\"<p>\\\\u6211\\\\u4eec\\\\u59cb\\\\u7ec8\\\\u4e3a\\\\u5ba2\\\\u6237\\\\u63d0\\\\u4f9b\\\\u4e2a\\\\u6027\\\\u5316<span class=\\\\\\\"color-red\\\\\\\">\\\\u5b9a\\\\u5236\\\\u670d\\\\u52a1<\\\\/span><\\\\/p>\\\\r\\\\n\\\\r\\\\n<p>\\\\u4ece\\\\u63a5\\\\u53d7\\\\u54a8\\\\u8be2\\\\u3001\\\\u8bbe\\\\u8ba1\\\\u3001\\\\u5236\\\\u4f5c\\\\u3001\\\\u5370\\\\u5237\\\\u3001\\\\u5370\\\\u540e\\\\u52a0\\\\u5de5\\\\u5230\\\\u8d28\\\\u68c0\\\\u3001\\\\u5305\\\\u88c5\\\\u3001\\\\u9001\\\\u8d27\\\\u4e0a\\\\u95e8\\\\u3001\\\\u56de\\\\u8bbf<\\\\/p>\\\\r\\\\n\\\\r\\\\n<p>\\\\u5df2\\\\u7ecf\\\\u5f62\\\\u6210\\\\u4e86<span class=\\\\\\\"color-red\\\\\\\">\\\\u4e00\\\\u7ad9\\\\u5f0f\\\\u7684\\\\u4e13\\\\u4e1a\\\\u670d\\\\u52a1\\\\u6d41\\\\u7a0b<\\\\/span><\\\\/p>\\\\r\\\\n\\\"}', 'common/text', '首页-简介', '1');
INSERT INTO `{{$prefix}}widgets` VALUES ('14', 'contact', '{\\\"data\\\":[{\\\"key\\\":\\\"\\\\u516c\\\\u53f8\\\\u540d\\\\u79f0\\\",\\\"value\\\":\\\"\\\\u4e0a\\\\u865e\\\\u5e02\\\\u8f89\\\\u714c\\\\u5f69\\\\u5370\\\\u6709\\\\u9650\\\\u516c\\\\u53f8\\\"},{\\\"key\\\":\\\"\\\\u90ae\\\\u7f16\\\",\\\"value\\\":\\\"312300\\\"},{\\\"key\\\":\\\"\\\\u7535\\\\u8bdd\\\",\\\"value\\\":\\\"0574-62495282 0574-62495281\\\"},{\\\"key\\\":\\\"\\\\u4f20\\\\u771f\\\",\\\"value\\\":\\\"0574-62495280\\\"},{\\\"key\\\":\\\"\\\\u90ae\\\\u7bb1\\\",\\\"value\\\":\\\"syzgys@126.com\\\"},{\\\"key\\\":\\\"\\\\u7f51\\\\u5740\\\",\\\"value\\\":\\\"www.shine-color.com\\\"},{\\\"key\\\":\\\"\\\\u5730\\\\u5740\\\",\\\"value\\\":\\\"\\\\u4e0a\\\\u865e\\\\u5e02\\\\u9a7f\\\\u4ead\\\\u9547\\\\u4e94\\\\u592b\\\\u5de5\\\\u4e1a\\\\u533a\\\"}],\\\"template\\\":\\\"<p><label>{\$key}\\\\uff1a<\\\\/label>{\$value}<\\\\/p> \\\"}', 'common/options', '联系方式', '1');
INSERT INTO `{{$prefix}}widgets` VALUES ('17', 'business', '{\\\"data\\\":[{\\\"key\\\":\\\"\\\\u5f69\\\\u76d2\\\",\\\"value\\\":\\\"\\\\u5f69\\\\u76d2 \\\\u63cf\\\\u8ff0\\\"},{\\\"key\\\":\\\"\\\\u5c55\\\\u793a\\\\u76d2\\\",\\\"value\\\":\\\"\\\\u5c55\\\\u793a\\\\u76d2 \\\\u63cf\\\\u8ff0\\\"},{\\\"key\\\":\\\"\\\\u5f69\\\\u5361\\\",\\\"value\\\":\\\"\\\\u5f69\\\\u5361 \\\\u63cf\\\\u8ff0\\\"},{\\\"key\\\":\\\"\\\\u6ce1\\\\u58f3\\\",\\\"value\\\":\\\"\\\\u6ce1\\\\u58f3 \\\\u63cf\\\\u8ff0\\\"},{\\\"key\\\":\\\"\\\\u5916\\\\u7bb1\\\",\\\"value\\\":\\\"\\\\u5916\\\\u7bb1 \\\\u63cf\\\\u8ff0\\\"},{\\\"key\\\":\\\"\\\\u4e0d\\\\u5e72\\\\u80f6\\\",\\\"value\\\":\\\"\\\\u4e0d\\\\u5e72\\\\u80f6 \\\\u63cf\\\\u8ff0\\\"}],\\\"template\\\":\\\"\\\"}', 'common/options', '首页-主营业务', '1');

-- 顶部导航
INSERT INTO `{{$prefix}}menus` VALUES ('1', '0', '100', '1', '14', '_top', '顶部导航', '', '', '');
INSERT INTO `{{$prefix}}menus` VALUES ('2', '1', '100', '2', '3', 'home', '首&nbsp;&nbsp;&nbsp;&nbsp;页', '', '{$base_url}index.html', '');
INSERT INTO `{{$prefix}}menus` VALUES ('3', '1', '100', '4', '5', 'about', '关于辉煌', '', '{$base_url}about.html', '');
INSERT INTO `{{$prefix}}menus` VALUES ('4', '1', '100', '6', '7', 'service', '服务介绍', '', '{$base_url}product/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('5', '1', '100', '8', '9', 'news', '新闻中心', '', '{$base_url}news/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('6', '1', '100', '10', '11', 'client', '合作客户', '', '{$base_url}client.html', '');
INSERT INTO `{{$prefix}}menus` VALUES ('7', '1', '100', '12', '13', 'contact', '联系我们', '', '{$base_url}contact.html', '');