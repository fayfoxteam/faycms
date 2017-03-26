-- 系统参数
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:sitename', 'Faycms开发文档  - 1.0', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:copyright', '&copy; 2012~2015 <a href=\"http://www.fayfox.com\" target=\"_blank\">fayfox.com</a>', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:beian', '浙ICP备12036784号-2', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:phone', '13616546418', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:fax', '', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:email', 'admin@fayfox.com', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:address', '', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:seo_index_title', 'Faycms开发文档  - 1.0', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:seo_index_keywords', 'Faycms,小妖cms,Faycms文档,Faycms手册,Faycms二次开发,文档中心,在线手册,phpfaycms,类库参考,开发框架,php框架,PHP开发框架', '', '{{$time}}', '{{$time}}', '1');
INSERT INTO `{{$prefix}}options` (`option_name`, `option_value`, `description`, `create_time`, `update_time`, `is_system`) VALUES ('site:seo_index_description', 'Faycms（小妖CMS）是一款基于PHP5.3+，自带轻量级MVC框架的CMS系统。完全免费、开源、提供详细技术文档。轻量、高效、架构清晰、易扩展。', '', '{{$time}}', '{{$time}}', '1');

INSERT INTO `{{$prefix}}categories` (`id`, `title`, `alias`, `parent`, `is_system`) VALUES ('1000', 'Fayfox', 'fayfox', '1', '1');