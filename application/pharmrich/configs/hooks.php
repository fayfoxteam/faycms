<?php
return array(
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(
			'router'=>'/^admin\/post\/(create|edit|index).*$/i',
			'function'=>function(){
				\F::app()->removeBox('alias');
				\F::app()->removeBox('likes');
				\F::app()->removeBox('category');
				\F::app()->removeBox('seo');
				\F::app()->removeBox('tags');
				\F::app()->removeBox('props');
				\F::app()->removeBox('gather');
			},
		),
	),
);