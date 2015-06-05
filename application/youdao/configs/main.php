<?php
/**
 * 该文件的配置项将覆盖外层/config/main.php的配置
 * 若不设置则默认使用外面的配置参数
 */
return array(
	/*
	 * 在一台服务器上跑多个cms的时候，以此区分session，可以随便设置一个
	 */
	'session_namespace'=>'youdao',
	
	/*
	 * 是否启用钩子，视application而定
	 * 默认为false
	 */
	'hook'=>true,
	
	/*
	 * 当前application包含的模块
	 */
	'modules'=>array(
		'frontend'
	),
);