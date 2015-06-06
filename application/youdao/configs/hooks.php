<?php
return array(
	/**
	 * 自定义一些admin左侧导航条信息
	 */
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(
			'router'=>'/^(admin)\/.*$/i',
			'function'=>array('youdao\\plugins\\AdminMenu', 'run'),
		),
	),
);