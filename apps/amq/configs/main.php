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
        'host'=>'localhost',//数据库服务器
        'user'=>$_SERVER['SERVER_ADDR'] == '127.0.0.71' ? 'root' : 'amdbnews',//用户名
        'password'=>$_SERVER['SERVER_ADDR'] == '127.0.0.71' ? '' : '22cn@#$dbnews',//密码
        'port'=>3306,//端口
        'dbname'=>$_SERVER['SERVER_ADDR'] == '127.0.0.71' ? 'faycms_amq' : 'dedenews',//数据库名
        'charset'=>'utf8',//数据库编码方式
        'table_prefix'=>'faycms_',//数据库表前缀
    ),
    
    'debug'=>false,
    
    'environment'=>$_SERVER['SERVER_ADDR'] == '127.0.0.71' ? 'development' : 'production',
    //'environment'=>'development',

    'session'=>array(
        'ini_set'=>array(
            //session保存30天，反正不存在前台登录
            'gc_maxlifetime'=>2592000,
            'cookie_lifetime'=>2592000,
        ),
    ),
);