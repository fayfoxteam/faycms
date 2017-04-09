<?php
use fay\helpers\HtmlHelper;

/**
 * @var $alias string
 * @var $widget
 * @var $tags array
 */
?>
<div class="widget widget-tag-cloud">
    <h5 class="widget-title"><?php echo HtmlHelper::encode($widget->config['title'])?></h5>
    <div class="cf tag-cloud"><?php
        foreach($tags as $t){
            echo HtmlHelper::link($t['tag']['title'], $t['tag']['link'], array(
                'title'=>$t['counter']['posts'] . '篇文章'
            ));
        }
    ?></div>
</div>