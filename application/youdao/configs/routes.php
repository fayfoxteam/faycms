<?php
/**
 * 该配置文件会跟外层/config/routes.php文件的配置项进行合并
 */
return array(
	'/^about\/(\w+)$/'=>'about/index/alias/$1',
	'/^about$/'=>'about/index',
	'/^team$/'=>'team/index',
	'/^contact$/'=>'contact/index',
	'/^page\/(\d+)$/'=>'page/item/id/$1',
	'/^page\/(\w+)$/'=>'page/item/alias/$1',
	'/^post\/(\d+)$/'=>'post/item/id/$1',
	'/^case\/(\d+)$/'=>'case/item/id/$1',
	'/^c\/(\w+)$/'=>'post/index/c/$1',
	'/^t\/(\d+)$/'=>'post/index/t/$1',
	'/^post$/'=>'post/index',
	'/^team\/(\d+)$/'=>'team/item/id/$1',
	
	'/^service\/(\d+)$/'=>'service/index/id/$1',
);