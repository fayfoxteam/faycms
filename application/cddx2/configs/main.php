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
		'user'=>$_SERVER['HTTP_HOST'] == 'cddx2.faycms.com' ? 'faycms' : 'root',//用户名
		'password'=>$_SERVER['HTTP_HOST'] == 'cddx2.faycms.com' ? 'jDoBjHwVq6q2hQVN' : '',//密码
		'port'=>3306,							//端口
		'dbname'=>'faycms_cddx2',					//数据库名
		'charset'=>'utf8',						//数据库编码方式
		'table_prefix'=>'cddx2_',				//数据库表前缀
	),
	
	/*
	 * 在一台服务器上跑多个cms的时候，以此区分session，可以随便设置一个
	 */
	'session_namespace'=>'cddx2',
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend'
	),
	
	//'environment'=>'production',
	
	'debug'=>false,
);