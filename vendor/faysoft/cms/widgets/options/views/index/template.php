<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget cms\widgets\options\controllers\IndexController
 * @var $title string
 * @var $data array
 */

if(!empty($widget->config['title'])){
    echo HtmlHelper::tag('h3', array(), HtmlHelper::encode($title));
}

foreach($data as $d){
    echo HtmlHelper::tag('p', array(
        'prepend'=>array(
            'tag'=>'label',
            'text'=>HtmlHelper::encode($d['key']),
        )
    ), HtmlHelper::encode($d['value']));
}