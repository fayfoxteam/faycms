<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget
 */
?>
<div class="m-contact">
    <h3><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
    <ul><?php
        foreach($widget->config['data'] as $d){
            echo HtmlHelper::tag('li', array(), "<span>{$d['key']}</span>: {$d['value']}");
        }
    ?></ul>
</div>