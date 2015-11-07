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
		'user'=>'root',							//用户名
		'password'=>'',							//密码
		'port'=>3306,							//端口
		'dbname'=>'faycms_glhs',				//数据库名
		'charset'=>'utf8',						//数据库编码方式
		'table_prefix'=>'faycms_',				//数据库表前缀
	),
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend'
	),
	
	'debug'=>$_SERVER['HTTP_HOST'] == '55.fayfox.com' || $_SERVER['HTTP_X_FORWARDED_HOST'] == '55.fayfox.com' ? true : false,
);