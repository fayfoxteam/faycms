<?php
/**
 * 该文件是默认配置信息，所有配置项都有可能被具体的application中的配置项所覆盖
 */
return array(
	/*
	 * 站点根目录，若为空，则系统会猜测一个，强烈建议手工设定。
	 * 此参数为系统路由的基础，若设置错误，系统将无法正常运行。
	 * 若系统在站点根目录，则该参数格式如下：
	 * http://www.faycms.com/
	 * 若系统被放置在二级目录，则该参数格式或许是这个样子：
	 * http://www.faycms.com/fayfox/
	 * 注意不要忘了最后的斜杠
	 */
	//'base_url'=>'http://55.fayfox.com/fayfox/',
	
	/*
	 * 数据库参数
	 */
	'db'=>array(
		'host'=>'localhost',					//数据库服务器
		'user'=>'root',							//用户名
		'password'=>'',							//密码
		'port'=>3306,							//端口
		'dbname'=>'fayfox_temp',				//数据库名
		'charset'=>'utf8',						//数据库编码方式（本产品不支持gb2312编码，但是可以选择utf8或者utf8mb4）
		'table_prefix'=>'fayfox_',				//数据库表前缀
	),
	
	'session'=>array(
		/*
		 * 命名空间
		 * 用一个域名管理多个APP的时候，以此区分session，默认为APPLICATION
		 */
		'namespace'=>APPLICATION,
		'ini_set'=>array(
			'use_cookies'=>1,//是否启用cookie存储session id
			'use_only_cookies'=>0,//是否只允许用cookie存储session id
			'name'=>'faysess',//session字段名称
			'save_handler'=>null,//存储方式。若为null，则应用php.ini文件中的配置
			'save_path'=>null,//session存储路径，必须保证此目录存在，系统不自动生成目录，若不存在的话会报错。若为null，则应用php.ini文件中的配置
			'gc_maxlifetime'=>1440,//session过期时间
		),
	),
	
	/*
	 * 用一个域名管理多个APP的时候，以此区分cookie，默认为APPLICATION加下划线
	 */
	'cookie_prefix'=>APPLICATION . '_',
	
	/*
	 * 默认url后缀
	 * 可通过config/ext.php配置文件对单独的url再做设置
	 */
	'url_suffix'=>'',
	
	/*
	 * 运行环境，设为development则开启所有报错，设为production则关闭所有报错
	 */
	'environment'=>'development',
	
	/*
	 * 若为true，则页面地步会列出所有被执行的sql语句等信息
	 */
	'debug'=>true,
	
	/*
	 * 时间相关设置
	 */
	'date'=>array(
		'format'=>'Y-m-d H:i:s',				//日期格式
		'default_timezone'=>'PRC',				//默认时区
	),
	
	/*
	 * 顶级域名，一般用于设置全局cookie，并不一定用到
	 * 若为false，则为当前域名
	 * 若是localhost本地测试，需设为false，因为chrome不接受域名为localhost的cookie
	 */
	'tld'=>false,
	
	/*
	 * 上传文件
	 */
	'upload'=>array(							//文件上传类的相关配置
		'upload_path'=>'./uploads/',			//文件上传路径
		'allowed_types'=>'*',					//允许上传的文件类型，详见mimes.php，若为星号，则允许所有类型
		'max_size'=>'20971520',					//这个最大值不能大于php.ini中设置的最大值
	),

	/*
	 * 当前application包含的模块
	 * 该参数在具体application中进行设置
	 */
	'modules'=>array(),
	
	/*
	 * 默认路由，即直接访问base_url时所对应的路由
	 */
	'default_router'=>array(
		'module'=>'frontend',
		'controller'=>'index',
		'action'=>'index',
	),
	
	/*
	 * 若用到加密类，需要配置此key
	 */
	'encryption_key'=>'m3cQ3mFAuy6z7LF2',//加密用的密钥
	
	/*
	 * 默认缓存方式
	 * 若要禁用默认缓存，将这个值设为空即可
	 */
	'default_cache_driver'=>'file',
);