<?php
/*
 * 页面缓存
 */
return array(
    'amq/frontend/index/index'=>array(
        'params'=>array(),
        'ttl'=>300,
        'on_post'=>'noaction',//noaction无操作, rebuild重新创建, remove删除（不重建）
    ),
);