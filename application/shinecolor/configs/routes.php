<?php
/**
 * 该配置文件会跟外层/config/routes.php文件的配置项进行合并
 */
return array(
	'/^news\/(\d+)$/'=>'news/item/id/$1',
	'/^news\/(\w+)$/'=>'news/index/alias/$1',
	
	'/^product\/(\d+)$/'=>'product/item/id/$1',
	
	'/^service\/(\w+)$/'=>'service/item/alias/$1',
);