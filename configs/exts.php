<?php
/**
 * 若具体application中存在此配置文件，则配置项会被合并
 * 
 * 该配置文件用于定义网站url的扩展名，默认扩展名在main.php中配置
 * 出于书写方便，这里的数组项只支持通配符*，其最终会被转为正则表达式进行匹配，转换规则
 *     / => \/
 *     * => .*
 */
return array(
	'.js'=>array('api/analyst'),
	''=>array('file/download*', 'file/pic*', 'file/vcode*', 'file/qrcode*', 'redirect*', '/', 'admin*', 'install/*', 'tools*', 'a', 'widget/*', 'assets/*', 'apps/*'),
);