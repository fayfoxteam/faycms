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
        'user'=>strpos($_SERVER['HTTP_HOST'], 'fay') !== false ? 'root' : 'amdbnews',//用户名
        'password'=>strpos($_SERVER['HTTP_HOST'], 'fay') !== false ? '' : '22cn@#$dbnews',//密码
        'port'=>3306,//端口
        'dbname'=>strpos($_SERVER['HTTP_HOST'], 'fay') !== false ? 'faycms_amq' : 'dedenews',//数据库名
        'charset'=>'utf8',//数据库编码方式
        'table_prefix'=>'faycms_',//数据库表前缀
    ),
    
    'debug'=>false,
    
    //'environment'=>strpos($_SERVER['HTTP_HOST'], 'amq.com') !== false || strpos($_SERVER['HTTP_HOST'], '22.cn') !== false ? 'production' : 'development',
    'environment'=>'development',

    'session'=>array(
        'ini_set'=>array(
            //session保存30天，反正不存在前台登录
            'gc_maxlifetime'=>2592000,
            'cookie_lifetime'=>2592000,
        ),
    ),
);