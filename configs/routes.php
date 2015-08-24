<?php
/**
 * url重写（不要改以下设置，否则可能导致系统无法正常运行）
 * 当然你也可以在服务器上做这些设置，当服务器不方便设置的时候，这里更方便程序员掌控。
 */
return array(
	'/^a$/'=>'admin/login/index',
	
	//根据widget别名加载
	'/^widget\/load$/'=>'tools/widget/load',//别名跟在问号后面参数中
	'/^widget\/load\/(.+)$/'=>'tools/widget/load/alias/$1',//别名包含在url重写中
	
	//根据widget名称加载
	'/^widget\/render$/'=>'tools/widget/render',//widget名称在问号后面参数中（名称很可能包含斜杠，故一般不会在url重写中）
	'/^widget\/(\w+)$/'=>'tools/widget/render/name/$1',//widget名称包含在url重写中
	
	'/^tools$/'=>'tools/index/index',//工具
	
	'/^tools\/analyst$/'=>'tools/analyst/js',//访问统计
	
	//图片显示
	'/^file\/pic(.*)$/'=>'tools/file/pic$1',
	'/^file\/vcode(.*)$/'=>'tools/file/vcode$1',
	'/^file\/qrcode(.*)$/'=>'tools/file/qrcode$1',
	'/^file\/download(.*)$/'=>'tools/file/download$1',
	
	'/^redirect(.*)$/'=>'tools/redirect/index$1',//页面跳转
);