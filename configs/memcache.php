<?php
/**
 * 若要用到memcache，可以进行设置
 * 本框架暂不支持多台memcache协同运作
 */
return array(
	'memcache'=>array(
		'host'=>'127.0.0.1',
		'port'=>11211,
		'flag'=>0,
		'expire'=>3600,
	),
);