<?php
return array(
	/**
	 * 刚开始无意义的加了个guide二级目录，已经被谷歌收录了，只好做个301重定向来弥补
	 */
// 	'after_uri'=>array(
// 		array(
// 			'function'=>function(){
// 				$request_uri = Uri::getInstance()->request_uri;
// 				if(substr($request_uri, 0, 5) == 'guide'){
// 					header('HTTP/1.1 301 Moved Permanently');
// 					header('Location: '.\F::config()->get('base_url').substr($request_uri, 6));
// 					die;
// 				}
// 			},
// 		),
// 	),
);