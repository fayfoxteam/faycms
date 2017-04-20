<?php
/**
 * url重写（不要改以下设置，否则可能导致系统无法正常运行）
 * 当然你也可以在服务器上做这些设置，当服务器不方便设置的时候，这里更方便程序员掌控。
 */
return array(
    '/^a$/'=>'cms/admin/login/index',
    
    //根据widget别名加载
    '/^widget\/load$/'=>'cms/api/widget/load',//别名跟在问号后面参数中
    '/^widget\/load\/([\w_-]+)$/'=>'cms/api/widget/load:alias=$1',//别名包含在url重写中
    '/^widget\/load\/([\w_-]+)\/([\w_-]+)$/'=>'cms/api/widget/load:alias=$1&action=$2',//别名包含在url重写中
    
    //根据widget别名获取widget数据（不渲染，以ajax方式返回）
    '/^widget\/data$/'=>'cms/api/widget/data',//别名跟在问号后面参数中
    '/^widget\/data\/(.+)$/'=>'cms/api/widget/data:alias=$1',//别名包含在url重写中
    
    
    //根据widget名称加载
    '/^widget\/render$/'=>'cms/api/widget/render',//widget名称在问号后面参数中（名称很可能包含斜杠，故一般不会在url重写中）
    '/^widget\/(\w+)$/'=>'cms/api/widget/render:name=$1',//widget名称包含在url重写中
    
    '/^tools$/'=>'cms/tools/index/index',//工具
    
    '/^file\/pic(.*)$/'=>'cms/api/file/pic$1',
    '/^file\/vcode(.*)$/'=>'cms/api/file/vcode$1',
    '/^file\/qrcode(.*)$/'=>'cms/api/file/qrcode$1',
    '/^file\/download(.*)$/'=>'cms/api/file/download$1',
    
    '/^redirect(.*)$/'=>'cms/api/redirect/index$1',//页面跳转
    
    '/^install$/'=>'cms/install/index/index',//安装
);