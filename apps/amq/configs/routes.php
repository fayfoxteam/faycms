<?php
return array(
    '/^post\/(\d+)$/'=>'amq/frontend/post/item:id=$1',
    '/^(\w+)\/$/'=>'amq/frontend/post/index:cat=$1',
);