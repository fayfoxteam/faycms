<?php
/*
 * 页面缓存
 */
return array(
// 	'admin/login/index'=>array(
// 		'params'=>array(),
// 		'ttl'=>86400,
// 		'on_post'=>'noaction',//noaction无操作, rebuild重新创建, remove删除（不重建）
// 	),
	'frontend/sitemap/xml'=>array(
		'params'=>array(),
		'ttl'=>86400 * 3,
		'on_post'=>'noaction',
		'function'=>function(){
			header('Content-type: text/xml');
		},//在返回缓存文件前执行的回调函数
	),
// 	'frontend/post/item'=>array(
// 		'params'=>array('id'),
// 		'ttl'=>1,
// 		'on_post'=>'remove',
// 	),
);