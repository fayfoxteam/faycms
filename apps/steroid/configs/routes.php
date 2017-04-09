<?php
return array(
    //文章详情页
    '/^post\/([\d-]+)$/'=>'post/item/id/$1',
    //分类文章列表
    '/^cat\/([\d-]+)$/'=>'post/index/cat/$1',
    //标签
    '/^tag\/(.*)$/'=>'tag/item/tag_title/$1',
);