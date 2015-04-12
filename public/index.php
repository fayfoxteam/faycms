<?php
use fay\core\Bootstrap;
use fay\core\Hook;

session_start();//开启session
define('START', microtime(true));
define('BASEPATH', realpath(__DIR__).DIRECTORY_SEPARATOR);//定义程序根目录绝对路径
define('APPLICATION', isset($_SESSION['__app']) ? $_SESSION['__app'] : 'vote');

require __DIR__.'/_init.php';
require __DIR__.'/until.php';

$bootstrap = new Bootstrap();
if($bootstrap->config('hook')){
	Hook::getInstance()->call('before_system');
}
$bootstrap->init();