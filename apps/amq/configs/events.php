<?php
return array(
    /**
     * 自定义一些admin左侧导航条信息
     */
    'after_uri'=>array(
        //Controller实例化后执行
        array(
            'router'=>'/^cms\/api\/site-map\/xml$/i',
            'handler'=>function(){
                header('Content-Type:application/xml');
            },
        ),
    ),
);