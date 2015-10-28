<?php
/**
 * url重写（不要改以下设置，否则可能导致系统无法正常运行）
 * 当然你也可以在服务器上做这些设置，当服务器不方便设置的时候，这里更方便程序员掌控。
 */
return array(
	'/^a$/'=>'admin/login/index',
	
	//根据widget别名加载
	'/^widget\/load$/'=>'api/widget/load',//别名跟在问号后面参数中
	'/^widget\/load\/(.+)$/'=>'api/widget/load/alias/$1',//别名包含在url重写中
	
	//根据widget别名获取widget数据（不渲染，以ajax方式返回）
	'/^widget\/data$/'=>'api/widget/data',//别名跟在问号后面参数中
	'/^widget\/data\/(.+)$/'=>'api/widget/data/alias/$1',//别名包含在url重写中
	
	
	//根据widget名称加载
	'/^widget\/render$/'=>'api/widget/render',//widget名称在问号后面参数中（名称很可能包含斜杠，故一般不会在url重写中）
	'/^widget\/(\w+)$/'=>'api/widget/render/name/$1',//widget名称包含在url重写中
	
	'/^tools$/'=>'tools/index/index',//工具
	
	'/^api\/analyst$/'=>'api/analyst/js',//访问统计
	
	//图片显示
	'/^file\/pic(.*)$/'=>'api/file/pic$1',
	'/^file\/vcode(.*)$/'=>'api/file/vcode$1',
	'/^file\/qrcode(.*)$/'=>'api/file/qrcode$1',
	'/^file\/download(.*)$/'=>'api/file/download$1',
	
	'/^redirect(.*)$/'=>'api/redirect/index$1',//页面跳转
);