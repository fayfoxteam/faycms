<?php
return array(
	/**
	 * 自定义一些admin左侧导航条信息
	 */
	'after_controller_constructor'=>array(
		//Controller实例化后执行
		array(
			'router'=>'/^(admin)\/.*$/i',
			'handler'=>function(){
				if(method_exists(\F::app(), 'addMenuTeam')){
					\F::app()->addMenuTeam(array(
						'title'=>'记账',
						'alias'=>'bill',
						'css_class'=>'fa fa-rmb',
						'link'=>'javascript:;',
						'target'=>'',
						'children'=>array(
							array(
								'title'=>'账单',
								'link'=>'cms/admin/bill/index',
								'target'=>'',
								'alias'=>'',
								'css_class'=>'',
							),
							array(
								'title'=>'分类',
								'link'=>'cms/admin/bill/cat',
								'target'=>'',
								'alias'=>'',
								'css_class'=>'',
							),
						),
					));
				}
			},
		),
	),
);