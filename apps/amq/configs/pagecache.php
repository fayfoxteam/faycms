<?php
/*
 * 页面缓存
 */
return array(
//    'amq/frontend/index/index'=>array(
//        'params'=>array('page'),
//        'ttl'=>600,
//        'on_post'=>'noaction',//noaction无操作, rebuild重新创建, remove删除（不重建）
//    ),
    'cms/api/site-map/xml'=>array(
        'ttl'=>86400 * 2,
    ),
);