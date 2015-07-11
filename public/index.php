<?php
use fay\core\Bootstrap;
use fay\core\Hook;

session_start();//开启session
define('START', microtime(true));
define('BASEPATH', realpath(__DIR__).DIRECTORY_SEPARATOR);//定义程序根目录绝对路径

/**
 * 为多应用设计的参数，对应application文件夹下的某个应用（文档结构上体现为文件夹）
 * 可以通过服务器配置环境变量方式实现，从而保证php代码完全一致
 * 
 * Apache定义环境变量语法：SetEnv FAYCMS_APPLICATION doc
 * Nginx定义环境变量语法：fastcgi_param FAYCMS_APPLICATION 'doc';
 */
define('APPLICATION', isset($_SESSION['__app']) ? $_SESSION['__app'] : (isset($_SERVER['FAYCMS_APPLICATION']) ? $_SERVER['FAYCMS_APPLICATION'] : 'blog'));

require __DIR__.'/_init.php';

$bootstrap = new Bootstrap();
//hook
Hook::getInstance()->call('before_system');
$bootstrap->init();