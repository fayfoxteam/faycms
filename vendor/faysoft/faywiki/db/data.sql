-- 文档分类
INSERT INTO `{{$prefix}}categories` (`title`, `alias`, `parent`, `is_system`) VALUES ('百科文档根分类', '_system_wiki_doc', '0', '1');
INSERT INTO `{{$prefix}}categories` (`title`, `alias`, `parent`, `is_system`) VALUES ('百科文档', '_system_file_wiki_doc', '12', '1');