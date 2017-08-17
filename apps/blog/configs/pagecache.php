<?php
return array(
    'frontend/post/item'=>array(
        'params'=>array('id'),
        'ttl'=>5,
    ),
    'blog/api/post/get'=>array(
        'params'=>array('id', 'fields', 'cat'),
        'ttl'=>5,
    ),
    'blog/frontend/sitemap/xml'=>array(
        'params'=>array(),
        'ttl'=>5,
    ),
);