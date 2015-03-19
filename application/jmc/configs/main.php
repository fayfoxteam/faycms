<?php
return array(
    'db'=>array(
        // 'host'=>'db',					//数据库服务器
        // 'user'=>'root',							//用户名
        // 'password'=>'',							//密码
        'host'=>'114.215.134.73',					//数据库服务器
        'user'=>'whis',							//用户名
        'password'=>'chen19921012',							//密码
        
        'port'=>3306,							//端口
        'dbname'=>'fay_jmc',				//数据库名
        'charset'=>'utf8',						//数据库编码方式
        'table_prefix'=>'fay_',				//数据库表前缀
    ),
    
    /*
     * 在一台服务器上跑多个cms的时候，以此区分session，可以随便设置一个
    */
    'session_namespace'=>'jmc',
    
    /*
     * 默认url后缀
    * 可通过config/ext.php配置文件对单独的url再做设置
    */
    'url_suffix'=>'.shtml',
    
    /*
     * 运行环境，设为development则开启所有报错，设为production则关闭所有报错
    */
    'environment'=>'development',
    
    /*
     * 若为true，则页面地步会列出所有被执行的sql语句等信息
    */
    'debug'=>false,
);
