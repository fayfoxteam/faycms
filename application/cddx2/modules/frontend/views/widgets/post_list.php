<?php
use fay\helpers\HtmlHelper;
?>
<ul class="inner-post-list"><?php
    foreach($posts as $k => $p){
        echo HtmlHelper::link('<span>'.HtmlHelper::encode($p['post']['title']).'</span>', array('post/'.$p['post']['id']), array(
            'wrapper'=>'li',
            'before'=>array(
                'tag'=>'time',
                'text'=>date('Y-m-d', $p['post']['publish_time']),
            ),
            'encode'=>false,
            'title'=>HtmlHelper::encode($p['post']['title']),
        ));
        
        if(($k + 1) % 5 == 0){
            echo HtmlHelper::tag('li', array(
                'class'=>'separator',
            ), '');
        }
    }
?></ul>