<?php
use fay\helpers\HtmlHelper;

/**
 * @var $links array
 * @var $widget \cms\widgets\friendlinks\controllers\IndexController
 */
?>
<div class="widget widget-friendlinks" id="<?php echo HtmlHelper::encode($widget->alias)?>">
    <div class="widget-title">
        <h3><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
    </div>
    <div class="widget-content">
        <ul>
        <?php foreach($links as $l){?>
            <li><?php echo HtmlHelper::link($l['title'], $l['url']);?></li>
        <?php }?>
        </ul>
    </div>
</div>