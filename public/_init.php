<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

use fay\core\ErrorHandler;

define('DS', DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', realpath(BASEPATH.'..'.DS.'application'.DS.APPLICATION).DS);
define('SYSTEM_PATH', realpath(BASEPATH.'..'.DS.'vendor') . DS);
define('BACKEND_PATH', realpath(SYSTEM_PATH.'cms').DS);
define('MODULE_PATH', realpath(APPLICATION_PATH . 'modules') . DS);

//包含基础文件
require SYSTEM_PATH.'F.php';
require SYSTEM_PATH.'fay/core/Loader.php';

//注册自动加载
spl_autoload_register('fay\core\Loader::autoload');

//捕获报错
$error_handler = new ErrorHandler();
$error_handler->register();


/**
 * 这是一个debug辅助方法，之所以放这里，是因为放哪儿都不合适
 * 格式化输出一个数组
 * @param array $arr
 * @param boolean $encode 若此参数为true，则会对数组内容进行html实体转换
 * @param boolean $return 若此参数为true，则不直接输出数组，而是以变量的方式返回
 */
function dump($arr, $encode = false, $return = false){
	if($encode){
		$arr = F::input()->filterR('fay\helpers\Html::encode', $arr);
	}
	if($return){
		ob_start();
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}else{
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}