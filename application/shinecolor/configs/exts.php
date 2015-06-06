<?php
/**
 * 该配置文件会跟外层/config/exts.php文件的配置项进行合并
 * 
 * 该配置文件用于定义网站url的扩展名，默认扩展名在main.php中配置
 * 所有数组项均会被转为正则表达式进行匹配，转换规则
 *     / => \/
 *     * => .*
 */
return array(
	'/'=>array('service', 'news', 'product'),
);