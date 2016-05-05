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
		'user'=>'nginx',							//用户名
		'password'=>'nimei001',							//密码
		'port'=>3306,							//端口
		'dbname'=>'w',				//数据库名
		'charset'=>'utf8',						//数据库编码方式
		'table_prefix'=>'w_',				//数据库表前缀
	),
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
        'frontend','api'
	),
	
	'debug'=>true,


	/* 短信接口配置*/
	'UCPAAS_ACCOUNTSID'	 => 'b469917f1df042b8f47be6812f9ce236',
	'UCPAAS_TOKEN'       => '0ad57332f45a3cb2a982aafc277c9869',
	'UCPAAS_APPID'       => 'e62613d039ac4ce78feb042a77e887d8',
);