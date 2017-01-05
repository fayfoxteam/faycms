<?php
use fay\core\Bootstrap;

define('START', microtime(true));
define('BASEPATH', realpath(__DIR__).DIRECTORY_SEPARATOR);//定义程序根目录绝对路径

/**
 * 为多应用设计的参数，对应application文件夹下的某个应用（文档结构上体现为文件夹）
 * 可以通过服务器配置环境变量方式实现，从而保证php代码完全一致
 * 
 * Apache定义环境变量语法：SetEnv FAYCMS_APPLICATION doc
 * Nginx定义环境变量语法：fastcgi_param FAYCMS_APPLICATION 'doc';
 */
define('APPLICATION', isset($_COOKIE['__app']) ? $_COOKIE['__app'] : (isset($_SERVER['FAYCMS_APPLICATION']) ? $_SERVER['FAYCMS_APPLICATION'] : 'blog'));

require __DIR__.'/_init.php';

$bootstrap = new Bootstrap();
//触发事件
\F::event()->trigger('before_system');
\fay\helpers\Runtime::append(__FILE__, __LINE__, '环境初始化完成');
$bootstrap->init();