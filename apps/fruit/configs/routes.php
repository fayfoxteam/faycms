<?php
return array(
    '/^page\/(\d+)$/'=>'page/item:id=$1',
    '/^product\/(\d+)$/'=>'product/item:id=$1',
    '/^product\/([\w-]+)$/'=>'product/index:cat_alias=$1',
    
    '/^news\/(\d+)$/'=>'news/item:id=$1',
    '/^news\/([\w-]+)$/'=>'news/index:cat_alias=$1',
);