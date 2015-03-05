<?php
return array(
    /*
     * 数据库参数
*/
    'db'=>array(
//         'host'=>'114.215.134.73',					//数据库服务器
//         'user'=>'whis',							//用户名
//         'password'=>'chen19921012',							//密码
        
        'host'=>'127.0.0.1',					//数据库服务器
        'user'=>'root',							//用户名
        'password'=>'',							//密码
        'port'=>3306,							//端口
        'dbname'=>'fay_tzcs',					//数据库名
        'charset'=>'utf8',						//数据库编码方式
        'table_prefix'=>'fay_',				//数据库表前缀
    ),

    /*
     * 在一台服务器上跑多个cms的时候，以此区分session，可以随便设置一个
*/
    'session_namespace'=>'tzcs',

    /*
     * 若为true，则页面地步会列出所有被执行的sql语句等信息
*/
    'debug'=>false,

    /*
     * 当前application包含的模块
*/
    'modules'=>array(
        'frontend'
    ),
    
    /*
     * 是否启用钩子，视application而定
     * 默认为false
     */
    'hook'=>true,
    
    /*
     * 默认url后缀
* 可通过config/ext.php配置文件对单独的url再做设置
*/
    'url_suffix'=>'.shtml',
    
    /*
     * 上传文件
     */
    'upload'=>array(							//文件上传类的相关配置
        'upload_path'=>'./uploads/',			//文件上传路径
        'allowed_types'=>'*',					//允许上传的文件类型，详见mimes.php，若为星号，则允许所有类型
        'max_size'=>'20971520',					//这个最大值不能大于php.ini中设置的最大值
    ),
);