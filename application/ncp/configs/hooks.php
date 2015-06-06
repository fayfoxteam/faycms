<?php
return array(
	/**
	 * 文章创建前执行
	 */
	'before_post_create'=>array(
		array(
			'function'=>array('ncp\plugins\TourRoute', 'addBox'),
		),
	),
	/**
	 * 文章创建完成后执行
	 */
	'after_post_created'=>array(
		array(
			'function'=>array('ncp\plugins\TourRoute', 'save'),
		),
	),
	/**
	 * 文章更新完成后执行
	 */
	'after_post_updated'=>array(
		array(
			'function'=>array('ncp\plugins\TourRoute', 'save'),
		),
	),
	/**
	 * 文章更新前执行
	 */
	'before_post_update'=>array(
		array(
			'function'=>array('ncp\plugins\TourRoute', 'setRoutes'),
		),
		array(
			'function'=>array('ncp\plugins\TourRoute', 'addBox'),
		),
	),
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(
			'router'=>'/^(admin)\/.*$/i',
			'function'=>array('ncp\plugins\AdminMenu', 'run'),
		),
		array(
			'router'=>'/^admin\/post\/(create|edit|index).*$/i',
			'function'=>array('ncp\plugins\HideBoxes', 'run'),
		),
	),
);