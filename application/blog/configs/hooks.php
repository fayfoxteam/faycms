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
						'label'=>'记账',
						'directory'=>'bill',
						'sub'=>array(
							array('label'=>'账单','router'=>'admin/bill/index',),
							array('label'=>'分类','router'=>'admin/bill/cat',),
						),
						'icon'=>'fa fa-rmb',
					));
				}
			},
		),
	),
);