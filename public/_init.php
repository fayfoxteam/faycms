<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

use fay\core\ErrorHandler;
use fay\helpers\DumperHelper;

define('DS', DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', realpath(BASEPATH.'..'.DS.'application'.DS.APPLICATION).DS);
define('VENDOR_PATH', realpath(BASEPATH.'..'.DS.'vendor') . DS);
define('SYSTEM_PATH', realpath(VENDOR_PATH.'faysoft') . DS);
define('CMS_PATH', realpath(SYSTEM_PATH.'cms').DS);

require VENDOR_PATH . 'autoload.php';
//包含基础文件
require SYSTEM_PATH.'fay/core/Loader.php';

//捕获报错
$error_handler = new ErrorHandler();
$error_handler->register();

/**
 * 定义一个快捷方式，方便调试
 * @param $var
 * @param bool $encode
 * @param bool $return
 * @return string
 */
function pr($var, $encode = false, $return = false){
	return DumperHelper::pr($var, $encode, $return);
}

/**
 * @param $var
 * @param int $depth
 */
function dump($var, $depth = 10){
	DumperHelper::dump($var, $depth);
}

/**
 * 循环调用dump后die脚本
 */
function dd(){
	array_map(function($x){
		dump($x);
	}, func_get_args());
	die;
}