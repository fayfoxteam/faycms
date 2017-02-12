<?php
/**
 * 该文件的配置项将覆盖外层/config/main.php的配置
 * 若不设置则默认使用外面的配置参数
 */
return array(
	/*
	 * 数据库参数
	 */
	'db'=>array(
		'host'=>'localhost',					//数据库服务器
		'user'=>'faycms',							//用户名
		'password'=>'jDoBjHwVq6q2hQVN',							//密码
		'port'=>3306,							//端口
		'dbname'=>'faycms_valentine',					//数据库名
		'charset'=>preg_match('/^(\d+).fayfox.com$/', isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']) ? 'utf8mb4' : 'utf8',						//数据库编码方式
		'table_prefix'=>'faycms_',				//数据库表前缀
	),
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend'
	),
	
	'debug'=>false,
	
	'base_url'=>preg_match('/^(\d+).fayfox.com$/', isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']) ? null : 'http://valentine.faycms.com/',
	
	'assets_url'=>preg_match('/^(\d+).fayfox.com$/', isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']) ? null : 'http://qiniu.cdn.faycms.com/assets/',
);