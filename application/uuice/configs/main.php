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
		'host'=>'121.40.188.120',					//数据库服务器
		'user'=>'nginx',//用户名
		'password'=>'nimei001',//密码
		'port'=>3306,							//端口
		'dbname'=>'uuice_dev',					//数据库名
		'charset'=>'utf8',						//数据库编码方式
		'table_prefix'=>'uuice_',				//数据库表前缀
	),
	
	/*
	 * 在一台服务器上跑多个cms的时候，以此区分session，可以随便设置一个
	 */
	'session_namespace'=>'uuice',
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend','doc'
	),
);