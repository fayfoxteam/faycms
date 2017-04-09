<?php
use fay\helpers\HtmlHelper;

echo HtmlHelper::link('<span>'.HtmlHelper::encode($data['title']).'</span>', array('post-'.$data['id']), array(
    'wrapper'=>'li',
    'before'=>array(
        'tag'=>'time',
        'text'=>date('Y-m-d', $data['publish_time']),
    ),
    'encode'=>false,
    'title'=>HtmlHelper::encode($data['title']),
));

if($index % 5 == 0){
    echo HtmlHelper::tag('li', array(
        'class'=>'separator',
    ), '');
}