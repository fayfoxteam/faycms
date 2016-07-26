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
		'user'=>$_SERVER['HTTP_HOST'] == 'blog.faycms.com' ? 'faycms' : 'root',//用户名
		'password'=>$_SERVER['HTTP_HOST'] == 'blog.faycms.com' ? 'jDoBjHwVq6q2hQVN' : '',//密码
		'port'=>$_SERVER['HTTP_HOST'] == 'blog.faycms.com' ? 3306 : 3307,							//端口
		'dbname'=>'faycms_blog',				//数据库名
		'charset'=>'utf8',						//数据库编码方式
		'table_prefix'=>'fayfox_',				//数据库表前缀
	),
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend'
	),
	
	//'debug'=>false,
	//'environment'=>'production',
	'session'=>array(
		'ini_set'=>array(
			'cookie_lifetime'=>86400,
		),
	),
	//'enable_tools'=>false,
	
	'assets_url'=>preg_match('/^(\d+).fayfox.com$/', isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']) ? '' : 'http://static.faycms.com/',
);