<?php
return array(
    '/^(\w+)\/(\d+)\.html$/'=>'amq/frontend/post/item:cat=$1&id=$2',
    '/^(\w+)\/$/'=>'amq/frontend/post/index:cat=$1',
    '/^(\w+)\.html$/'=>'amq/frontend/page/item:page=$1',
);