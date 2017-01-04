<?php
return array(
	'frontend/post/item'=>array(
		'params'=>array('id'),
		'ttl'=>5,
		'on_post'=>'remove',
	),
	'frontend/sitemap/xml'=>array(
		'params'=>array(),
		'ttl'=>86400 * 3,
		'on_post'=>'noaction',
		'handler'=>function(){
			\F::config()->set('debug', false);
			header('Content-type: text/xml');
		},
	),
);