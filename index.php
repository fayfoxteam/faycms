<?php
/**
 * APACHE环境，当伪静态未开启时，该文件能让系统正常运行。
 * 但**强烈建议**开启伪静态功能以达到良好的性能和美观的URL
 * 
 * NGINX环境默认不支持pathinfo，配置pathinfo的复杂度甚至高于配置url重写，不建议采用这种方式
 */
$folder = dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
$request = substr($_SERVER['REQUEST_URI'], strlen($folder.'/index.php/'));

if(preg_match('/^(assets|uploads|apps|favicon.ico|robots\.txt)/', $request)){
	//静态文件
	if($pos = strpos($request, '?')){
		//截掉问号后面部分
		$filename = './public/'.substr($request, 0, $pos);
	}else{
		$filename = './public/'.$request;
	}
	$ext = substr(strrchr($filename, '.'), 1);
	
	//发送mime type header
	$mines = require './configs/mimes.php';
	if(isset($mines[$ext])){
		if(is_array($mines[$ext])){
			header('Content-type:'.$mines[$ext][0]);
		}else{
			header('Content-type:'.$mines[$ext]);
		}
	}else{
		if(function_exists('finfo_open')){
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			header('Content-type:'.$mimetype);
		}
	}
	
	readfile($filename);
}else{
	//动态页面
	define('NO_REWRITE', 1);//未开启伪静态标记
	
	require './public/index.php';
}