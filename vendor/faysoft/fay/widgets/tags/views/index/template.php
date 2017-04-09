<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget \fay\widgets\tags\controllers\IndexController
 * @var $tags array
 */
?>
<div class="widget widget-tags" id="widget-<?php echo HtmlHelper::encode($widget->alias)?>">
    <div class="widget-title">
        <h3><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
    </div>
    <div class="widget-content">
        <div class="cf tag-cloud"><?php
            foreach($tags as $t){
                echo HtmlHelper::link($t['tag']['title'], $t['tag']['link'], array(
                    'title'=>$t['counter']['posts'] . '篇文章'
                ));
            }
        ?></div>
    </div>
</div>