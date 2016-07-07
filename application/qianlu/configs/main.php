<?php
return array(
	/*
	 * 数据库参数
	 */
	'db'=>array(
		'host'=>'localhost',					//数据库服务器
		'user'=>'faycms',//用户名
		'password'=>'jDoBjHwVq6q2hQVN',//密码
		'port'=>3306,							//端口
		'dbname'=>'faycms_qianlu',				//数据库名
		'charset'=>'utf8',						//数据库编码方式
		'table_prefix'=>'faycms_',				//数据库表前缀
	),
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend'
	),
	
	/*
	 * 默认url后缀
	 * 可通过config/ext.php配置文件对单独的url再做设置
	 */
	'url_suffix'=>'.html',
	
	'assets_url'=>$_SERVER['HTTP_HOST'] == 'qianlu.fayfox.com' ? 'http://qiniu.cdn.faycms.com/' : '',
	
	'debug'=>false,
);