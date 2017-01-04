<?php
return array(
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(//隐藏文章编辑时不需要的编辑框
			'router'=>'/^admin\/post\/(create|edit|index).*$/i',
			'function'=>'siwi\\plugins\\HideBoxes::run',
		),
		array(//移除不需要的菜单
			'router'=>'/^(admin)\/.*$/i',
			'function'=>'siwi\\plugins\\AdminMenu::run',
		),
		array(//这只是个测试
			'router'=>'/^admin\/index\/index.*$/i',
			'function'=>'siwi\\plugins\\Dashboard::run',
		),
	),
);