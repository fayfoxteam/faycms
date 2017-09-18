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
        'host'=>'112.124.64.22',                    //数据库服务器
        'user'=>'yujiajia',//用户名
        'password'=>'shabiyujiajia',//密码
        'port'=>3306,                            //端口
        'dbname'=>'fayfox_yujiajia',                //数据库名
        'charset'=>'utf8',                        //数据库编码方式
        'table_prefix'=>'info_',                //数据库表前缀
    ),

    'debug'=>true,
);