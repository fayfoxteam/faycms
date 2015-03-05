<?php
return array(
	/**
	 * 自定义一些admin左侧导航条信息
	 */
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(
			'router'=>'/^(admin)\/.*$/i',
			'function'=>function(){
				if(method_exists(\F::app(), 'addMenuTeam')){
					\F::app()->addMenuTeam(array(
						'label'=>'表格上传',
						'directory'=>'excel',
						'sub'=>array(
							array('label'=>'上传','router'=>'admin/excel/index',),
						    array('label'=>'查看学生信息','router'=>'admin/excel/show',),
						),
						'icon'=>'icon-book',
					));
				}
			},
		),
	),
);